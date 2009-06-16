<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class Template_Controller extends Controller {

	public $auto_render = TRUE;

	public function __construct()
	{
		parent::__construct();	
		#$this->profiler = new Profiler;		
		$this->template = new View("shell");	
	
		$data = array(
			'data_path' => 'http://' . ROOTDOMAIN . "/data/$this->site_name",
		);	
		$this->template->set_global($data);

		# Global CSS			
		$this->template->linkCSS("css/global.php?u=$this->site_name&t=$this->theme", '/assets/');
		
		# Global Javascript (Theme specific)	
		$this->template->linkJS('jquery_latest.js');
		
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
			$this->template->linkCSS('get/css/admin', url::site() );
			
			$js_files = array(
				'facebox/public_multi.js',
				'ui/ui_latest_lite.js',
				'ajax_form/ajax_form.js',
				'jw/jwysiwyg.js',
				'swfupload/swfupload.js',
				'simple_tree/jquery.simple.tree.js',
				'admin/init.js'	
			);
			$this->template->add_root_js_files($js_files);
			
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
	
	
	# Render loaded template when class is destroyed
	public function _render()
	{
		if ($this->auto_render == TRUE)
			$this->template->render(TRUE);
	}
	
} # End Template_Controller