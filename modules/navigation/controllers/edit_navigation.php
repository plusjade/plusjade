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
		$items		= $db->query("
			SELECT * FROM navigation_items 
			WHERE parent_id = '$tool_id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC
		");

		function render_node_navigation($item)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span>' . $item->display_name . '</span> <small style="display:none">Type: '. $item->type .' <br> Data: '. $item->data .'</small>'; 
		}
		
		$primary->tree = Tree::display_tree('navigation', $items, NULL, TRUE);
		$primary->tool_id = $tool_id;
		
		# get pages 
		$pages = $db->query("
			SELECT page_name FROM pages 
			WHERE fk_site = '$this->site_id'
			ORDER BY page_name
		");			
		$primary->pages = $pages;
			
		die($primary);
	}

/*
 * Add links(s)
 */ 
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		if($_POST)
		{
			$db = new Database;
			# Get parent
			$parent	= $db->query("
				SELECT * FROM navigations 
				WHERE id = '$tool_id' 
				AND fk_site = '$this->site_id'
			")->current();	
			if(! is_object($parent) )
				die('does not exist');
				
			$_POST['data'] = (empty($_POST['data'])) ? '' : $_POST['data'];
			
			# if for any reason local_parent is null, just add to root.
			$_POST['local_parent'] = (empty($_POST['local_parent'])) ?
				$parent->root_id : $_POST['local_parent'];

			$data = array(
				'parent_id'		=> $tool_id,
				'fk_site'		=> $this->site_id,
				'display_name'	=> $_POST['item'],
				'type'			=> $_POST['type'],
				'data'			=> $_POST['data'],
				'local_parent'	=> $_POST['local_parent'],
			);	
			$insert_id = $db->insert('navigation_items', $data)->insert_id(); 	
			
			# Update left and right values
			Tree::rebuild_tree('navigation_items', $parent->root_id, '1');
			
			die("$insert_id");#output to javascript
		}
		die();
	}

/*
 * Saves the nested positions of the menu links
 * Can also delete any links removed from the list.
 *
 */ 
	function save_tree($tool_id=NULL)
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
		$db = new Database;
		if($_POST)
		{
			$data = array(
				'display_name'	=> $_POST['item'],
				'type'			=> $_POST['type'],
				'data'			=> @$_POST['data'],
			);	
			$db->update(
				'navigation_items',
				$data,
				array('id' => $id, 'fk_site' => $this->site_id)
			); 	
			die('Changes Saved!');
		}
		$primary = new View('edit_navigation/edit_item');
	
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
	
	public function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);		
		$db = new Database;

		if($_POST)
		{	
			$db->update(
				'navigations',
				array('title' => $_POST['title']),
				"id = '$tool_id' AND fk_site = '$this->site_id'"
			);
			die('Navigation Settings Saved');	
		}

		$primary = new View("edit_navigation/settings");
		$parent = $db->query("
			SELECT * FROM navigations 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();		
		$primary->tool = $parent;
		$primary->js_rel_command = "update-navigation-$tool_id";
		die($primary);
			
	}


/*
 * Need to add a root child to items list for every other
 * child to belong to
 * Add root child id to parent for easier access.
 */		 
	static function _tool_adder($tool_id, $site_id)
	{
		$db = new Database;
		$data = array(
			'parent_id'		=> $tool_id,
			'fk_site'		=> $site_id,
			'display_name'	=> 'ROOT',
			'type'			=> 'none',
			'local_parent'	=> '0',
			'position'		=> '0'
		);	
		$root_insert_id = $db->insert('navigation_items', $data)->insert_id(); 	
		
		$db->update('navigations', 
			array( 'root_id' => $root_insert_id ), 
			array( 'id' => $tool_id, 'fk_site' => $site_id ) 
		);
		
		return 'manage';
	}
	
	static function _tool_deleter()
	{
		return false;
	}
}