<?php defined('SYSPATH') or die('No direct script access.');

/**
 * sitewide help controller outputs help views.
 */
 
class Help_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
	}

/**
 * output the basic help view.
 */	
	function index()
	{
		$system_tools = ORM::factory('system_tool')->where('enabled', 'yes')->find_all();
		
		$view = new View('help/index');
		#$view->page = View::factory('help/intro')->render();
		$view->system_tools = $system_tools;
		$view->selected = (empty($_GET['page'])) ? 'page' : $_GET['page'];
		die($view);
	}
	
}  /* End of help.php */