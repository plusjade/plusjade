<?php

class Navigation_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	/*
	 * Displays a nestable navigation element menu
	 */	 
	function _index($tool_id)
	{
		valid::id_key($tool_id);	
		$db = new Database;
		
		$parent	= $db->query("
			SELECT * FROM navigations 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();
			
		$items	= $db->query("
			SELECT * FROM navigation_items 
			WHERE parent_id = '$parent->id' 
			AND fk_site = '$this->site_id' 
			ORDER BY lft ASC
		");		
		
		# There will always be a root_holder so no items is actually =1
		if('1' == $items->count())
			return $this->public_template('(no items)', 'navigation', $tool_id);
		
		$primary = new View('public_navigation/index');	
		$primary->parent = $parent;
		
		# public node_generation function is contained in the tree class...
		$primary->tree = Tree::display_tree('navigation', $items);
	
		return $this->public_template($primary, 'navigation', $tool_id);
	}
  
}

/* -- end of application/controllers/showroom.php -- */