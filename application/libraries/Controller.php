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
		$session	= Session::instance();
		$site_name	= $_SESSION['site_name'];
		
		$site_config = file_get_contents(DATAPATH . "$site_name/protected/site_config.yaml");
		$site_config = explode(',',$site_config);
		$keys = array();
		foreach($site_config as $string)
		{
			$value	= strstr($string ,':');
			$keys[]	= ltrim($value, ':');
		}
		$this->site_id 		= $keys['0'];
		$this->site_name 	= $keys['1'];
		$this->theme 		= $keys['2'];
		$this->banner 		= $keys['3'];
		
		
		# Auth Instance
		$this->client = new Auth;	
		
		if (Kohana::$instance == NULL)
		{
			# Set the instance to the first controller loaded
			Kohana::$instance = $this;
		}

		# URI should always be available
		$this->uri = URI::instance();

		# Input should always be available
		$this->input = Input::instance();
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
	
	
	function public_template($primary, $toolname, $tool_id)
	{
		$template = new View('public_tool_wrapper');		
		$template->primary = $primary;
		$template->toolname = $toolname;
		$template->tool_id = $tool_id;
		$template->readyJS = '';
		$template->custom_css = '';
		
		# add admin mode stuff to each tool output ( _index() in particular )
		/*
		  Each initial tool view is called view <toolname>::_index()
		  in public view the index displays only html
		  in admin view each tool_output has to be 100% self_contained.
			so we inject appropriate CSS, html, and javascript =D
		 */
		if( $this->client->logged_in() )
		{
			# Custom CSS
			$css_file = DATAPATH . "$this->site_name/tools_css/$toolname/$tool_id.css";		
			if( file_exists($css_file) )
			{
				$contents = file_get_contents($css_file);							
				$template->custom_css = "
					<style type=\"text/css\">
						$contents
					</style>
				";
			}			
			
			# Javascripts
			# grab the index javascript and insert it inline.
			$js_file = MODPATH . "$toolname/views/public_$toolname/js/index.js";
			
			if( file_exists($js_file) )
			{
				$contents = file_get_contents($js_file);			
				$contents = str_replace('%VAR%', $tool_id , $contents);
				
				$template->readyJS = "
					<script type=\"text/javascript\">
						$(document).ready(function(){	
							$contents
						});
					</script>
				";
			}
		}
		else
			$template->readyJS($toolname, 'index', $tool_id);
			
		return $template;
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
		# Default to showing a 404 page
		#Event::run('system.404');
		die('root controller method not found');
	}
	
} # End Controller Class