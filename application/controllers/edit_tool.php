<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Edit_Tool_Controller extends Controller {

/*
 * All edit_tool controllers extend this class.
 */
 
	public function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login to edit this tool');
	}

} # End edit_tool_Controller