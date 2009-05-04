<?php
class Faq_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{
		$db = new Database;
		$primary = new View("faq/index");
		
		$parent = $db->query("SELECT * FROM faqs 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();	

		$items = $db->query("SELECT * FROM faq_items 
			WHERE parent_id = '$tool_id' 
			AND fk_site = '$this->site_id'
			ORDER BY position
		");
		
		$primary->parent = $parent;	
		$primary->items = $items;	
		
		# Javascript
		$primary->readyJS('faq','index', $parent->id);

		return $primary;	
	}
}

/* -- end of application/controllers/faq.php -- */