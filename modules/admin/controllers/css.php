<?php
class Css_Controller extends Controller {

	/**
	 *	Compile the tools css for each page
	 * 
	 *
	 *
	 */
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * Build the css for the tools on the page
 * $generic_tools = The different tools on the page (non-repeats)
 * $all_tools = every tool on the page
 */
	function tools($generic_tools=NULL, $all_tools=NULL)
	{
		$primary = new View('css/tools');
		
		$generic_tools	= explode('-', $generic_tools);
		$all_tools		= explode('-', $all_tools);
		
		
		$primary->generic_tools = $generic_tools;
		
		
		$primary->all_tools = $all_tools;
		
		echo $primary;
		die();
		
	}

}

/* End of file admin.php */
/* Location: ./modules/admin/controllers/admin.php */