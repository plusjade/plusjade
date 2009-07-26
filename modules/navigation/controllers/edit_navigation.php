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
		
		$navigation_items = ORM::factory('navigation_item')
			->where(array(
				'fk_site'		=> $this->site_id,
				'navigation_id'	=> $tool_id,
			))
			->find_all();	
		if(0 == $navigation_items->count())
			die('Error: this navigation has no root node.');


		$pages = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->find_all();	
			
		function render_node_navigation($item)
		{
			return ' <li rel="'. $item->id .'" id="item_' . $item->id . '"><span>' . $item->display_name . '</span> <small style="display:none">Type: '. $item->type .' <br> Data: '. $item->data .'</small>'; 
		}

		$primary = new View('edit_navigation/manage');
		$primary->tree = Tree::display_tree('navigation', $navigation_items, NULL, TRUE);
		$primary->tool_id = $tool_id;
		$primary->pages = $pages;
		die($primary);
	}

/*
 * Add navigation items (links)  to a navigation 
 */ 
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		if($_POST)
		{
			$navigation = ORM::factory('navigation')
				->where('fk_site', $this->site_id)
				->find($tool_id);	
			if(FALSE === $navigation->loaded)			
				die('adding items to invalid navigation list.');
			
			$_POST['data'] = (empty($_POST['data'])) ? '' : $_POST['data'];			
			# if for any reason local_parent is null, just add to root.
			$_POST['local_parent'] = (empty($_POST['local_parent'])) ?
				$navigation->root_id : $_POST['local_parent'];

			$new_item = ORM::factory('navigation_item');
			$new_item->navigation_id	= $tool_id;
			$new_item->fk_site			= $this->site_id;
			$new_item->display_name		= $_POST['item'];
			$new_item->type				= $_POST['type'];
			$new_item->data				= $_POST['data'];
			$new_item->local_parent		= $_POST['local_parent'];
			$new_item->save();
	
			# Update left and right values
			Tree::rebuild_tree('navigation_item', $navigation->root_id, $this->site_id, '1');
			
			die("$new_item->id"); # output to javascript
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
			echo Tree::save_tree('navigation', 'navigation_item', $tool_id, $this->site_id, $_POST['output']);
		}
		die();
	}
	
/*
 * Edit single navigation Item
 */
	public function edit($id=NULL)
	{
		valid::id_key($id);		

		$navigation_item = ORM::factory('navigation_item')
			->where('fk_site', $this->site_id)
			->find($id);	
		if(FALSE === $navigation_item->loaded)
			die('invalid navigation item id');
		
		if($_POST)
		{
			$_POST['data'] = (empty($_POST['data'])) ? '' : $_POST['data'];
			
			$navigation_item->display_name = $_POST['item'];
			$navigation_item->type = $_POST['type'];
			$navigation_item->data = $_POST['data'];
			$navigation_item->save();
			die('Navigation item updated.');
		}
		
		$pages = ORM::factory('page')
			->where('fk_site', $this->site_id)
			->find_all();
		
		$primary = new View('edit_navigation/edit_item');
		$primary->item = $navigation_item;
		$primary->pages = $pages;
		die($primary);
	}

/*
 * configure navigation settings
 */ 
	public function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);		

		$navigation = ORM::factory('navigation')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $navigation->loaded)
			die('invalid navigation id');

		if($_POST)
		{
			$navigation->title = $_POST['title'];
			$navigation->save();
			die('Navigation Settings Saved');	
		}

		$primary = new View("edit_navigation/settings");
		$primary->navigation = $navigation;
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
		# this can all be done in the overloaded save function for
		# navigations model - look into it.
		
		$new_item = ORM::factory('navigation_item');
		$new_item->navigation_id	= $tool_id;
		$new_item->fk_site			= $site_id;
		$new_item->display_name		= 'ROOT';
		$new_item->type				= 'none';
		$new_item->data				= 0;
		$new_item->local_parent		= 0;
		$new_item->save();
			
		$navigation = ORM::factory('navigation')
			->where('fk_site', $site_id)
			->find($tool_id);
		
		$navigation->root_id = $new_item->id;
		$navigation->save();
		
		return 'manage';
	}
	
	static function _tool_deleter($tool_id, $site_id)
	{
		ORM::factory('navigation_item')
			->where(array(
				'fk_site'	=> $site_id,
				'navigation_id'	=> $tool_id,
				))
			->delete_all();	

		return TRUE;
	}
}