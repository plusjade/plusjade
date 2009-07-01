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
		$theme_js_path = APPPATH."/views/$this->theme/js/js.php";
		if( file_exists($theme_js_path) )
			include($theme_js_path);	
		*/
		
		# Render Template immediately after controller method	
		if ($this->auto_render == TRUE)
			Event::add('system.post_controller', array($this, '_render'));
	}
	
/*
 * Load Assets for Admin edit mode
 *
 */ 
	function _load_admin($page_id, $page_name)
	{	
		if( $this->client->can_edit($this->site_id) )
		{
			#inline modular global css
			$css_path = Assets::data_path_theme('css/global.css');
			$css = '';
			if(file_exists($css_path))
			{
				$theme_url = Assets::url_path_theme('images');
				$css = str_replace('../images', $theme_url , file_get_contents($css_path));	
			}
			else
				die("this file does not exist: $css_path"); # for development only
			
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
					)
				);
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * Build the output and send to view
	 */	
	public function build_output($containers_array, $template=NULL)
	{
		$header		= View::factory('_global/header');
		$menu		= View::factory('_global/menu');
		$template 	= ((NULL == $template)) ? 'master' : $template;
		$path		= Assets::data_path_theme("templates/$template.html");
		
		ob_start();	
		if (file_exists($path))
			readfile($path);	
		else
			die("Could not find '$template.html' for theme: $this->theme");
		

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
			'%HEADER%',
			'%MENU%',
		);
		$replacements = array(
			$header,
			$menu,
		);
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