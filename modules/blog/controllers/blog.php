<?php
class Blog_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{

		ob_start();
		$path = "http://localhost.com/blog/";
		
		#include($path);
		
		$handle = fopen("http://localhost.com/blog/", "rt");
		$source_code = fread($handle,9000); 

		return $source_code;
		return ob_get_contents();	
	}
}

/* -- end of application/controllers/faq.php -- */