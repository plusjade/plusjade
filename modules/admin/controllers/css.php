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
		foreach($tool_data as $tool)
		{
			$custom_file = DATAPATH . "$this->site_name/tools_css/$tool->name/$tool->tool_id.css";
			if(file_exists($custom_file))
				readfile($custom_file);
		}
		
		# This is wrong FIX IT
		$image_path = "THIS_IS_WRONG/application/views/$this->theme/global/images";
		
		$contents		=  ob_get_clean();
		$keys			= '%PATH%';
		$replacements	= $image_path;
	
		die( str_replace($keys, $replacements , $contents) );
	}
	
/*
 * load admin_globa.css & all admin css from all tools. 
 * and load it as one file. useful when in admin mode
 */
	function admin()
	{
		$this->client->can_edit($this->site_id);
		
		header("Content-type: text/css");
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		
		ob_start();	
		
		# get the admin_global.css content
		$admin_global = DOCROOT . 'assets/css/admin_global.css';
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
			
			if(file_exists($admin_css))
				readfile($admin_css);
		}				
		die( ob_get_clean() );
	}
	
	
/*
 * Edit a custom css file associated with a tool.
 * Custom files are auto created if none exists.
 * Stored in /data/tools_css
 */
	function edit($name_id=NULL, $tool_id=NULL)
	{
		$this->client->can_edit($this->site_id);
		valid::id_key($name_id);	
		valid::id_key($tool_id);		
		
		$css_file_path = DOCROOT."data/$this->site_name/tools_css";
		$db = new Database;
		$tool = $db->query("
			SELECT LOWER(name) AS name 
			FROM tools_list 
			WHERE id='$name_id'
		")->current();
		$table = $tool->name.'s';
		
		# Overwrite old file with new file contents;
		if($_POST)
		{
			$attributes = $_POST['attributes'];	
			$db->update(
				$table,
				array('attributes' => $attributes ),
				"id='$tool_id' AND fk_site = '$this->site_id'
			");
			
			die( Css::save_custom_css($tool->name, $tool_id, $_POST['contents'] ) );
		}

		$primary = new View('css/edit_single');			
		$primary->contents	= Css::get_tool_css($tool->name, $tool_id);
		$primary->stock		= Css::get_tool_css($tool->name, $tool_id, TRUE);
		$primary->tool_id	= $tool_id;
		$primary->name_id	= $name_id;
		$primary->tool_name	= $tool->name;
		$primary->js_rel_command = "update-$tool->name-$tool_id";
		
		# get attributes for this tool.
		$parent = $db->query("
			SELECT attributes
			FROM $table
			WHERE id='$tool_id'
		")->current();
		$primary->attributes = $parent->attributes;
		
		die($primary);
	}
}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */