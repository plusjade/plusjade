<?php
class Js_Controller extends Controller {

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
 * build singular js file with all needed admin functionality.
 * This is good because we can keep each file modular,
 * but also optimize and minimize http requests.
 */
	function admin()
	{
		header('Content-type: text/javascript');
		header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");	

		$files = array(
			'jquery_latest.js',
			'ui/ui_latest_lite.js',
			'facebox/public_multi.js',
			'ajax_form/ajax_form.js',
			'jw/jwysiwyg.js',
			'swfupload/swfupload.js',
			'simple_tree/jquery.simple.tree.js',
			'admin/init.js',
		);
		ob_start();
		foreach($files as $file)
		{
			$admin_js = DOCROOT . "_assets/js/$file";
			if(file_exists($admin_js))
				readfile($admin_js);
		}
		die( ob_get_clean() );
	}
	
/*
 * 
 * and load it as one file. useful when in admin mode
 */
	function tools()
	{
		#if(!$this->client->can_edit($this->site_id))
		#	die('Please login');
		
		header('Content-type: text/javascript');
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		
		# load all admin-mode tool css files.
		$db = new Database;
		$tools_list = $db->query("
			SELECT LOWER(name) as name
			FROM tools_list
		");
		ob_start();	
		foreach($tools_list as $tool)
		{
			$admin_js	= MODPATH . "$tool->name/views/public_$tool->name/js/all.js";
			
			if(file_exists($admin_js))
				readfile($admin_js);
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
		if(!$this->client->can_edit($this->site_id))
			die('Please login');
		valid::id_key($name_id);	
		valid::id_key($tool_id);		
		
		$css_file_path = DATAPATH . "$this->site_name/tools_css";
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

		$primary = new View('css/edit_file');			
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