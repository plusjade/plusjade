<?php
class Edit_Blog_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for blog module.
 *	Extends the module template to build page quickly in facebox frame mode.
 *	Only Logged in users should have access
 *
 */
	function __construct()
	{
		parent::__construct();
	}

/*
 * show all drafts, TODO:comment moderation.
 */ 
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$db = new Database;
		$primary = new View('edit_blog/manage');
		
		# Show drafts
		$items = $db->query("
			SELECT blog_items.*, 
			DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on 
			FROM blog_items
			WHERE blog_items.parent_id = '$tool_id'
			AND blog_items.fk_site = '$this->site_id'					
			AND status = 'draft'
			GROUP BY blog_items.id 
			ORDER BY created DESC
		");
		$primary->items = $items;
		die($primary);
	}

/*
 * add a new blog post
 */ 
	function add($tool_id=NULL)
	{
		valid::id_key($tool_id);	
		if($_POST)
		{
			$db = new Database;	
			$data = array(
				'fk_site'	=> $this->site_id,
				'parent_id'	=> $tool_id,
				'url'		=> $_POST['url'],
				'status'	=> $_POST['status'],
				'title'		=> $_POST['title'],
				'body'		=> $_POST['body'],
				'created'	=> strftime("%Y-%m-%d %H:%M:%S")
			);
			$item_id = $db->insert('blog_items', $data)->insert_id();
			
			self::save_tags($_POST['tags'], $item_id, $tool_id);
			die('Post added'); # success
		}
		die( $this->_view_add_single('blog', $tool_id) );
	}

/*
 * edit a blog post
 */ 
	function edit($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;
		if($_POST)
		{
			$data = array(
				'title'		=> $_POST['title'],
				'body'		=> $_POST['body'],
				'url'		=> $_POST['url'],
				'status'	=> $_POST['status'],
			);
			$db->update(
				'blog_items',
				$data,
				"id = '$id' AND fk_site='$this->site_id'"
			);
			self::save_tags($_POST['tags'], $id, $_POST['parent_id']);
			
			if(isset($_POST['sticky']))
			{
				$sticky_posts = '';
				
				if('stick' == $_POST['sticky'])
					$sticky_posts = $_POST['sticky_posts'] . ",$id";
				elseif('unstick' == $_POST['sticky'])
				{
					$sticky_posts = explode(',', $_POST['sticky_posts']);
					#print_r($sticky_posts);die();
					if($key = array_search($id, $sticky_posts))
						unset($sticky_posts[$key]);
						
					$sticky_posts = implode(',', $sticky_posts);
				}
	
				$db->query("
					UPDATE blogs
					SET sticky_posts = '$sticky_posts'
					WHERE id = '$_POST[parent_id]'
					AND fk_site = '$this->site_id'
				");	
			}
			die('Post Saved'); #status			
		}

		$primary = new View("edit_blog/edit_item");
		$item = $db->query("
			SELECT blog_items.*, DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, 
			GROUP_CONCAT(DISTINCT blog_items_tags.value, CONCAT('_',blog_items_tags.id) ORDER BY blog_items_tags.value  separator ',') as tag_string
			FROM blog_items 
			LEFT JOIN blog_items_tags ON blog_items.id = blog_items_tags.item_id
			WHERE blog_items.id = '$id'
			AND blog_items.fk_site = '$this->site_id'
		")->current();
		$primary->item = $item;
		
		$parent = $db->query("
			SELECT * 
			FROM blogs
			WHERE id='$item->parent_id'
			AND fk_site = '$this->site_id'
		")->current();
		
		$sticky_posts = explode(',', $parent->sticky_posts);
		$primary->sticky_posts = $parent->sticky_posts;
		$primary->is_sticky = (in_array($id, $sticky_posts)) ? TRUE : FALSE ;
		
		$primary->js_rel_command = "update-blog-$item->parent_id";
		die($primary);
	}

/*
 * Save tags to database.
 * (string) $tags (comma dilemenated)
 */
	private function save_tags($tags, $item_id, $parent_id)
	{
		$tags = trim($tags);
		if (empty($tags))
			return FALSE;
			
		$db = new Database;
		$tags = explode(',', $tags);
		
		foreach($tags as $tag)
		{
			$tag = trim($tag);
			$tag = preg_replace("(/W)", '_', $tag);
			$data = array(
			   'fk_site'	=> $this->site_id,
			   'item_id'	=> $item_id,
			   'parent_id'	=> $parent_id,
			   'value'		=> $tag,					
			);
			$db->insert('blog_items_tags', $data);
		}
		return TRUE;
	}
/*
 * delete a single blog post
 * should also delete blog post metadata: comments/tags
 */
	function delete($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;
		$db->delete(
			'blog_items',
			array('id' => $id, 'fk_site' => $this->site_id)
		);	
		$db->query("
			DELETE FROM blog_items_tags
			WHERE item_id = '$id'
			AND fk_site = '$this->site_id'
		");
		$db->query("
			DELETE FROM blog_items_comments
			WHERE item_id = '$id'
			AND fk_site = '$this->site_id'
		");
		die('Post deleted!'); #status
	}

/*
 * delete a single tag
 */
	function delete_tag($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;
		$db->delete('blog_items_tags', array('id' => "$id", 'fk_site' => "$this->site_id") );	
		die('Tag deleted!'); #status
	}	

/*
 * delete a single comment
 */
	function delete_comment($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;
		$db->delete('blog_items_comments', array('id' => "$id", 'fk_site' => $this->site_id) );	
		die('Comment deleted!'); #status
	}
	
/*
 * show settings view
 */	
	function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);
		if($_POST)
		{
			die('testing');
			$db = new Database;
			$data = array(
				'title'	=> $_POST['title'],
			);
			$db->update(
				'blogs',
				$data,
				"id='$tool_id' AND fk_site = '$this->site_id'"
			); 						
			die('Blog Settings Updated.'); #success
		}
		die( $this->_view_edit_settings('blog', $tool_id) );
	}

/*
 * logic executed after this blog tool is added to site.
 */
	function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}

/*
 * logic executed after this blog tool is deleted from site.
 */	
	function _tool_deleter($tool_id, $site_id)
	{
		$db = new Database;
		$db->query("
			DELETE FROM blog_items_comments
			WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
		");
		$db->query("
			DELETE FROM blog_items_tags
			WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
		");
		return TRUE;
	}
}
