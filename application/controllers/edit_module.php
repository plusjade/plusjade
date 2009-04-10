<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Edit_Module_Controller extends Controller {

/*
 *	Creates a template for edit Module controller tasks
 *	views are rendered for ajax mode - no page can be built
 *		
 * 	also provides useful automation functions
 */
 
	public function __construct()
	{
		parent::__construct();
		# Require Login
		if(!$this->client->logged_in()) url::redirect();	
		
		# Load Template
		$this->template = new View("ajax");	

		# Controller variables
		$this->site_data_dir = DOCROOT."data/$this->site_name";
		
		# View variables						
		$data = array(
			'theme_name'		=> $this->theme,
			'site_name'			=> $this->site_name,
			'js_path'			=> 'http://' . ROOTDOMAIN . '/js',
			'data_path'			=> 'http://' . ROOTDOMAIN . "/data/{$this->site_name}",
			'custom_include'	=> DOCROOT."data/{$this->site_name}/themes/{$this->theme}/",
		);	
		$this->template->set_global($data);
	}
	

##################################
# ---------- GRABS --------------#
##################################
# Grabs always return data/objects
	
/*
 * Returns single parent object 
 *
 */ 	
	function _grab_module_parent($module, $tool_id=NULL, $child_id=NULL)
	{	
		$table = $module.'s';
		$table_items = $module.'_items';
		$db = new Database;
		
		if (! empty($tool_id) )
			$query = "SELECT * FROM $table WHERE id = '$tool_id' AND fk_site = '$this->site_id'";
		else
		{
			$item = $db->query("SELECT parent_id FROM $table_items WHERE id = '$child_id' AND fk_site = '$this->site_id' ")->current();
			$query = "SELECT * FROM $table WHERE id = '$item->parent_id' AND fk_site = '$this->site_id' ";
		}
		
		$return = $db->query($query)->current();
		
		# Needs to return only one object for now
		return $return;
	}

/*
 * Returns single child object 
 *
 */ 
	function _grab_module_child($module, $child_id, $JOIN ='')
	{
		$db = new Database;
		$table_items = $module.'_items';
		
		$query = "SELECT * 
			FROM $table_items 
			$JOIN 
			WHERE id = '$child_id' 
			AND fk_site = '$this->site_id'";
		
		$return = $db->query($query);
		
		return $return->current();
	}

	
	

##################################
# ---------- SHOWS --------------#
##################################
# Show functions always return VIEWS

	/*
	 * SHOW the manage items view. Needs list of items belonging to module/page
	 * GRAB items from module table - renders edit panel based on findings 
	 * Validates ownership of module items to page/site/client.
	 * If no items found, show new_module builder view
	 * redirects if no validation
	 * 		@param (string)	$module		=	singular name of the Module to Grab
	 * 		@param (int)	$tool_id	= 	tool_id of the parent tool	
	 * 		@param (string)	$join		= 	add a join statement to the query
	 */
	
	function _show_manage_module_items( $module, $tool_id, $join = '')
	{
		$db = new Database;
		$table = $module.'s';		
	
		# Grab module parent
		$parent = $this->_grab_module_parent($module, $tool_id);
		
		# Grab items belonging to parent		
		$query = "SELECT * FROM {$module}_items $join WHERE parent_id = '{$parent->id}' AND fk_site = '{$this->site_id}' ORDER BY position";
		
		$items = $db->query($query);

		# If items exist, load edit view
		if($items->count() > 0)
		{
			$primary = new View("$module/edit/manage_$module");

			# Send parent object
			$primary->set($module, $parent);

			# Send items object
			$primary->items = $items;
		}
		else
		{
			# If the parent exists SHOW NEW module instance.
			if( is_object($parent) )
			{
				$primary = new View("$module/edit/new_$module");
				$primary->add = new View("$module/edit/new_item");			
				$primary->add->tool_id = $parent->id;
			}
			else
				url::redirect('get/admin/pages'); # if logged in - should not be gaming!
		}
				
		# Display the view
		$this->template->primary = $primary;	
		$this->template->render(true);			
	}	

	
/*
 * SHOW add a new item to a Tool
 * 		@PARAM $module	(STRING)	=	tool name to add to
 * 		@PARAM $tool_id	(INT)		=	tool_id of the tool parent	
 */
	function _show_add_single($module, $tool_id)
	{
		$primary = new View("$module/edit/new_item");
		$primary->tool_id = $tool_id;			
		$this->template->primary = $primary;
		
		return $this->template;
	}


	/*
	 * SHOW single view of a module item 
	 * Validates ownership of module items to page/site/client.
	 * if no items found = bad id
	 * redirects if no validation
	 * 		@param (string)	$module		=	singular name of the Module to Grab
	 * 		@param (int)	$item_id	= 	item id
	 * 		@param (string)	$query		= 	overwrite default simple query with custom one (useful for joins)
	 */
	
	function _show_edit_single( $module, $item_id, $JOIN = '' )
	{	
		$primary = new View("$module/edit/single_item");
		$table = $module.'s';
			
		# Grab single item
		$item = $this->_grab_module_child($module, $item_id, $JOIN);

		# If item exists & belongs to this site:
		if(! empty($item) )
		{
			$primary->item = $item;
			$this->template->primary = $primary;
			$this->template->render(true);			
		}
		else
		{
			echo 'Bad id';
		}		
	}

	
/*
 *	SHOW edit settings view
 *	
 */	
	public function _show_edit_settings($module, $tool_id)
	{
			$primary = new View("$module/edit/settings");
			$primary->page_id = '';	
			$primary->tool_id = $tool_id;
			
			# Grab module container
			$parent = $this->_grab_module_parent($module, $tool_id);
			$primary->set($module, $parent);		
						
			$this->template->primary = $primary;
			$this->template->render(TRUE);		
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

		echo 'Sort Order Saved!!<br>Updating...'; # status response	
	
	}

/*
 * $module = name of module
 * $id = id of item to delete
 *
 */
	function _delete_single_common($module, $id)
	{	
		tool_ui::validate_id($id);		
		$db = new Database;
		$table = $module.'_items';
		
		# Perform delete
		$db->delete( $table, array('id' => "$id", 'fk_site' => $this->site_id) );	
	}


	public function __call($method, $id)
    {
		echo 'non existent method';
		die();
	}
	
} # End Template_Controller