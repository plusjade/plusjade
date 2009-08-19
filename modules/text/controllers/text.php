<?php defined('SYSPATH') OR die('No direct access allowed.');


class Text_Controller extends Public_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

	
	
	public function _index($tool_id)
	{		
		$text = ORM::factory('text')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $text->loaded)
			return $this->public_template('this text id not found.', 'text', $text);
	
		# Need this to be able to append toolbar in edit mode
		if( empty($text->body) AND $this->client->logged_in() )
			$text->body = '<p class="aligncenter">(sample text)</p>';
		
		$primary = new View("public_text/basic/stock");
		$primary->item = $text;
		return $this->public_template($primary, 'text', $text);
	}


	
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{
			$text = ORM::factory('text', $tool_id);
			$text->body = View::factory('public_text/sample')->render();
			$text->save();
		}

		return 'add';
	}	
}

/* -- end -- */