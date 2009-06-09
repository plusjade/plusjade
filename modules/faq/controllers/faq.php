<?php
class Faq_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{
		$db = new Database;
		$primary = new View("public_faq/index");
		
		$parent = $db->query("
			SELECT * FROM faqs 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();	

		$items = $db->query("
			SELECT * FROM faq_items 
			WHERE parent_id = '$tool_id' 
			AND fk_site = '$this->site_id'
			ORDER BY position
		");
		if('0' == $items->count())
			return $this->public_template('(no questions)', 'faq', $tool_id);
		
		$primary->parent = $parent;	
		$primary->items = $items;

		return $this->public_template($primary, 'faq', $tool_id);
	}
}

/* -- end of application/controllers/faq.php -- */