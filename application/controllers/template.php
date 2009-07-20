<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Template_Controller extends Controller {

	public $auto_render = TRUE;

	public function __construct()
	{
		parent::__construct();	
		#$this->profiler = new Profiler;		
		$this->template = new View("shell");	

		
		# Global CSS			
		if(! $this->client->can_edit($this->site_id) )
			$this->template->linkCSS("/_data/$this->site_name/themes/$this->theme/css/global.css?v=23094823-");
		
		/*
		$theme_js_path = DOCROOT . "_assets/themes/$this->theme/js/stock.js"
		if( file_exists($theme_js_path) )
			include($theme_js_path);	
		*/
		
		# Render Template immediately after controller method	
		if ($this->auto_render == TRUE)
			Event::add('system.post_controller', array($this, '_render'));
	}
	
/*
 * Load Assets for Admin edit mode
 */ 
	function _load_admin($page_id, $page_name)
	{	
		if($this->client->can_edit($this->site_id))
		{
			# inline modular global css
			$css_path = $this->assets->themes_dir("$this->theme/css/global.css");

			$css = (file_exists($css_path)) ?
				file_get_contents($css_path) : '/* global.css file does not exist. Please create it.*/';

			$this->template->inline_global_css = "<style type=\"text/css\" id=\"global-style\">\n$css\n</style>\n";
	
			$this->template->linkCSS('get/css/admin', url::site() );
			$this->template->admin_linkJS('get/js/admin?v=1.1');
			$this->template->admin_linkJS('get/js/tools');

			# determine if tool is protected so we can omit scope link
			$db = new Database;
			$protected_tools = $db->query("
				SELECT * FROM tools_list
				WHERE protected = 'yes'
			");	
			$protected_array = array();
			foreach($protected_tools as $tool)
				$protected_array[] = $tool->id;
				
			$this->template->admin_panel =
				view::factory(
					'admin/admin_panel',
					array(
						'protected_array'	=> $protected_array,
						'page_id'			=> $page_id,
						'page_name'			=> $page_name,
						'global_css_path'	=> "/_data/$this->site_name/themes/$this->theme/css/global.css?v=23094823-"
					)
				);
			return TRUE;
		}
		return FALSE;
	}

/*
 * Build the output and send to view (shell.php)
 * Output is composed of tools data sent from build_page.php or other admin data
 * from auth/utada.php that needs to be wrapped in a theme-based shell.
 */	
	public function build_output($containers_array, $template=NULL)
	{
		$banner		= View::factory('_global/banner');
		$menu		= View::factory('_global/menu');
		$template 	= ((NULL == $template)) ? 'master' : $template;
		$path		= $this->assets->themes_dir("$this->theme/templates");
		
		ob_start();	
		if (file_exists("$path/$template.html"))
			readfile("$path/$template.html");	
		else
		{
			if(!file_exists("$path/master.html"))
			{
				$rootsite = ROOTDOMAIN;
				die("Missing 'master.html' for theme: $this->theme : <a href=\"http://$rootsite/get/auth\">enter safe-mode</a>");
			}
			readfile("$path/master.html");
			$this->template->error = "<h1 class=\"aligncenter\">Invalid '$template.html' for theme: $this->theme, using master.html</h1>";
		}

		function get_string_between($string, $start, $end)
		{
			$string = " ".$string;
			$ini = strpos($string, $start);
			if ($ini == 0) return "";
			$ini += strlen($start);   
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}
		$master = get_string_between(ob_get_clean(), '<body>', '</body>');
		
		$keys = array(
			'%BANNER%',
			'%MENU%',
		);
		$replacements = array(
			$banner,
			$menu,
		);
		# this is necessary for pages that do not get built via build_page.php
		# but still need to load into the given theme template.
		if(! is_array($containers_array) )
			$containers_array = array(
				' ', $containers_array,' ', ' ', ' ', ' '
			);
		
		# 5 containers
		foreach($containers_array as $key => $content)
		{
			array_push($keys, "%CONTAINER_$key%");
			array_push($replacements, $content);
		}
		
		# Add login to +Jade
		if(ROOTACCOUNT == $this->site_name )
		{
			array_push($keys, "%LOGIN%");
			array_push($replacements, View::factory("_global/login"));
		}
		
		# TODO: Look into compression for this ...
		$this->template->output = str_replace($keys, $replacements , $master);

		# build the end_body contents
		# It is bad to open 2 buffers, fix this
		$tracker_path = DATAPATH . "$this->site_name/tracker.html";	
		#ob_start();
		if ( file_exists("$tracker_path") )
			readfile("$tracker_path");	
		$this->template->end_body = ob_get_clean();	
	}
	
	
	# Render loaded template when class is destroyed
	public function _render()
	{
		if ($this->auto_render == TRUE)
			$this->template->render(TRUE);
	}
	
} # End Template_Controller