<?php

class Edit_Text_Controller extends Edit_Tool_Controller {

/*
 *	Handles all editing logic for Showroom module.
 *	Extends the module template to build page quickly for ajax rendering.
 *	Only Logged in users should have access
 *
 */
 
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * Manage Function display a sortable list of tool resources (items)
 */
	function manage($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);

		$embed_js ='
		  // Make Sortable
			$("#generic_sortable_list").sortable({ handle : "img", axis : "y" });
		';
		$this->template->rootJS($embed_js);
		
		# Javascript Save sort
		$save_sort_js = $this->_js_save_sort_init('showroom');
		$this->template->rootJS($save_sort_js);
		
		# Javascript delete
		$delete_js = $this->_js_delete_init('showroom');
		$this->template->rootJS($delete_js);
		
		# Show the manage panel
		$this->_show_manage_module_items('showroom', $tool_id);
		die();
	}

/*
 * Edit single Item
 */
	public function add($id=NULL)
	{
		tool_ui::validate_id($id);
		
		$db = new Database;
			
		# Edit item
		if($_POST)
		{
			$data = array(
				'body'	=> $_POST['body'],		
			);		
			$db->update('texts', $data, "id = '$id' AND fk_site = '$this->site_id'");
			
			echo 'Changes Saved!<br>Updating...';
		}
		else
		{		
			$parent = $db->query("SELECT * FROM texts WHERE id = '$id' AND fk_site = '$this->site_id' ")->current();			
			$primary = new View("text/edit/single_item");
			
			$primary->item = $parent;
			$this->template->primary = $primary;
			$this->template->render(true);
		}
		
		die();		
	}

/*
 * DELETE showroom (item) single
 * Success Response via inline JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of showroom item row 
 */
	public function delete($id=NULL)
	{
		tool_ui::validate_id($id);		
		echo 'done!';die();
		# Get image object
		$image = $this->_grab_module_child('showroom', $id);
				
		# Image File delete		
		$image_path = "{$this->site_data_dir}/assets/images/showroom/$image->image";	

			
		if( file_exists($image_path) )
			unlink($image_path);
			
		# db delete
		$this->_delete_single_common('showroom', $id);
		die();
	}

/*
 * SAVE items sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		$this->_save_sort_common($_GET['showroom'], 'showroom_items');
		die();
	}
	

}

/* -- end of application/controllers/showroom.php -- */