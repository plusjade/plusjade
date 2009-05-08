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
 * Edit single Item
 */
	public function add($id=NULL)
	{
		valid::id_key($id);		
		$db = new Database;

		if($_POST)
		{
			echo'<pre>';print_r($_POST);echo'</pre>';die();
			$data = array(
				'body'	=> $_POST['body'],		
			);		
			$db->update('texts', $data, "id = '$id' AND fk_site = '$this->site_id'");
			echo 'Changes Saved!<br>Updating...';
		}
		else
		{
			$primary = new View("edit_text/single_item");
			$parent = $db->query("SELECT * FROM texts 
				WHERE id = '$id' 
				AND fk_site = '$this->site_id'
			")->current();			
			$primary->item = $parent;
			echo $primary;
		}
		die();		
	}
}

/* -- end of application/controllers/showroom.php -- */