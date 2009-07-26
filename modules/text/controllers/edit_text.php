<?php

class Edit_Text_Controller extends Edit_Tool_Controller {

/*
 *
 */
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * add single Item
 */
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);		

		$text = ORM::factory('text')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $text->loaded)
			die('invalid text id');

		if($_POST)
		{
			$text->body = $_POST['body'];
			$text->save();
			die('Changes Saved');
		}
		
		$primary = new View("edit_text/add_item");
		$primary->item = $text;
		$primary->js_rel_command = "update-text-$text->id";
		die($primary);
	}
	
	
/*
 * edit a single item, uses the same logic as add so we're all good.
 */
	public function edit($id=NULL)
	{
		$this->add($id);
	}
	
	public static function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}
	
	public static function _tool_deleter($tool_id, $site_id)
	{
		return true;
	}
}

/* -- end of application/controllers/showroom.php -- */