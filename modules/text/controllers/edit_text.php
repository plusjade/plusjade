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
	public function add($id=NULL)
	{
		valid::id_key($id);		
		$db = new Database;

		if($_POST)
		{
			#echo'<pre>';print_r($_POST);echo'</pre>';die();
			$data = array(
				'body'	=> $_POST['body'],		
			);		
			$db->update('texts', $data, "id = '$id' AND fk_site = '$this->site_id'");
			
			die('Changes Saved!<br>Updating...');
		}
		else
		{
			$primary = new View("edit_text/single_item");
			$parent = $db->query("
				SELECT * FROM texts 
				WHERE id = '$id' 
				AND fk_site = '$this->site_id'
			")->current();			
			$primary->item = $parent;
			
			$primary->js_rel_command = "update-text-$parent->id";
			
			die($primary);
		}	
	}
/*
 * edit a single item
 */
	public function edit($id=NULL)
	{
		$this->add($id);
	}
	
	static function _tool_adder()
	{
		return 'add';
	}
}

/* -- end of application/controllers/showroom.php -- */