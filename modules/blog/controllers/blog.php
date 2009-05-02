<?php
class Blog_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  /*
   * events in here assume everything is being loaded normally
   * in non-ajax mode. so we build the page in pieces everytime
   */
	function _index($tool_id)
	{
		$db = new Database;
		$action	= uri::easy_segment('2');
		$value	= uri::easy_segment('3');
		$value2	= uri::easy_segment('4');
		
		/*
		 * need the parent to setup appropriate views and user-specific settings
		# get parent 
		$parent = $db->query("SELECT * FROM blogs 
			WHERE id = '$tool_id' AND fk_site = '$this->site_id'
		")->current();	
		*/
		
		$primary = new View("blog/index");
		$primary->tool_id = $tool_id;
		$primary->tags = $this->_get_tags($tool_id);
		$primary->add_root_js_files('ajax_form/ajax_form.js');

		switch($action)
		{
			case 'entry':
				$content = $this->_single_post($tool_id, $value);
				break;
			
			case 'tag':
				$content = $this->_single_post($tool_id, $value);
				break;
				
			case 'archive':
				$content = new View("blog/archive");
				$date_search = false;
				
				# year search
				if(! empty($value) AND empty($value2) )
				{
					tool_ui::validate_id($value);
					$start = $value;
					(int)$end = $value+1;				
					$date_search = "AND created >= '$start' AND created < '$end'";		
				}
				# month search
				elseif(! empty($value) AND !empty($value2) )
				{
					tool_ui::validate_id($value);
					$month = $value2+1;
					if(10 > $month)
						$month = "0$month";
						
					$start	= "$value-$value2";
					$end	= "$value-$month";
					
					#echo $start.' '.$end;die();
					$date_search = "AND created >= '$start' AND created < '$end'";				
				}

				$items = $db->query("SELECT blog_items.*, 
					DATE_FORMAT(created, '%Y') as year,
					DATE_FORMAT(created, '%M') as month,
					DATE_FORMAT(created, '%e') as day
					FROM blog_items 
					WHERE blog_items.parent_id = '$tool_id' 
					AND blog_items.fk_site = '$this->site_id'
					$date_search
					ORDER BY created
				");
				$content->items = $items;
				
				break;
				
			case 'comment':
			/* comment action is only called via ajax (comment form and view_comment link).
			 * users calling this action should just get the full post_view
			 *
			 */
				tool_ui::validate_id($value);
				
				if($_POST)
					$primary->response = $this->_post_comment($value);
				
				$content = $this->_single_post($tool_id, NULL, $value);
				
				break;
				
			default:
				# blog homepage
				$items = $db->query("SELECT blog_items.*, 
					COUNT(blog_items_comments.id) as comments,
					DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created 
					FROM blog_items 
					JOIN blog_items_comments ON blog_items.id = blog_items_comments.item_id
					WHERE blog_items.parent_id = '$tool_id' AND blog_items.fk_site = '$this->site_id'
					GROUP BY blog_items.id
				");
				
				$content = new View('blog/home');	
				$content->items = $items;
				
				$primary->add_root_js_files('expander/expander.js');
				$primary->global_readyJS('
				
					$("div.post_body").expander({
						 userCollapseText: "[collapse]"
					});
					
					$(".get_comments").click(function(){
						id = $(this).attr("rel");
						$("#post_comments_"+id).load("/blog/comment/"+id);
						return false;
					});
					
		$("body").submit($.delegate({
			"form.public_ajaxForm": function(e){
				form = $(e.target);
				var options = {
					beforeSubmit: function(){
						if( $("input[type=text]", form).jade_validate() )
							return true;
						else
							return false;
					},
					success: function(data) {
						$(".comments_wrapper", form).append(data);
						$(".add_comment", form).replaceWith("<div class=\"blog_response\">Comment Added!</div>");
					}	
				};
				$(form).ajaxSubmit(options);	
				return false;
		}	
	}));
	
				');		
				break;
		}	
		$primary->content = $content;
		return $primary;
	}
	
	
	# return single post view
	# get by url, or id if sent from comment form
	function _single_post($tool_id, $url=NULL, $id=NULL)
	{
		$db = new Database;
		
		$content = new View("blog/single");
		
		$field = 'url';
		if(NULL !== $id)
		{
			$field	= 'id';
			$url	= $id;
		}	
		$item = $db->query("SELECT *, DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created 
			FROM blog_items 
			WHERE $field = '$url' AND fk_site = '$this->site_id'
		")->current();

		if(! is_object($item) )
			return 'This post does not exist';
		
		$content->item = $item;	
		$content->comments = $this->_get_comments($item->id);
	
		return $content;
	}

	# get all tags
	function _get_tags($tool_id)
	{
		$db = new Database;
		$tags = $db->query("SELECT * FROM blog_items_meta
			WHERE parent_id = '$tool_id'
			AND fk_site='$this->site_id'
			ORDER BY id
		
		");
		return $tags;		
	}
	
	function _get_comments($post_id=NULL)
	{
		$db = new Database;
		$content = new View('blog/comments');
		$comments =  $db->query("SELECT *
			FROM blog_items_comments 
			WHERE item_id = '$post_id' AND fk_site = '$this->site_id'
		");
		$content->comments = $comments;
		$content->item_id = $post_id;
		return $content;

	}

	function _post_comment($post_id=NULL)
	{
		$db = new Database;
		if($_POST)
		{
			$data = array(
				'item_id'		=> $post_id,
				'fk_site'		=> $this->site_id,
				'body'			=> $_POST['body'],
				'author'		=> $_POST['author'],
				'author_url'	=> $_POST['url'],
				'author_email'	=> $_POST['email'],
				'created_at'	=> date("Y-m-d H:m:s"),					
			);
			$db->insert('blog_items_comments', $data);
			return '<div class="comment_item">' . $_POST['author'] . '<br>' . $_POST['body'] . '</div>';
		}
	}	
}

/* -- end of application/controllers/blog.php -- */