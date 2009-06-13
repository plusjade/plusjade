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
		die($primary);
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
			if(empty($_POST['id']))
				die('Please specify a contact');

			# Get highest position
			$get_highest = $db->query("
				SELECT MAX(position) as highest 
				FROM contact_items 
				WHERE parent_id = '$tool_id'
			")->current()->highest;

			foreach($_POST['id'] as $id => $name)
			{
				$data = array(
					'parent_id'		=> $tool_id,
					'fk_site'		=> $this->site_id,
					'type'			=> $id,
					'display_name'	=> $name,
					'position'		=> ++$get_highest
				);
				$db->insert('contact_items', $data); 
			}
			$count = count($_POST['id']);
			die("$count Contact(s) added."); #status message				
		}

		$primary = new View('edit_contact/add_item');
		$contact_types = $db->query("SELECT * FROM contact_types");
		$primary->contact_types = $contact_types;
		$contacts = $db->query("
			SELECT * FROM contact_items 
			JOIN contact_types ON contact_types.type_id = contact_items.type
			WHERE parent_id = '$tool_id' 
			AND fk_site = '{$this->site_id}' 
			ORDER BY position
		");
		$primary->contacts = $contacts;
		$primary->tool_id = $tool_id;
		$primary->js_rel_command = "update-contact-$tool_id";
		die($primary);

	}
	
/*
 * EDIT single Contact
 * Loads into tier 2 facebox
 * @PARM (INT) $id = id of contact row 
 */
	public function edit($id=Null)
	{
		valid::id_key($id);
		if($_POST)
		{
			$db = new Database;
			$data = array(
				'display_name'	=> $_POST['display_name'],
				'value'			=> $_POST['value'],
				'enable'		=> $_POST['enable'],
			);
			$db->update( 'contact_items', $data, array('id' => $id, 'fk_site' => $this->site_id) );
			die('Contact edited'); # success
		}

		$primary = new View("edit_contact/edit_item");
		$join = 'JOIN contact_types ON contact_types.type_id = contact_items.type';
		$item = $this->_grab_tool_child('contact', $id, $join);		
		$primary->item = $item;
		$primary->js_rel_command = "update-contact-$item->parent_id";
		die($primary);

	}
	
/*
 * DELETE single contact
 * Success Response via JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of contact row 
 */
	public function delete($id=NULL)
	{
		die($this->_delete_single_common('contact', $id));
	}

/*
 * SAVE Contacts sort order
 * Success Response via Facebox_response
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		if( empty($_GET['contact']) )
			die('No items to sort');
			
		die( $this->_save_sort_common($_GET['contact'], 'contact_items') );
	}
	
	static function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}

	static function _tool_deleter($tool_id, $site_id)
	{
		return FALSE;
	}
	
}