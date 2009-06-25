<?php
class Blog_Controller extends Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->db = new Database;
	}
	
/*
* The _index controller is only called when building full pages.
* This therefore assumes no ajax calls.
*/
	function _index($tool_id)
	{
		$url_array	= uri::url_array();
		$page_name	= $this->get_page_name(@$url_array['0'], 'blog', $tool_id);
		$action		= @$url_array['1'];
		$value		= @$url_array['2'];
		$value2		= @$url_array['3'];
		
		/*
		 * need the parent to setup appropriate views and user-specific settings
		 */
		# get parent 
		$parent = $this->db->query("
			SELECT * FROM blogs 
			WHERE id = '$tool_id'
			AND fk_site = '$this->site_id'
		")->current();	
		if(!is_object($parent))
			die('invalid tool');
		
		$primary = new View("public_blog/index");
		$primary->tool_id = $tool_id;
		$primary->set_global('blog_page_name', $page_name);
		$primary->tags = $this->_get_tags($tool_id);
		$primary->sticky_posts = $this->get_sticky_posts($parent->sticky_posts);
		$primary->recent_comments = $this->get_recent_comments($tool_id);
		$primary->add_root_js_files('ajax_form/ajax_form.js');

		switch($action)
		{
			case 'entry':
				$content = $this->single_post($page_name, $value);
				break;
			
			case 'tag':
				$content = $this->tag_search($tool_id, $value);
				break;
				
			case 'archive':
				$content = $this->show_archive($tool_id, $value, $value2);
				break;

			case 'comment':
				valid::id_key($value);
				if($_POST)
					$primary->response = $this->post_comment($value);
				
				$content = $this->single_post($page_name, NULL, $value);
				break;
				
			default:
				# blog homepage
				$items = $this->db->query("
					SELECT blog_items.*, 					
					DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on,
					GROUP_CONCAT(DISTINCT blog_items_tags.value ORDER BY blog_items_tags.value  separator ',') as tag_string,
					COUNT(DISTINCT blog_items_comments.id) as comments
					FROM blog_items
					LEFT JOIN blog_items_tags ON blog_items.id = blog_items_tags.item_id
					LEFT JOIN blog_items_comments ON blog_items.id = blog_items_comments.item_id
					WHERE blog_items.parent_id = '$tool_id'
					AND blog_items.fk_site = '$this->site_id'					
					AND blog_items.status = 'publish'
					GROUP BY blog_items.id 
					ORDER BY created DESC
					# add custom limit here
				");
				$content = new View('public_blog/multiple_posts');	
				$content->items = $items;	
				break;
		}
		$primary->content = $content;
		
		return $this->public_template($primary, 'blog', $tool_id);
	}
	
	
/*
 * return single post view
 * get by url, or id if sent from comment form
 */
	private function single_post($page_name, $url=NULL, $id=NULL)
	{
		$content = new View("public_blog/single");
		$field = 'url';
		
		if(NULL !== $id)
		{
			$field	= 'id';
			$url	= $id;
		}
		
		$item = $this->db->query("
			SELECT blog_items.*, DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, 
			GROUP_CONCAT(DISTINCT blog_items_tags.value ORDER BY blog_items_tags.value  separator ',') as tag_string
			FROM blog_items 
			LEFT JOIN blog_items_tags ON blog_items.id = blog_items_tags.item_id
			WHERE blog_items.$field = '$url'
			AND blog_items.fk_site = '$this->site_id'
			AND blog_items.status = 'publish'
		")->current();

		if(! is_object($item) )
			return 'This post does not exist';
		
		$content->item = $item;	
		$content->comments = $this->get_comments($page_name, $item->id, $item->parent_id);
		$content->blog_page_name = $page_name;
		return $content;
	}

/*
 * get and display a view of all posts having a specific tag.
 * does not use ajax.
 */
	private function tag_search($tool_id, $tag)
	{
		$content = new View('public_blog/multiple_posts');
		$items = $this->db->query("
			SELECT blog_items.*,					
			DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, blog_items_tags.value,
			GROUP_CONCAT(DISTINCT blog_items_tags.value ORDER BY blog_items_tags.value  separator ',') as tag_string,
			FIND_IN_SET('$tag', GROUP_CONCAT(DISTINCT blog_items_tags.value)) as tag_match,
			COUNT(DISTINCT blog_items_comments.id) as comments
			FROM blog_items
			LEFT JOIN blog_items_tags ON blog_items.id = blog_items_tags.item_id
			LEFT JOIN blog_items_comments ON blog_items.id = blog_items_comments.item_id
			WHERE blog_items.parent_id = '$tool_id'
			AND blog_items.fk_site = '$this->site_id'					
			AND blog_items.status = 'publish'
			GROUP BY blog_items.id HAVING tag_match > '0'
			ORDER BY created DESC
		");
		$content->items = $items;
		#Javascript
		$content->add_root_js_files('expander/expander.js');		
		return $content;		
	}
	
/*
 * get and display a view of all posts in archive format.
 * does not use ajax.
 */	
	private function show_archive($tool_id, $value, $value2)
	{
		$content = new View("public_blog/archive");
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
			SELECT blog_items.*, 
			DATE_FORMAT(created, '%Y') as year,
			DATE_FORMAT(created, '%M') as month,
			DATE_FORMAT(created, '%e') as day
			FROM blog_items 
			WHERE blog_items.parent_id = '$tool_id' 
			AND blog_items.fk_site = '$this->site_id'
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
		$content = new View('public_blog/comments');
		
		if(NULL == $tool_id)
		{
			$parent =  $this->db->query("
				SELECT parent_id FROM blog_items 
				WHERE id = '$post_id' AND fk_site = '$this->site_id'
			")->current();
			$tool_id = $parent->parent_id;
		}			
			
		$comments =  $this->db->query("
			SELECT *,
			DATE_FORMAT(created_at, '%M %e, %Y, %l:%i%p') as clean_date
			FROM blog_items_comments 
			WHERE item_id = '$post_id'
			AND fk_site = '$this->site_id'
			ORDER BY created_at
		");
		$content->comments = $comments;
		$content->item_id = $post_id;
		$content->tool_id = $tool_id;
		$content->blog_page_name = $page_name;
		
		# Javascript 
		# TODO: this is being duplicated on all posts,
		# make this into a function and call the function instead.
		if($this->client->logged_in())
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
			ini_set('date.timezone', 'America/Los_Angeles');

			$data = array(
				'parent_id'		=> $_POST['tool_id'],
				'item_id'		=> $post_id,
				'fk_site'		=> $this->site_id,
				'body'			=> $_POST['body'],
				'name'			=> $_POST['name'],
				'url'			=> $_POST['url'],
				'email'			=> $_POST['email'],
				'created_at'	=> date("Y-m-d H:m:s"),					
			);
			
			$insert_id = $this->db->insert('blog_items_comments', $data);
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
	function _get_tags($tool_id)
	{
		$tags = $this->db->query("
			SELECT id, value,
			COUNT(tags.id) as qty
			FROM blog_items_tags as tags
			WHERE parent_id = '$tool_id'
			AND fk_site='$this->site_id'
			GROUP BY tags.value
			ORDER BY qty DESC
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
			SELECT blog_items.title, blog_items.url
			FROM blog_items
			WHERE id IN ($id_string)
			AND fk_site = '$this->site_id'
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
			SELECT comments.name, blog_items.title, blog_items.url
			FROM blog_items_comments as comments
			JOIN blog_items ON blog_items.id = comments.item_id
			WHERE comments.parent_id = '$tool_id' AND comments.fk_site = '$this->site_id'
			ORDER BY comments.created_at DESC
			LIMIT 0,5
		");
		return $comments;	
	}



	
/*
 * page builders frequently use ajax to update their content
 * common method for handling ajax requests.
 * param $url_array = (array) an array of url signifiers
 * param $tool_id 	= (int) the tool id of the tool.
 */ 
	function _ajax($url_array, $tool_id)
	{
		@list( , $page_name, $action, $value) = $url_array;
		
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
					die( $this->post_comment($value) );
				else
					die( $this->get_comments($page_name, $value) );
				break;
				
			default:
				die('no action');
				break;
		}
	}
}

/* -- end of application/controllers/blog.php -- */