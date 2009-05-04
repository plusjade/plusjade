<?php

class Edit_Contact_Controller extends Edit_Tool_Controller {

	function __construct()
	{
		parent::__construct();
	}

/*
 * MANAGE Contacts 
 * Loads into tier 1 Facebox
 * @PARM (INT) $page_id = page id (table pages) contact tool is installed
 */
	function manage($tool_id)
	{
		valid::id_key($tool_id);
		$db = new Database;
		$contact_types = $db->query("SELECT * FROM contact_types");	
		$join = 'JOIN contact_types ON contact_types.type_id = contact_items.type';
		$primary = $this->_view_manage_tool_items('contact', $tool_id, $join);
		$primary->set_global('contact_types', $contact_types);
		echo $primary;
		die();
	}

/*
 * ADD Contacts multiple
 * Loads into tier 1 facebox
 *
 */
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$db = new Database;
		
		if($_POST)
		{
			if(! empty($_POST['id']) )
			{
				# Get highest position
				$get_highest = $db->query("SELECT MAX(position) as highest FROM contact_items WHERE parent_id = '$tool_id' ");
				$highest = $get_highest->current()->highest;
				
				foreach($_POST['id'] as $id => $name)
				{
					$data = array(
						'parent_id'		=> $tool_id,
						'fk_site'		=> $this->site_id,
						'type'			=> $id,
						'display_name'	=> $name,
						'position'		=> ++$highest
					);
					$db->insert('contact_items', $data); 
				}
				echo 'Contacts added!!<br>Updating...'; #status message
			}
			else
				echo 'Please specify a contact';	
		}
		else
		{
			$primary = new View('edit_contact/new_item');
			$contact_types = $db->query("SELECT * FROM contact_types");
			$primary->contact_types = $contact_types;
			$contacts = $db->query("SELECT * FROM contact_items 
				JOIN contact_types ON contact_types.type_id = contact_items.type
				WHERE parent_id = '$tool_id' 
				AND fk_site = '{$this->site_id}' 
				ORDER BY position
			");
			$primary->contacts = $contacts;
			$primary->tool_id = $tool_id;
			echo $primary;	
		}
		die();
	}
	
/*
 * EDIT single Contact
 * Loads into tier 2 facebox
 * @PARM (INT) $id = id of contact row 
 */
	public function edit($id=Null)
	{
		valid::id_key($id);
		
		if(! empty($_POST['enable']) )
		{
			$db = new Database;
			$data = array(
				'display_name'	=> $_POST['display_name'],
				'value'			=> $_POST['value'],
				'enable'		=> $_POST['enable'],
			);
			$db->update( 'contact_items', $data, array('id' => $id, 'fk_site' => $this->site_id) );
			echo 'Contact edited!!<br>Updating...'; # success
		}
		else
		{
			$primary = new View("edit_contact/single_item");
			$join = 'JOIN contact_types ON contact_types.type_id = contact_items.type';
			$item = $this->_grab_tool_child('contact', $id, $join);		
			$primary->item = $item;
			echo $primary;
		}
		die();	
	}
	
/*
 * DELETE single contact
 * Success Response via JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of contact row 
 */
	public function delete($id=NULL)
	{
		$this->_delete_single_common('contact', $id);
		die();
	}

/*
 * SAVE Contacts sort order
 * Success Response via Facebox_response
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		$this->_save_sort_common($_GET['contact'], 'contact_items');
		die();	
	}
}

/* -- end of application/controllers/contact.php -- */