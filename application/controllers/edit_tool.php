<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Edit_Tool_Controller extends Controller {

/*
 *	Creates a template for edit Module controller tasks
 *	views are rendered for ajax mode
 *	provides useful automation functions		
 * 
 */
 
	public function __construct()
	{
		parent::__construct();
		if(! $this->client->logged_in()
			OR $this->client->get_user()->client_site_id != $this->site_id )
		{
			echo 'Please login';
			die();
		}
		
		# Controller variables
		$this->site_data_dir = DATAPATH . "$this->site_name";
	}
	
# ---------- GRABS
# Grabs always return data/objects
	
/*
 * Returns single parent object 
 *
 */ 	
	function _grab_tool_parent($toolname, $tool_id=NULL, $child_id=NULL)
	{	
		$table = $toolname.'s';
		$table_items = $toolname.'_items';
		$db = new Database;
		
		if (! empty($tool_id) )
			$query = "SELECT * FROM $table 
				WHERE id = '$tool_id'
				AND fk_site = '$this->site_id'
			";
		else
		{
			$item = $db->query("SELECT parent_id FROM $table_items 
				WHERE id = '$child_id' 
				AND fk_site = '$this->site_id'
			")->current();
			
			$query = "SELECT * FROM $table 
				WHERE id = '$item->parent_id' 
				AND fk_site = '$this->site_id'
			";
		}	
		$parent = $db->query($query)->current();
		
		if( is_object($parent) )
			return $parent;
		else
			return FALSE;
	}

/*
 * Returns single child object 
 *
 */ 
	function _grab_tool_child($toolname, $child_id, $JOIN ='')
	{
		$db = new Database;
		$table_items = $toolname.'_items';
		$query = "SELECT * 
			FROM $table_items 
			$JOIN 
			WHERE id = '$child_id' 
			AND fk_site = '$this->site_id'
		";
		$child = $db->query($query)->current();	
		
		if( is_object($child) )
			return $child;
		else
			return FALSE;
	}


# ---------- SHOWS --------------#
# Show functions always return VIEWS

	/*
	 * SHOW the manage items view. Needs list of items belonging to module/page
	 * GRAB items from module table - renders edit panel based on findings 
	 * Validates ownership of module items to page/site/client.
	 * If no items found, show new_module builder view
	 * redirects if no validation
	 * 		@param (string)	$toolname		=	singular name of the Module to Grab
	 * 		@param (int)	$tool_id	= 	tool_id of the parent tool	
	 * 		@param (string)	$join		= 	add a join statement to the query
	 */
	
	function _view_manage_tool_items($toolname, $tool_id, $join = '')
	{
		$db = new Database;
		$table = $toolname.'s';		

		$parent = $this->_grab_tool_parent($toolname, $tool_id);		
		$query = "SELECT * FROM {$toolname}_items 
			$join 
			WHERE parent_id = '$parent->id' 
			AND fk_site = '$this->site_id' 
			ORDER BY position
		";	
		$items = $db->query($query);

		if($items->count() > 0)
		{
			$primary = new View("$toolname/edit/manage_$toolname");
			$primary->set($toolname, $parent);
			$primary->items = $items;
		}
		else
		{
			if( is_object($parent) )
			{
				$primary = new View("$toolname/edit/new_item");			
				$primary->tool_id = $parent->id;
			}
			else
				die();
		}
		return $primary;		
	}	

	
/*
 * SHOW add a new item to a Tool
 * 		@PARAM $toolname (STRING)	=	tool name to add to
 * 		@PARAM $tool_id	(INT)		=	tool_id of the tool parent	
 */
	function _view_add_single($toolname, $tool_id)
	{
		$primary = new View("$toolname/edit/new_item");
		$primary->tool_id = $tool_id;			
		return $primary;
	}


	/*
	 * SHOW single view of a module item 
	 * Validates ownership of module items to page/site/client.
	 * 		@param (string)	$toolname		=	singular name of the Module to Grab
	 * 		@param (int)	$item_id	= 	item id
	 * 		@param (string)	$query		= 	overwrite default simple query with custom one (useful for joins)
	 */
	
	function _view_edit_single($toolname, $item_id, $JOIN = '' )
	{	
		$primary	= new View("$toolname/edit/single_item");
		$table		= $toolname.'s';
		$item		= $this->_grab_tool_child($toolname, $item_id, $JOIN);

		if( is_object($item) )
		{
			$primary->item = $item;
			return $primary;
		}
		else
			echo 'Bad id';
	}

	
/*
 *	SHOW edit settings view
 *	
 */	
	public function _view_edit_settings($toolname, $tool_id)
	{
		$primary = new View("$toolname/edit/settings");
		$primary->tool_id = $tool_id;	
		$parent = $this->_grab_tool_parent($toolname, $tool_id);
		
		if( is_object($parent) )
		{
			$primary->set($toolname, $parent);		
			return $primary;
		}
		else
			return FALSE;
	}
	

##################################
# ---------- Workers ------------#
##################################
/*
 * item array via GET
 * $table module table this updates
 *
 */
	function _save_sort_common($item_array, $table)
	{
		(array) $item_array;	
		$db = new Database;	
		
		foreach($item_array as $position => $id)
			$db->update($table, array('position' => "$position"), "id = '$id'"); 	
		
		return 'Sort Order Saved!!<br>Updating...'; # status response	
	}

/*
 * $toolname = name of module
 * $id = id of item to delete
 *
 */
	function _delete_single_common($toolname, $tool_id)
	{	
		tool_ui::validate_id($tool_id);		
		$db = new Database;
		$table = $toolname.'_items';
		$db->delete( $table, array('id' => "$tool_id", 'fk_site' => $this->site_id) );	
	}

	public function __call($method, $id)
    {
		echo 'non existent method';
		die();
	}
	
} # End Template_Controller