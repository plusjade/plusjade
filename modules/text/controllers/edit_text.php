<?php

/*
 * stores and displays text.
 */
 
class Edit_Text_Controller extends Edit_Tool_Controller {

	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * add single Item
 */
	public function add($parent_id=NULL)
	{
		valid::id_key($parent_id);		

		$text = ORM::factory('text')
			->where('fk_site', $this->site_id)
			->find($parent_id);	
		if(!$text->loaded)
			die('invalid text id');

		if($_POST)
		{
			$text->body = $_POST['body'];
			# update the cache
			$text->cache = $this->parse_tokens($text->body);
			$text->save();
			die('Text Changes Saved');
		}
		
		$view = new View("edit_text/add_item");
		$view->item = $text;
		$view->js_rel_command = "update-text-$text->id";
		die($view);
	}
	
	
/*
 * edit a single item, uses the same logic as add so we're all good.
 */
	public function edit($id=NULL)
	{
		$this->add($id);
	}
	

	public static function _tool_deleter($parent_id, $site_id)
	{
		return true;
	}
}

/* -- end of application/controllers/showroom.php -- */