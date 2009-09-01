<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 
 * control the main home page stuff for plusjade only.
 */
 
class Home_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
		if(ROOTACCOUNT !== $this->site_name)
			die('return 404 not found');
	}

	
/*
 * View for Create a new website.
 */	 
	public static function _index()
	{	
		if($_POST)
		{
			# beta code
			if('DOTHEDEW' != $_POST['beta'])
				return self::display_create('The beta code is not valid', $_POST);

			$site_name = 'beta-'.trim($_POST['site_name']);
			$theme = (empty($_POST['theme'])) ? 'base' : $_POST['theme'];
			
			# will redirect on success, else show error.
			$show_error = Auth_Controller::_create_website($site_name, $theme);
			return self::display_create($show_error, $_POST);
		}
		else
			return self::display_create();
	}

/*
 * Internal function used to setup the create view.
 */	
	private static function display_create($errors=NULL, $values=NULL)
	{
		if(empty($values))
			$values = array(
				'site_name'	=> strtolower(text::random('alpha', 5)),
				'beta'		=> '',
				'theme'		=> ''
			);
		$view = new View('plusjade_home');
		$view->errors = $errors;
		$view->values = $values;
		$view->themes = ORM::factory('theme')->where('enabled', 'yes')->find_all();
		$view->request_js_files('easing/jquery.easing.1.3.js');										
		$view->request_js_files('cycle_lite/jquery.cycle.all.min.js');
		return $view;
	}
	
} # End home Controller