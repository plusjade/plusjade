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
	public function add($id=NULL, $action='add')
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
			
			# this output the guid so we can use it to update the DOM
			# via ajaxForm success callback			
			Load_Tool::die_guid(@$_POST['guid']);
				
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
			

			$primary->hidden_guid = Load_Tool::is_get_guid(@$_GET['guid']);

			$primary->js_rel_command = "$action-text-$parent->id";
			die($primary);
		}	
	}
/*
 * edit a single item
 */
	public function edit($id=NULL)
	{
		$this->add($id, 'update');
	}
	
	static function _tool_adder()
	{
		return 'add';
	}
}

/* -- end of application/controllers/showroom.php -- */