<?php defined('SYSPATH') or die('No direct script access.');
 
class Load_Module_Core {

	/*
	 * Dynamically loads a module object instance
	 * Used on /application/hooks/build_tool.php
	 *
	 */
	function factory($module)
	{
		switch ($module)
		{
			case 'Slide_Panel':
				$tool = new Slide_Panel_Controller(); 			
				break;				
			case 'About':
				$tool = new About_Controller(); 			
				break;					
			case 'Faq':
				$tool = new Faq_Controller(); 
				break;
			case 'Contact':
				$tool = new Contact_Controller(); 
				break;
			case 'Album':
				$tool = new Album_Controller(); 			
				break;
			case 'Reviews':
				$tool = new Reviews_Controller(); 			
				break;	
			case 'Showroom':
				$tool = new Showroom_Controller(); 			
				break;
			case 'Text':
				$tool = new Text_Controller(); 			
				break;
			case 'Calendar':
				$tool = new Calendar_Controller(); 			
				break;					
			default:
				$tool = new Text_Controller();
		}
		
		return $tool;
	}
	
	
}