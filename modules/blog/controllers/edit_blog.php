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

	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$db = new Database;
		$primary = new View('edit_blog/manage');
		
		# Show drafts
		$items = $db->query("SELECT blog_items.*, 
			DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on 
			FROM blog_items
			WHERE blog_items.parent_id = '$tool_id'
			AND blog_items.fk_site = '$this->site_id'					
			AND status = 'draft'
			GROUP BY blog_items.id 
			ORDER BY created DESC
		");
		$primary->items = $items;
		echo $primary;
		die();	
	}
	
	function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$db = new Database;
		
		if($_POST)
		{
			$data = array(
				'fk_site'	=> $this->site_id,
				'parent_id'	=> $tool_id,
				'url'		=> $_POST['url'],
				'status'	=> $_POST['status'],
				'title'		=> $_POST['title'],
				'body'		=> $_POST['body'],
				'created'	=> date("Y-m-d H:m:s")
			);

			$db->insert('blog_items', $data); 
			echo 'Post added'; #status
		}
		else
		{
			echo $this->_view_add_single('blog', $tool_id);
		}
	}


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
			$db->update('blog_items', $data, "id = '$id' AND fk_site='$this->site_id'");
			
			$tags = trim($_POST['tags']);
			if (! empty($tags) )
			{
				$tags = explode(',', $_POST['tags']);
			
				foreach($tags as $tag)
				{
					$tag = trim($tag);
					$tag = preg_replace("(/W)", '_', $tag);
					$data = array(
					   'fk_site'	=> $this->site_id,
					   'item_id'	=> $id,
					   'parent_id'	=> $_POST['parent_id'],
					   'value'		=> $tag,					
					);
					$db->insert('blog_items_tags', $data);
				}
			}			
			echo 'Post Saved!<br>Updating...'; #status				
		}
		else
		{
			$primary = new View("edit_blog/single_item");
			$item = $db->query("
				SELECT blog_items.*, DATE_FORMAT(created, '%M %e, %Y, %l:%i%p') as created_on, 
				GROUP_CONCAT(DISTINCT blog_items_tags.value, CONCAT('_',blog_items_tags.id) ORDER BY blog_items_tags.value  separator ',') as tag_string
				FROM blog_items 
				LEFT JOIN blog_items_tags ON blog_items.id = blog_items_tags.item_id
				WHERE blog_items.id = '$id'
				AND blog_items.fk_site = '$this->site_id'
				AND blog_items.status = 'publish'
			")->current();
			$primary->item = $item;
		
			echo $primary;
		}
		die();
	}

	function delete($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$this->_delete_single_common('blog', $tool_id);
		echo 'Post deleted!'; #status
		die();
	}

	function delete_tag($id=NULL)
	{
		$db = new Database;
		valid::id_key($id);
		$db->delete('blog_items_tags', array('id' => "$id", 'fk_site' => "$this->site_id") );	
		echo 'Tag deleted!'; #status
		die();
	}	

	function delete_comment($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;
		$db->delete('blog_items_comments', array('id' => "$id", 'fk_site' => $this->site_id) );	
		echo 'Comment deleted!'; #status
		die();
	}
	
	
	function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);
		if($_POST)
		{
			$db = new Database;
			$data = array(
				'title'	=> $_POST['title'],
			);
			
			$db->update('blogs', $data, "id='$tool_id' AND fk_site = '$this->site_id'"); 						
			
			echo 'Settings Updated!<br>Updating...'; #success
		}
		else
		{
			echo $this->_view_edit_settings('blog', $tool_id);
		}
		die();
	}
	
}

/* -- end of application/controllers/faq.php -- */