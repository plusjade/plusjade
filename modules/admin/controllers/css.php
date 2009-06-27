<?php
class Css_Controller extends Controller {

	/**
	 * Compile the tools css for each page
	 * edit css files for each tool
	 * This controller shoudl be renamed to a more specific "tool" css controller
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * get user custom css for all tools on a page
 * these files should be 100% ready for output.
 * That is any tokens should have been parsed before saved.
 * tool-css files are saved relative to the installed theme.
 * ex: /_data/<site_name>/themes/<theme_name>/tools/<toolname>/<tool_id>.css 
 */
	function tools($page_id=NULL, $admin=FALSE)
	{
		header("Content-type: text/css");
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		
		valid::id_key($page_id);
		
		$db = new Database;
		$tool_data = $db->query("
			SELECT pages_tools.*, LOWER(tools_list.name) as name
			FROM pages_tools
			JOIN tools_list ON tools_list.id = pages_tools.tool
			WHERE (pages_tools.page_id BETWEEN 1 AND 5 OR page_id = '$page_id')
			AND fk_site = '$this->site_id'
			ORDER BY container, position
		");
		
		# load custom tool css files
		ob_start();
		
		$static_helpers = DOCROOT . '_assets/css/static_helpers.css';
		if (file_exists($static_helpers))
			readfile($static_helpers);

		$tool_types = array();
		foreach($tool_data as $tool)
		{	
			$theme_tool_css = Assets::data_path_theme("tools/$tool->name/css/$tool->tool_id.css");
			if(file_exists($theme_tool_css))
				readfile($theme_tool_css);
				
			$tool_types[$tool->name] = $tool->name;
		}
		
		# Load any tool-css needed for javascript functionality.
		foreach($tool_types as $key => $toolname)
		{
			$js_css = MODPATH . "$key/views/public_$key/js/js.css";
			if(file_exists($js_css))
				readfile($js_css);
		}
		die();
	}
	
/*
 * load admin_global.css & all admin css from all tools. 
 * and load it as one file. useful when in admin mode
 */
	function admin()
	{
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
		
		header("Content-type: text/css");
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		
		ob_start();	

		$static_helpers = DOCROOT . '_assets/css/static_helpers.css';
		if (file_exists($static_helpers))
			readfile($static_helpers);
			
		# get the admin_global.css content
		$admin_global = DOCROOT . '_assets/css/admin_global.css';
		if(file_exists($admin_global))
			readfile($admin_global);
		
		# load all admin-mode tool css files.
		$db = new Database;
		$tools_list = $db->query("
			SELECT LOWER(name) as name
			FROM tools_list
		");
	
		foreach($tools_list as $tool)
		{
			$admin_css	= MODPATH . "$tool->name/views/edit_$tool->name/admin.css";
			$js_css		= MODPATH . "$tool->name/views/public_$tool->name/js/js.css";
			
			if(file_exists($admin_css))
				readfile($admin_css);

			if(file_exists($js_css))
				readfile($js_css);
		}				
		die( ob_get_clean() );
	}
	
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */