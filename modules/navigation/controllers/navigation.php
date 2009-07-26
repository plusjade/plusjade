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
  
}  /* -- end -- */

