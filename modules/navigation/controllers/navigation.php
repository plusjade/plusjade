<?php defined('SYSPATH') OR die('No direct access allowed.');


class Navigation_Controller extends Public_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

/*
 * Displays a nestable navigation element menu
 * now expects the parent table object.
 */	 
	public function _index($navigation)
	{
		# There will always be a root_holder so no items is actually =1
		if('1' == $navigation->navigation_items->count())
			return $this->public_template('(no items)', 'navigation', $navigation);
		
		$primary = new View('public_navigation/lists/stock');	
		$primary->navigation = $navigation;
		# public node_generation function is contained in the tree class...
		$primary->tree = Tree::display_tree('navigation', $navigation->navigation_items);
		return $this->public_template($primary, 'navigation', $navigation);
	}
 
 
/*
 * Need to add a root child to items list for every other
 * child to belong to
 * Add root child id to parent for easier access.
 */		 
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
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


		
		if($sample)
		{
			$new_item->clear();		
			$new_item->navigation_id	= $tool_id;
			$new_item->fk_site			= $site_id;
			$new_item->display_name		= 'Sample list item';
			$new_item->type				= 'none';
			$new_item->data				= '';
			$new_item->local_parent		= $navigation->root_id;
			$new_item->save();

			$new_item->clear();		
			$new_item->navigation_id	= $tool_id;
			$new_item->fk_site			= $site_id;
			$new_item->display_name		= 'Link to Home';
			$new_item->type				= 'page';
			$new_item->data				= 'home';
			$new_item->local_parent		= $navigation->root_id;
			$new_item->save();

			$new_item->clear();		
			$new_item->navigation_id	= $tool_id;
			$new_item->fk_site			= $site_id;
			$new_item->display_name		= 'External Google Link';
			$new_item->type				= 'url';
			$new_item->data				= 'google.com';
			$new_item->local_parent		= $navigation->root_id;
			$new_item->save();


			
			# Update left and right values
			Tree::rebuild_tree('navigation_item', $navigation->root_id, $site_id, '1');
			
		}	
		return 'manage';
	}
	
}  /* -- end -- */

