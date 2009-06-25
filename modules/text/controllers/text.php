<?php

class Text_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{		
		$db = new Database;
		$primary = new View("public_text/index");
		
		$parent = $db->query("
			SELECT * FROM texts 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();			
		
		# Need this to be able to append toolbar in edit mode
		if( empty($parent->body) AND $this->client->logged_in() )
			$parent->body = '<p class="aligncenter">(sample text)</p>';
		
		$primary->item = $parent;
		
		
		return $this->public_template($primary, 'text', $tool_id, $parent->attributes);
	}
	
}

/* -- end of application/controllers/showroom.php -- */