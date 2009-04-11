<?php

class Edit_Contact_Controller extends Edit_Module_Controller {

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
		tool_ui::validate_id($tool_id);
		$db = new Database;

		# Get all available contact types
		$result = $db->query("SELECT * FROM contact_types");
		$this->template->set_global('contact_types', $result);
		
		# Javascript
		$this->template->rootJS('		
			// Make contacts sortable
			$("#generic_sortable_list").sortable({ handle : ".handle", axis : "y" });	
		');
		
		# Javascript Save sort
		$save_sort_js = tool_ui::js_save_sort_init('contact');
		$this->template->rootJS($save_sort_js);

		# Javascript delete
		$delete_js = tool_ui::js_delete_init('contact');
		$this->template->rootJS($delete_js);
	
		# Show the manage panel
		# JOIN contact_type to item table.
		$join = 'JOIN contact_types ON contact_types.type_id = contact_items.type';
		$this->_show_manage_module_items('contact', $tool_id, $join);	
		die();
	}

/*
 * ADD Contacts multiple
 * Loads into tier 1 facebox
 *
 */
	public function add($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
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
			$primary = new View('contact/edit/new_item');
						
			# Grab all contact types
			$result = $db->query("SELECT * FROM contact_types");
			$primary->contact_types = $result;
			
			# Grab items
			$result = $db->query("SELECT * FROM contact_items JOIN contact_types ON contact_types.type_id = contact_items.type  WHERE parent_id = '$tool_id' AND fk_site = '{$this->site_id}' ORDER BY position");
			$primary->contacts = $result;
			
			$primary->tool_id = $tool_id;
			$this->template->primary = $primary;	
			$this->template->render(true);	
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
		tool_ui::validate_id($id);
		
		if(! empty($_POST['enable']) )
		{
			$db = new Database;
			$data = array(
				'display_name'	=> $_POST['display_name'],
				'value'			=> $_POST['value'],
				'enable'		=> $_POST['enable'],
			);
			$db->update( 'contact_items', $data, array('id' => $id, 'fk_site' => $this->site_id) );
		
			# Status message
			echo 'Contact edited!!<br>Updating...';
		}
		else
		{
			$primary = new View("contact/edit/single_item");
			
			# Join contact_type to item table.
			$join = 'JOIN contact_types ON contact_types.type_id = contact_items.type';
			$item = $this->_grab_module_child('contact', $id, $join);
			
			$primary->item = $item;
			$this->template->rootJS = '
				$(".facebox #place_address").click(function(){
					var html = $(".facebox #address_container").html();
					$(".facebox #contact_textarea").html(html);
					return false;
				});
			';
			$this->template->primary = $primary;
			$this->template->render(true);
			
			#$this->_show_edit_single( 'contact', $id, $join);
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
	
	public function css($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);

		# Overwrite old file with new file contents;
		if($_POST)
		{
			echo Css::save_contents('contact', $tool_id, $_POST['contents'] );
		}
		else
		{
			$primary = new View('css/edit_single');

			$primary->contents	= Css::get_contents('contact', $tool_id);
			$primary->tool_id	= $tool_id;
			$primary->tool_name	= 'contact';
			
			echo $primary;
		
		}		
		die();
	}
}

/* -- end of application/controllers/contact.php -- */