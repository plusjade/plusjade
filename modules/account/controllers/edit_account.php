<?php

class Edit_Account_Controller extends Edit_Tool_Controller {

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
	public function add($id=NULL)
	{
		valid::id_key($id);		
		$db = new Database;

		if($_POST)
		{
			if(!empty($_POST['bio']))
				$db->update(
					'texts',
					array('body' => $_POST['body']),
					"id = '$id' AND fk_site = '$this->site_id'"
				);
			else
				$db->update(
					'texts',
					array('body' => $_POST['body']),
					"id = '$id' AND fk_site = '$this->site_id'"
				);
			die('Changes Saved');
		}
		
		$primary = new View("edit_text/add_item");
		$parent = $db->query("
			SELECT * FROM texts 
			WHERE id = '$id' 
			AND fk_site = '$this->site_id'
		")->current();			
		$primary->item = $parent;
		$primary->js_rel_command = "update-text-$parent->id";
		die($primary);
	}
/*
 * edit a single item, uses the same logic as add so we're all good.
 */
	public function edit($id=NULL)
	{
		$this->add($id);
	}
	
	static function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}
	
	static function _tool_deleter($tool_id, $site_id)
	{
		return true;
	}
}

/* -- end of application/controllers/showroom.php -- */