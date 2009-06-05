<?php
class Edit_Faq_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for FAQ module.
 *	Extends the module template to build page quickly in facebox frame mode.
 *	Only Logged in users should have access
 *
 */
	function __construct()
	{
		parent::__construct();
	}

	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$db = new Database;
		$primary = new View('edit_faq/manage');
		$items = $db->query("
			SELECT * FROM faq_items 
			WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
			ORDER BY position
		");
		$primary->items = $items;
		$primary->tool_id = $tool_id;
		die($primary);
	}
	
	function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		if($_POST)
		{
			$db = new Database;
			
			# Get highest position
			$get_highest = $db->query("
				SELECT MAX(position) as highest 
				FROM faq_items 
				WHERE parent_id = '$tool_id' 
			")->current()->highest;

			$data = array(
				'fk_site'	=> $this->site_id,
				'parent_id'	=> $tool_id,
				'question'	=> $_POST['question'],
				'answer'	=> $_POST['answer'],
				'position'	=> ++$get_highest
				
			);
			$db->insert('faq_items', $data); 
			
			Load_Tool::die_guid(@$_POST['guid']); # if wizard : die($guid)
			
			die('Question added'); #success
		}
		else
		{
			$primary = new View("edit_faq/new_item");
			$primary->tool_id = $tool_id;
			
			# For the javascript tool_html updates
			$primary->hidden_guid = Load_Tool::is_get_guid(@$_GET['guid']);				
			$action = ( empty($_GET['guid']) ) ? 'update' : 'add';	
			$primary->js_rel_command = "$action-faq-$tool_id";
			
			die($primary);
		}
	}


	function edit($id=NULL)
	{
		valid::id_key($id);
		
		if($_POST)
		{
			$db = new Database;
			$data = array(
			   'question'	=> $_POST['question'],
			   'answer'		=> $_POST['answer']
			);
			$db->update('faq_items', $data, "id = '$id' AND fk_site='$this->site_id'");		
			die('Faq updated!<br>Updating...'); # success			
		}
		else
			die( $this->_view_edit_single('faq', $id) );
	}

	function delete($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$this->_delete_single_common('faq', $tool_id);
		die('Faq deleted!'); # success
	}

	/* 
	 * save the positions of the faq questions
	 * the ids are passed directly from the DOM so we don't need a tool_id
	 */
	function save_sort()
	{
		die( $this->_save_sort_common($_GET['faq'], 'faq_items') );
	}
	
	function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);

		if($_POST)
		{
			$db = new Database;
			$data = array(
				'title'	=> $_POST['title'],
			);
			$db->update('faqs', $data, "id='$tool_id' AND fk_site = '$this->site_id'"); 						
			die( 'Settings Updated!<br>Updating...'); # success
		}
		else
			die( $this->_view_edit_settings('faq', $tool_id) );
	}
	
	static function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}
}

/* -- end of application/controllers/edit_faq.php -- */