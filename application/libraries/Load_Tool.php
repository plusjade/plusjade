<?php defined('SYSPATH') or die('No direct script access.');
 
class Load_Tool_Core {

	/*
	 * Dynamically loads a tool object instance
	 * Used on /application/controllers/build_page.php
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
				$tool = new Text_Controller();
		}
		
		return $tool;
	}

	/*
	 * Execute further commands after a tool is added
	 * if needed
	 */
	function after_add($tool_name, $tool_id)
	{		
		switch ($tool_name)
		{
			case 'Navigation':
				/*
				 * Need to add a root child to items list for every other
				 * child to belong to
				 * Add root child id to parent for easier access.
				 */
				$db = new Database;
				$data = array(
					'parent_id'		=> $tool_id,
					'fk_site'		=> $this->site_id,
					'display_name'	=> 'ROOT',
					'type'			=> 'none',
					'local_parent'	=> '0',
					'position'		=> '0'
				);	
				$root_insert = $db->insert('navigation_items', $data); 	
				
				$db->update('navigations', 
					array( 'root_id' => $root_insert->insert_id() ), 
					array( 'id' => $tool_id, 'fk_site' => $this->site_id ) 
				);
				
				return true;
				
			break;

			case 'Showroom':
				/*
				 * Need this to enable nested showroom categories
				 * Need to add a root child to items list for every other
				 * child to belong to
				 * Add root child id to parent for easier access.
				 */
				$db = new Database;
				$data = array(
					'parent_id'		=> $tool_id,
					'fk_site'		=> $this->site_id,
					'name'			=> 'ROOT',
					'local_parent'	=> '0',
					'position'		=> '0'
				);	
				$root_insert = $db->insert('showroom_items', $data); 	
				
				$db->update('showrooms', 
					array( 'root_id' => $root_insert->insert_id() ), 
					array( 'id' => $tool_id, 'fk_site' => $this->site_id ) 
				);
				
				return true;
				
			break;
		}
	
	}
}