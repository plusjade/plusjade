<?php defined('SYSPATH') or die('No direct script access.');
 
class Load_Tool_Core {

/*
 * Dynamically loads a tool object instance
 * Used @ /application/controllers/build_page.php
 *
 */
	public static function factory($tool)
	{
		$tool = ucwords($tool);
		switch ($tool)
		{
			case 'Format':
				$tool = new Format_Controller(); 			
				break;				
			case 'About':
				$tool = new About_Controller();
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
			case 'Navigation':
				$tool = new Navigation_Controller(); 			
				break;
			case 'Blog':
				$tool = new Blog_Controller(); 			
				break;
			case 'Account':
				$tool = new Account_Controller(); 			
				break;
			case 'Forum':
				$tool = new Forum_Controller(); 			
				break;					
			default:
				die("<b>error:</b> '$tool' tool does not exist (Load_Tool::factory)");
		}	
		return $tool;
	}

/* 
 * Loads a tool instance, but for admin/edit controllers.
*/
	public static function edit_factory($tool)
	{
		$tool = ucwords($tool);
		switch ($tool)
		{
			case 'Format':
				$tool = new Edit_Format_Controller(); 			
				break;				
			case 'About':
				$tool = new Edit_About_Controller(); 			
				break;
			case 'Album':
				$tool = new Edit_Album_Controller(); 			
				break;
			case 'Reviews':
				$tool = new Edit_Reviews_Controller(); 			
				break;	
			case 'Showroom':
				$tool = new Edit_Showroom_Controller(); 			
				break;
			case 'Text':
				$tool = new Edit_Text_Controller(); 			
				break;
			case 'Calendar':
				$tool = new Edit_Calendar_Controller(); 			
				break;
			case 'Navigation':
				$tool = new Edit_Navigation_Controller(); 			
				break;
			case 'Blog':
				$tool = new Edit_Blog_Controller();				
				break;						
			case 'Account':
				$tool = new Edit_Account_Controller();
				break;
			case 'Forum':
				$tool = new Edit_Forum_Controller();
				break;
			default:
				die("<b>error:</b> '$tool' edit_tool does not exist (Load_Tool::edit_factory)");
		}	
		return $tool;
	}
} # end



