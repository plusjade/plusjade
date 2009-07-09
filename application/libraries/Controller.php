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
		$session			= Session::instance();
		$site_config		= yaml::parse($_SESSION['site_name'], 'site_config');
		$this->site_id 		= $site_config['site_id'];
		$this->site_name 	= $site_config['site_name'];
		$this->theme 		= $site_config['theme'];
		$this->banner 		= @$site_config['banner']; # banner can be empty
		$this->homepage 	= $site_config['homepage'];
		
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
	
/*
  Each initial tool view is called view <toolname>::_index()
  in public view the index displays only html
  in admin view each tool_output has to be 100% self_contained.
	so we inject appropriate CSS, html, and javascript =D
	
	## rename to "tool_view_template"
 */	
	function public_template($primary, $toolname, $tool_id, $attributes='')
	{
		$template				= new View('public_tool_wrapper');		
		$template->primary		= $primary;
		$template->toolname		= $toolname;
		$template->tool_id		= $tool_id;
		$template->attributes	= $attributes;
		$template->readyJS		= '';
		$template->custom_css	= '';
		
		if($this->client->can_edit($this->site_id))
		{
			# Get CSS
			$custom_css	= Assets::themes_dir("$this->theme/tools/$toolname/css/$tool_id.css");
			$contents	= (file_exists($custom_css)) ? file_get_contents($custom_css) : '';
			$template->custom_css = "
				<style type=\"text/css\" id=\"$toolname-$tool_id-style\">
					$contents
				</style>
			";			
			
			# Get Javascripts
			# grab the index javascript and insert it inline.
			$js_file = MODPATH . "$toolname/views/public_$toolname/js/index.js";
			if(file_exists($js_file))
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
		{
			/* # public view:
			 *	css is handled via /get/css/tools/page_id link
			 *	js is handled in the same way @ view library
			 */
			$template->readyJS($toolname, 'index', $tool_id);
		}	
		
		return $template;
	}

/*
 * protected pages must maintain their page_name path
 * especially in cases of ajax requests or when on homepage
 # quick hack, optimize later...
 # we can probably do this using pages_config.yaml
 */
	public function get_page_name($page_name, $toolname, $tool_id)
	{
		if(! empty($page_name) )
			if('get' == $page_name)
				return yaml::does_value_exist($this->site_name, 'pages_config', "$toolname-$tool_id");
			else
				return $page_name;
		
		return $this->homepage;
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