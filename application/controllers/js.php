<?php
class Js_Controller extends Controller {

/**
 * Compile and manage javascript files for admin session.
 */
	
	function __construct()
	{
		parent::__construct();
	}


/*
 * build singular js file for websites when in live mode.
 * This is good because we can keep each file modular,
 * but also optimize and minimize http requests.
 */
	function live()
	{
		header('Content-type: text/javascript');
		header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");	

		$files = array(
			'jquery_latest.js',
			'ajax_form/ajax_form.js',
			'timeago/jquery.timeago.js',
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
			'timeago/jquery.timeago.js',
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
 * load all backend tool javascript as one file.
 */
	function tools()
	{
		header('Content-type: text/javascript');
		header("Pragma: public");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");		
		
		$db = new Database;
		$system_tools = $db->query("
			SELECT LOWER(name) as name
			FROM system_tools
		");
		ob_start();	
		foreach($system_tools as $tool)
		{
			$admin_js = MODPATH . "$tool->name/views/public_$tool->name/js/all.js";
			
			if(file_exists($admin_js))
				readfile($admin_js);
		}				
		die( ob_get_clean() );
	}
	
} /* End  */
