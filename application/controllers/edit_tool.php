<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * All edit_tool controllers extend this class.
 * used to factor common functionality 
 * and provide an interface and overloading access point.
 */
 
abstract class Edit_Tool_Controller extends Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login to edit this tool');
	}

	
/*
 * callback function when deleting a tool.
 * useful for cleaning up assets generated with a tool.
 
 */	
	public static function _tool_deleter($parent_id, $site_id)
	{
		# delete items_meta (items)
		return TRUE;	
	}
	
} # End edit_tool_Controller