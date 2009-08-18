<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Compile and manage javascript files for both live and admin sessions.
 * The javascripts scope apply to all site functionality.
 */
 
class Js_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

/*
 * Package a singular js file for websites when in live mode.
 * This is good because we can keep each file modular,
 * but also optimize and minimize http requests.
 */
	public function live()
	{
		header('Content-type: text/javascript');
		header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");	

		$files = array(
			'jquery_latest.js',
			'ajax_form/ajax_form.js',
			'timeago/jquery.timeago.js',
			#'gallery/jquery.galleryview-1.1.js', # this should not be here.
			#'slide/slide_4.js' # testing
		);
		
		ob_start();
		foreach($files as $file)
			if(file_exists(DOCROOT . "_assets/js/$file"))
				readfile(DOCROOT . "_assets/js/$file");

		die();
	}

	
/*
 * Package a singular js file with all needed admin functionality.
 * Admin mode should load every global js dependency as well as
 * all available tool js dependencies, since any tool can be loaded via ajax at any time.
 */
	public function admin()
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
			'gallery/gallery.js', # album tool
			'lightbox/lightbox.js', # album tool
			'timeago/jquery.timeago.js',
			'simple_tree/jquery.simple.tree.js',
			'admin/init.js',
		);

		ob_start();
		foreach($files as $file)
			if(file_exists(DOCROOT . "_assets/js/$file"))
				readfile(DOCROOT . "_assets/js/$file");

		die();
	}
	

	
} /* End  */
