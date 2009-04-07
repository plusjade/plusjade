<?php
class Edit_Faq_Controller extends Edit_Module_Controller {

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
   
	function index($page_id)
	{
		$db = new Database;
		
		if($_POST)
		{
			# add faq	
			if(!empty($_POST['add_faq']))
			{
				$data = array(
						'fk_site'	=> $this->site_id,
						'page_id'	=> $page_id,
						'question'	=> $_POST['question'],
						'answer'	=> $_POST['answer'],
				);

				$db->insert('faq', $data); 
				
				$this->template->readyJS('$.jGrowl("New Faq added");'); #status
			}
			
			# update faq
			if(!empty($_POST['update']))
			{	
				$data = array(
				   'question'	=> $_POST['question'],
				   'answer'		=> $_POST['answer'],
				);
				$db->update('faq', $data, "id = '{$_POST['id']}'");
				
				$this->template->readyJS('$.jGrowl("Faq updated");'); #status				
			}
			
			# delete faq
			if(!empty($_POST['delete']))
			{
				$db->delete('faq', array('id' => "{$_POST['id']}")); 						
				
				$this->template->readyJS('$.jGrowl("Faq deleted!");'); #status
			}
	
		}
	
		# Javascript
		$embed_js ='
			$("#select_tab_nav").change(function(){
				var i = $(this).val();
				$tabs.tabs("select", i);
			}); 
		';
		$this->template->readyJS($embed_js);		

		# Grab entries from module table
		$this->_grab_module_items('faq', $page_id);
	}
	
}

/* -- end of application/controllers/faq.php -- */