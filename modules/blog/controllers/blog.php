<?php defined('SYSPATH') OR die('No direct access allowed.');


class Blog_Controller extends Public_Tool_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->db = new Database;
	}
	
/*
* The _index controller is only called when building full pages.
* This therefore assumes no ajax calls.
* expects parent blog table object
*/
	public function _index($blog)
	{
		date_default_timezone_set('America/Los_Angeles');# just for now
		list($page_name, $action, $value, $value2) = Uri::url_array();
		$page_name	= $this->get_page_name($page_name, 'blog', $blog->id);
		$action		= (empty($action)) ? 'SomethingRandom' : $action ;

		$primary = new View("public_blog/blogs/index");
		$primary->tool_id = $blog->id;
		$primary->set_global('blog_page_name', $page_name);
		$primary->tags = $this->get_tags($blog->id);
		$primary->sticky_posts = $this->get_sticky_posts($blog->sticky_posts);
		$primary->recent_comments = $this->get_recent_comments($blog->id);

		
		switch($action)
		{
			case 'entry':
				
				$content = self::single_post($page_name, $value);
				break;
			
			case 'tag':
				$content = self::tag_search($blog->id, $value);
				break;
				
			case 'archive':
				$content = self::show_archive($blog->id, $value, $value2);
				break;

			case 'comment':
				valid::id_key($value);
				if($_POST)
					$primary->response = self::post_comment($value);
				
				$content = self::single_post($page_name, NULL, $value);
				break;
				
			default:
				# blog homepage
				$items = $this->db->query("
					SELECT blog_posts.*, 					
					DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on,
					GROUP_CONCAT(DISTINCT blog_post_tags.value ORDER BY blog_post_tags.value  separator ',') as tag_string,
					COUNT(DISTINCT blog_post_comments.id) as comments
					FROM blog_posts
					LEFT JOIN blog_post_tags ON blog_posts.id = blog_post_tags.blog_post_id
					LEFT JOIN blog_post_comments ON blog_posts.id = blog_post_comments.blog_post_id
					WHERE blog_posts.blog_id = '$blog->id'
					AND blog_posts.fk_site = '$this->site_id'					
					AND blog_posts.status = 'publish'
					GROUP BY blog_posts.id 
					ORDER BY created DESC
				");
				$content = new View('public_blog/blogs/multiple_posts');	
				$content->items = $items;	
				break;
		}
		$primary->content = $content;
		# get the custom javascript;
		$primary->global_readyJS(self::javascripts());
		
		return $this->wrap_tool($primary, 'blog', $blog);
	}
	

/*
 * output the appropriate javascript based on the calendar view.
 * currently we just have one though
 */	
	private function javascripts()
	{
		$js = '
			$("body").click($.delegate({
				".blog_wrapper a[rel*=blog_ajax]": function(e){
					$(".blog_content").html("<div class=\"ajax_loading\">Loading...</div>");
					$(".blog_content").load(e.target.href);
					return false;
				},
				".blog_wrapper a.get_comments":function(e){
					var url		= $(e.target).attr("rel");
					$container	= $(e.target).parent();
					
					$container.html("<div class=\"ajax_loading\">Loading...</div>");
					$.get(url, function(data){
						$container.replaceWith(data);
					});
					return false;
				}
			}));

			$("body").submit($.delegate({
				".blog_wrapper form.public_ajaxForm": function(e){
					var form = $(e.target);
					$(form).ajaxSubmit({
						beforeSubmit: function(){
							if( $("input[type=text]", form).jade_validate() )
								return true;
							
							return false;
						},
						success: function(data) {
							$(".comments_wrapper", form).append(data);
							$(".add_comment", form).replaceWith("<div class=\"blog_response\">Comment Added!</div>");
							e.stopPropagation();
						}
					});
					e.stopPropagation();		
					return false;
				}
			}));		
		
		';
		# place the javascript.
		return $this->place_javascript($js, FALSE);
	}
	
	
/*
 * return single post view
 * get by url, or id if sent from comment form
 */
	private function single_post($page_name, $url=NULL, $id=NULL)
	{
		$content = new View("public_blog/blogs/single");
		$field = 'url';
		
		if(NULL !== $id)
		{
			$field	= 'id';
			$url	= $id;
		}
		
		$item = $this->db->query("
			SELECT blog_posts.*, DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, 
			GROUP_CONCAT(DISTINCT blog_post_tags.value ORDER BY blog_post_tags.value  separator ',') as tag_string
			FROM blog_posts 
			LEFT JOIN blog_post_tags ON blog_posts.id = blog_post_tags.blog_post_id
			WHERE blog_posts.$field = '$url'
			AND blog_posts.fk_site = '$this->site_id'
			AND blog_posts.status = 'publish'
		")->current();

		if(! is_object($item) )
			return 'This post does not exist';
		
		$content->item = $item;
		$content->set_global('title', $item->title);
		$content->comments = $this->get_comments($page_name, $item->id, $item->blog_id);
		$content->blog_page_name = $page_name;
		return $content;
	}

/*
 * get and display a view of all posts having a specific tag.
 * does not use ajax.
 */
	private function tag_search($tool_id, $tag)
	{
		$content = new View('public_blog/blogs/multiple_posts');
		$items = $this->db->query("
			SELECT blog_posts.*,					
			DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, blog_post_tags.value,
			GROUP_CONCAT(DISTINCT blog_post_tags.value ORDER BY blog_post_tags.value  separator ',') as tag_string,
			FIND_IN_SET('$tag', GROUP_CONCAT(DISTINCT blog_post_tags.value)) as tag_match,
			COUNT(DISTINCT blog_post_comments.id) as comments
			FROM blog_posts
			LEFT JOIN blog_post_tags ON blog_posts.id = blog_post_tags.blog_post_id
			LEFT JOIN blog_post_comments ON blog_posts.id = blog_post_comments.blog_post_id
			WHERE blog_posts.blog_id = '$tool_id'
			AND blog_posts.fk_site = '$this->site_id'					
			AND blog_posts.status = 'publish'
			GROUP BY blog_posts.id HAVING tag_match > '0'
			ORDER BY created DESC
		");
		$content->items = $items;
		#Javascript
		$content->request_js_files('expander/expander.js');		
		return $content;		
	}
	
/*
 * get and display a view of all posts in archive format.
 * does not use ajax.
 */	
	private function show_archive($tool_id, $value, $value2)
	{
		$content = new View("public_blog/blogs/archive");
		$date_search = false;
		
		# year search
		if(! empty($value) AND empty($value2) )
		{
			valid::year($value);
			$start = $value;
			(int)$end = $value+1;				
			$date_search = "AND created >= '$start' AND created < '$end'";		
		}
		# month search
		elseif(! empty($value) AND !empty($value2) )
		{
			valid::year($value);
			valid::month($value2);
			$month = $value2+1;
			if(10 > $month)
				$month = "0$month";
				
			$start		= "$value-$value2";
			$end		= "$value-$month";
			$date_search = "AND created >= '$start' AND created < '$end'";				
		}

		$items = $this->db->query("
			SELECT blog_posts.*, 
			DATE_FORMAT(created, '%Y') as year,
			DATE_FORMAT(created, '%M') as month,
			DATE_FORMAT(created, '%e') as day
			FROM blog_posts 
			WHERE blog_posts.blog_id = '$tool_id' 
			AND blog_posts.fk_site = '$this->site_id'
			$date_search
			ORDER BY created
			LIMIT 0, 10
		");
		$content->items = $items;
		return $content;
	}
	
/*
 * get and display a view of all comments from a specific post.
 * uses ajax
 */		
	private function get_comments($page_name, $post_id=NULL, $tool_id = NULL)
	{
		$content = new View('public_blog/blogs/comments');
		
		if(NULL == $tool_id)
		{
			$parent =  $this->db->query("
				SELECT blog_id FROM blog_posts 
				WHERE id = '$post_id' AND fk_site = '$this->site_id'
			")->current();
			$tool_id = $parent->blog_id;
		}			
			
		$comments = $this->db->query("
			SELECT *,
			DATE_FORMAT(created_at, '%M %e, %Y, %l:%i%p') as clean_date
			FROM blog_post_comments 
			WHERE blog_post_id = '$post_id'
			AND fk_site = '$this->site_id'
			ORDER BY created_at
		");
		$content->comments = $comments;
		$content->blog_post_id = $post_id;
		$content->tool_id = $tool_id;
		$content->blog_page_name = $page_name;
		$content->admin_js = '';
		# Javascript 
		# TODO: this is being duplicated on all posts,
		# make this into a function and call the function instead.
		if($this->client->can_edit($this->site_id))
			$content->admin_js = '
				$("#post_comments_'.$post_id.' .comment_item").each(function(i){
					var toolname = "blog";
					var id		= $(this).attr("rel");
					var del		= "<span class=\"icon cross\">&#160; &#160; </span> <a href=\"/get/edit_" + toolname + "/delete_comment/" + id + "\" class=\"js_admin_delete\" rel=\"comment_"+id+"\">delete</a>";
					var toolbar	= "<div class=\"jade_admin_item_edit\">" + del + "</div>";
					$(this).prepend(toolbar);
				});
			';
		return $content;

	}

/*
 * function for handling post request on the post-comment form.
 * uses ajax.
 */	
	private function post_comment($post_id=NULL)
	{
		if($_POST)
		{
			##ini_set('date.timezone', 'America/Los_Angeles');

			$data = array(
				'blog_id'		=> $_POST['tool_id'],
				'blog_post_id'		=> $post_id,
				'fk_site'		=> $this->site_id,
				'body'			=> $_POST['body'],
				'name'			=> $_POST['name'],
				'url'			=> $_POST['url'],
				'email'			=> $_POST['email'],
				'created_at'	=> strftime("%Y-%m-%d %H:%M:%S"),					
			);
			
			$insert_id = $this->db->insert('blog_post_comments', $data);
			return '		
			<div class="comment_item">				
				<div class="comment_name">'.$_POST['name'].' says...</div>
				<div class="comment_time">just now</div>
				<div class="comment_body">' .$_POST['body']. '</div>
			</div>
			';		
		}
	}

/* Sidebar functions */
	
/*
 * get all tags from this blog.
 */	
	private function get_tags($tool_id)
	{
		$tags = $this->db->query("
			SELECT id, value,
			COUNT(tags.id) as qty
			FROM blog_post_tags as tags
			WHERE blog_id = '$tool_id'
			AND fk_site='$this->site_id'
			GROUP BY tags.value
			ORDER BY qty DESC, value
		");
		return $tags;		
	}

/*
 * get all sticky posts from this blog.
 */		
	private function get_sticky_posts($id_string=Null)
	{
		if( empty($id_string) )
			return false;
			
		$item =  $this->db->query("
			SELECT blog_posts.title, blog_posts.url
			FROM blog_posts
			WHERE id IN ($id_string)
			AND fk_site = '$this->site_id'
			AND status = 'publish'
			LIMIT 0,5
		");
		return $item;	
	}

/*
 * get all recent comments from this blog.
 */		
	private function get_recent_comments($tool_id=Null)
	{
		$comments =  $this->db->query("
			SELECT comments.name, blog_posts.title, blog_posts.url
			FROM blog_post_comments as comments
			JOIN blog_posts ON blog_posts.id = comments.blog_post_id
			WHERE comments.blog_id = '$tool_id' AND comments.fk_site = '$this->site_id'
			ORDER BY comments.created_at DESC
			LIMIT 0,5
		");
		return $comments;	
	}



	
/*
 * ajax handler.
 */ 
	public function _ajax($url_array, $tool_id)
	{
		list($page_name, $action, $value) = $url_array;
		
		switch($action)
		{
			case 'entry':
				die( $this->single_post($page_name, $value) );
				break;
			
			case 'tag':
				break;	
				
			case 'archive':
				break;

			case 'comment':
				# this is an ajaxForm comment post request
				# OR ajax request to view comments
				valid::id_key($value);
				if($_POST)
					die( self::post_comment($value) );
				else
					die( self::get_comments($page_name, $value) );
				break;
				
			default:
				die('no action');
				break;
		}
	}
	
	
/*
 * logic executed after this blog tool is added to site.
 */
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{
			$new_post = ORM::factory('blog_post');
			$new_post->fk_site	= $site_id;
			$new_post->blog_id	= $tool_id;
			$new_post->url		= 'my-first-blog-post';
			$new_post->title	= 'My First Blog Post';
			$new_post->body		= '<p>All sorts of interesting content...</p> And then some more content <p>Looking good!</p>';
			$new_post->created	= strftime("%Y-%m-%d %H:%M:%S");
			$new_post->status	= 'publish';
			$new_post->save();
			
			$db = new Database;
			$data = array(
			   'fk_site'		=> $site_id,
			   'blog_post_id'	=> $new_post->id,
			   'blog_id'		=> $tool_id,
			   'value'			=> 'general',					
			);
			$db->insert('blog_post_tags', $data);
		
		
		}
		return 'add';
	}

	
	
}

/* -- end of application/controllers/blog.php -- */