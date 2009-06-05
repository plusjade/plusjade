<?php defined('SYSPATH') or die('No direct script access.');
 
class Load_Tool_Core {

	/*
	 * Dynamically loads a tool object instance
	 * Used @ /application/controllers/build_page.php
	 *
	 */
	function factory($tool)
	{
		switch ($tool)
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
			case 'Navigation':
				$tool = new Navigation_Controller(); 			
				break;
			case 'Blog':
				$tool = new Blog_Controller(); 			
				break;						
			default:
				die('<b>error:</b> tool does not exist');
		}	
		return $tool;
	}

	function edit_factory($tool)
	{
		switch ($tool)
		{
			case 'Slide_Panel':
				$tool = new Edit_Slide_Panel_Controller(); 			
				break;				
			case 'About':
				$tool = new Edit_About_Controller(); 			
				break;					
			case 'Faq':
				$tool = new Edit_Faq_Controller(); 
				break;
			case 'Contact':
				$tool = new Edit_Contact_Controller(); 
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
			default:
				die('<b>error:</b> edit_tool does not exist');
		}	
		return $tool;
	}
/*
	these functions are useful when going through the add_tool wizard.
	Wizard Steps:
		1. tool/add 
			2. edit_tool_controller::_tool_adder()
				3. method as defined by _tool_adder (usually add)
	
	typically the tool will call "add" in which case the add method
	must know whether it should update an already existing tool in the dom
	(this case is when using the add link via the red toolkit)
	
	or add the just-added tool parent into the DOM
	These functions take care of that check.
	
	Note: if the tool does not invoke "add" it probably will do "manage"
	in which case the checks aren't needed.
*/
	# this output the guid so we can use it to update the DOM
	# via ajaxForm success callback	
	static function die_guid($guid=NULL)
	{
		if(NULL != $guid)
		{
			valid::id_key($guid);
			die("$guid");
		}
	}
	
	static function is_get_guid($guid=NULL)
	{
		if(NULL != $guid)
		{
			valid::id_key($guid);
			return '<input type="hidden" name="guid" value="'. $guid .'">';
		}	
		return '';
	}
}