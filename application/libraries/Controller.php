<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohana Controller class. The controller class must be extended to work
 * properly, so this class is defined as abstract.
 *
 * $Id: Controller.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http:#kohanaphp.com/license.html
 */
abstract class Controller_Core {

	# Allow all controllers to run in production by default
	const ALLOW_PRODUCTION = TRUE;
	
/**
 * Loads URI, and Input into this controller.
 *
 * @return  void
 */
	public function __construct()
	{
		$session	 = Session::instance();
		$site_config = yaml::parse($_SESSION['site_name'], 'site_config');

		foreach($site_config as $key => $value)
			$this->$key = $value ;
	
		// --- Setup our required library instances ---
		// --------------------------------------------
		
		# Auth Instance for editing site capability
		$this->client = new Auth($this->claimed);	
		
		# Account Instance for user account tool.
		$this->account_user = new Account;
		
		# assets instance to fetch datapath urls.
		$this->assets = Assets::instance($this->site_name, $this->theme);
		
		# URI should always be available
		$this->uri = URI::instance();

		# Input should always be available
		$this->input = Input::instance();
		
		if (Kohana::$instance == NULL)
		{
			# Set the instance to the first controller loaded
			Kohana::$instance = $this;
		}
	}


/**
 * Includes a View within the controller scope.
 *
 * @param   string  view filename
 * @param   array   array of view variables
 * @return  string
 */
	public function _kohana_load_view($kohana_view_filename, $kohana_input_data)
	{
		if ($kohana_view_filename == '')
			return;

		# Buffering on
		ob_start();

		# Import the view variables to local namespace
		extract($kohana_input_data, EXTR_SKIP);

		try
		{
			# Views are straight HTML pages with embedded PHP, so importing them
			# this way insures that $this can be accessed as if the user was in
			# the controller, which gives the easiest access to libraries in views
			include $kohana_view_filename;
		}
		catch (Exception $e)
		{
			# Display the exception using its internal __toString method
			echo $e;
		}

		# Fetch the output and close the buffer
		return ob_get_clean();
	}
	
/**
 * Handles methods that do not exist.
 *
 * @param   string  method name
 * @param   array   arguments
 * @return  void
 */
	public function __call($method, $args)
	{
		kohana::log('error', "-- $method does not exist. Called by: {$_SERVER['REQUEST_URI']}");
		header('HTTP', true, 500);
		die('bad controller method call. This is an internal error and has been reported.');
	}
	
} # End Controller Class