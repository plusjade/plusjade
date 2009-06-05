<?php

class Navigation_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	/*
	 * Displays a nestable navigation menu
	 *
	 */	 
	function _index($tool_id)
	{	
		valid::id_key($tool_id);	
		
		$primary	= new View('public_navigation/index');	
		$db			= new Database;
		
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
		
		# TODO: change this to only logged in view
		if('0' == $items->count() )
			return '(no items)';
			
			
		$primary->parent = $parent;
		
		# node_generation function is contained in the tree class...
		$primary->tree = Tree::display_tree('navigation', $items);
		
		# Javascript
		if( $this->client->logged_in() )
			$primary->add_root_js_files('simple_tree/jquery.simple.tree.js');
		
		return $primary;
	}
  
}

/* -- end of application/controllers/showroom.php -- */