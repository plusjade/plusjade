<?php
class Slide_Panel_Controller extends Public_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function _index($tool_id)
	{		
		$db = new Database;
		$primary = new View("public_slide_panel/index");
				
		# Grab Slide panel parent for this page
		$parent = $db->query("
			SELECT * FROM slide_panels
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();
		
		# Grab slide panel items
		$items = $db->query("
			SELECT * FROM slide_panel_items
			WHERE parent_id = '$parent->id'
			AND fk_site = '$this->site_id'
			ORDER BY position
		");
				
		$primary->slide_panels = $items;	
		
		# Javascript
		$primary->add_root_js_files('slide/slide_4.js');
		
		return $this->public_template($primary, 'slide_panel', $tool_id);
	}
	
}
/* -- end of application/controllers/home.php -- */