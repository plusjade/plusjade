<?php

class Edit_Navigation_Controller extends Edit_Tool_Controller {
/*
 * Edit a navigation menu
 *
 */
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * Manage Function display a sortable list of tool resources (items)
 */
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$primary	= new View('edit_navigation/manage');
		$db			= new Database;	
		$items		= $db->query("SELECT * FROM navigation_items 
			WHERE parent_id = '$tool_id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC
		");				
		$primary->tree = Tree::display_tree('navigation', $items, TRUE);
		$primary->tool_id = $tool_id;	
		echo $primary;
		die();
	}

/*
 * Add links(s)
 */ 
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		if($_POST)
		{
			die('99'); # sample data return of insert_id
			$db = new Database;

			# Get parent
			$parent	= $db->query("SELECT * FROM navigations 
				WHERE id = '$tool_id' 
				AND fk_site = '$this->site_id' ")->current();
			
			foreach($_POST['item'] as $key => $item)
			{			
				$data_string = ( empty($_POST['data'][$key]) ) ? '' : $_POST['data'][$key];

				$data = array(
					'parent_id'		=> $tool_id,
					'fk_site'		=> $this->site_id,
					'display_name'	=> $item,
					'type'			=> $_POST['type'][$key],
					'data'			=> $data_string,
					'local_parent'	=> $parent->root_id,
					'position'		=> '0'
				);	
				$db->insert('navigation_items', $data); 	
			}
			# Update left and right values
			Tree::rebuild_tree('navigation_items', $parent->root_id, '1');
			echo 'Links added'; #status message
		}
		elseif($_GET)
		{
			# GET must come from ajax request @ view(edit_navigation/manage)
			$local_parent = valid::id_key(@$_GET['local_parent']);
			
			$primary = new View('edit_navigation/new_item');
			$db = new Database;
			$pages = $db->query("
				SELECT page_name FROM pages 
				WHERE fk_site = '$this->site_id'
				ORDER BY page_name
			");			
			$primary->pages = $pages;
			$primary->tool_id = $tool_id;
			$primary->local_parent = $local_parent;				
			echo $primary;
		}
		die();		
	}

/*
 * Saves the nested positions of the menu links
 * Can also delete any links removed from the list.
 *
 */ 
	function save_sort($tool_id)
	{
		if($_POST)
		{
			valid::id_key($tool_id);
			echo Tree::save_tree('navigations', 'navigation_items', $tool_id, $_POST['output']);
		}
		die();
	}
/*
 * Edit single Item
 */
	public function edit($id=NULL)
	{
		valid::id_key($id);		
	
		if($_POST)
		{
			die('successful test');
		}
		else
		{
			$primary = new View('edit_navigation/edit_item');
			$db = new Database;
		
			$item = $db->query("
				SELECT * FROM navigation_items 
				WHERE id = '$id'
				AND fk_site = '$this->site_id'
			")->current();
			
			if(! is_object($item) )
				die('element does not exist');
				
				
			$pages = $db->query("
				SELECT page_name FROM pages 
				WHERE fk_site = '$this->site_id'
				ORDER BY page_name
			");
			
			$primary->item = $item;
			$primary->pages = $pages;
			
			die($primary);
		}
	}
	
	public function delete($id=NULL)
	{

	}

	public function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);		
		$db = new Database;

		if($_POST)
		{
			$data = array(
				'title'	=> $_POST['title'],
			);		
			$db->update('navigations', $data, "id = '$tool_id' AND fk_site = '$this->site_id'");
			echo 'Settings Saved!<br>Updating...';	
		}
		else
		{
			$primary = new View("edit_navigation/settings");
			$parent = $db->query("SELECT * FROM navigations WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();			
			$primary->parent = $parent;
			echo $primary;
		}
		die();				
	}
	
}