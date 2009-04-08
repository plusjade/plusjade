<?php

class Navigation_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{	
		tool_ui::validate_id($tool_id);	
		$primary	= new View('calendar/index');	
	}
  
}

/* -- end of application/controllers/showroom.php -- */