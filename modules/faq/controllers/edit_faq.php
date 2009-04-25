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
		tool_ui::validate_id($tool_id);
		$db = new Database;
		$primary = new View('faq/edit/manage');
		
		# Get faq items
		$items = $db->query("SELECT * FROM faq_items 
			WHERE parent_id = '$tool_id' AND fk_site = '$this->site_id'
			ORDER BY position
		");
		$primary->items = $items;
		
		# Javascript
		$embed_js ='
			$("#generic_sortable_list").sortable({handle:".handle"});
		';
		$embed_js .= tool_ui::js_save_sort_init('faq');
		$this->template->rootJS($embed_js);		
		$this->template->primary = $primary;
		echo $this->template;
		die();	
	}
	
	function add($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		$db = new Database;
		
		if($_POST)
		{
			# Get highest position
			$get_highest = $db->query("SELECT MAX(position) as highest 
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
			echo 'Question added'; #status
		}
		else
		{
			echo $this->_show_add_single('faq', $tool_id);
		}
	}


	function edit($id=NULL)
	{
		tool_ui::validate_id($id);
		
		if($_POST)
		{
			$db = new Database;
			$data = array(
			   'question'	=> $_POST['question'],
			   'answer'		=> $_POST['answer']
			);
			$db->update('faq_items', $data, "id = '$id' AND fk_site='$this->site_id'");
			
			echo 'Faq updated!<br>Updating...'; #status				
		}
		else
		{
			echo $this->_show_edit_single('faq', $id);
		}
		die();
	}

	function delete($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		
		$this->_delete_single_common('faq', $tool_id);
		echo 'Faq deleted!'; #status

		die();
	}

	public function save_sort()
	{
		echo $this->_save_sort_common($_GET['faq'], 'faq_items');
		die();	
	}
	
	function settings($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);

		if($_POST)
		{
			$db = new Database;
			$data = array(
				'title'	=> $_POST['title'],
			);
			
			$db->update('faqs', $data, "id='$tool_id' AND fk_site = '$this->site_id'"); 						
			
			echo 'Settings Updated!<br>Updating...'; #success
		}
		else
		{
			echo $this->_show_edit_settings('faq', $tool_id);
		}
		die();
	}
	
}

/* -- end of application/controllers/faq.php -- */