<?php

class Text_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{		
		$text = ORM::factory('text')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $text->loaded)
			return $this->public_template('this text id not found.', 'text', $tool_id, '');
	
		# Need this to be able to append toolbar in edit mode
		if( empty($text->body) AND $this->client->logged_in() )
			$text->body = '<p class="aligncenter">(sample text)</p>';
		
		$primary = new View("public_text/index");
		$primary->item = $text;
		return $this->public_template($primary, 'text', $tool_id, $text->attributes);
	}
	
}

/* -- end -- */