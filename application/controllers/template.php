<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Template_Controller extends Controller {

	public $auto_render = TRUE;

	public function __construct()
	{
		parent::__construct();
		
		#$this->profiler = new Profiler;	
		
		# Load Template
		$this->template = new View("shell");	
		
		# View variables						
		$data = array(
			'theme_name'		=> $this->theme,
			'site_name'			=> $this->site_name,
			'js_path'			=> 'http://' . ROOTDOMAIN .'/js',
			'data_path'			=> 'http://' . ROOTDOMAIN . "/data/$this->site_name",
			'custom_include'	=> DOCROOT."data/$this->site_name/themes/$this->theme",
		);	
		$this->template->set_global($data);
		
		# Global CSS			
		$this->template->linkCSS("css/global.php?u=$this->site_name&t=$this->theme", '/');
				
		# Global Javascript (Theme specific)	
		$this->template->linkJS('jquery_latest.js');
		
		$theme_js_path = APPPATH."/views/{$this->theme}/js/js.php";
		if( file_exists($theme_js_path) )
			include($theme_js_path);	
			
		
		# Render Template immediately after controller method	
		if ($this->auto_render == TRUE)
			Event::add('system.post_controller', array($this, '_render'));
	}
	
/*
 * Load Assets for Admin edit mode
 *
 */ 
	function _load_admin()
	{	
		if( $this->client->logged_in() )
		{	
			if($this->client->get_user()->client_site_id == $this->site_id)
			{
				# Top Admin panel
				$this->template->admin_panel = TRUE;				
				
				# CSS			
				$this->template->linkCSS('css/admin_global.css');
				$this->template->linkCSS('css/smoothness.css');
					
				# Javascript
				$js_files = array(
					'facebox/public_multi.js',		# Facebox 
					'ui/ui_latest_lite.js',			# Jquery UI
					'ajax_form/ajax_form.js',		# Ajax Form
					'multi_form/MultiFile.pack.js',
					'jw/jwysiwyg.js',				# JWysiwyg
					'admin/init.js'					# Document Ready stuff
				);
				$this->template->add_root_js_files($js_files);
				#$embed_js = ' ';				
				#$this->template->global_readyJS($embed_js);	
			}
			return true;
		}	
		return false;
	}
	
	
	# Render loaded template when class is destroyed
	public function _render()
	{
		if ($this->auto_render == TRUE)
			$this->template->render(TRUE);
	}
	
} # End Template_Controller