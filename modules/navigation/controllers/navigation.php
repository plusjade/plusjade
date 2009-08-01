<?php

class Navigation_Controller extends Public_Tool_Controller {

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
		
		$navigation = ORM::factory('navigation')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $navigation->loaded)
			return $this->public_template('this navigation id not found.', 'navigation', $tool_id, '');
	
		# There will always be a root_holder so no items is actually =1
		if('1' == $navigation->navigation_items->count())
			return $this->public_template('(no items)', 'navigation', $tool_id);
		
		$primary = new View('public_navigation/index');	
		$primary->navigation = $navigation;
		# public node_generation function is contained in the tree class...
		$primary->tree = Tree::display_tree('navigation', $navigation->navigation_items);
		return $this->public_template($primary, 'navigation', $tool_id, $navigation->attributes);
	}
 
/*
 * Need to add a root child to items list for every other
 * child to belong to
 * Add root child id to parent for easier access.
 */		 
	public static function _tool_adder($tool_id, $site_id)
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
	
}  /* -- end -- */

