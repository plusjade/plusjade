<?php

class Edit_Text_Controller extends Edit_Module_Controller {

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
 * Add Item(s)
 */ 
	public function add($page_id=NULL, $position=NULL)
	{		
		tool_ui::validate_id($page_id);
		tool_ui::validate_id($position);
		
		if(!empty($_POST['add_item']))
		{
			$db = new Database;
			
			# Grab module parent
			$parent = $this->_grab_module_parent('showroom', $page_id, $position);
			$parent_id = $parent->id;
				
			$data = array(			
				'parent_id'	=> $parent_id,
				'fk_site'	=> $this->site_id,
				'name'		=> $_POST['name'],
				'intro'		=> $_POST['intro'],
				'body'		=> $_POST['body'],
				'price'		=> $_POST['price'],
				'position'	=> 0,				
			);	

			# Upload image if sent
			if(!empty($_FILES['image']['name']))
				if (! $data['image'] = $this->_upload_image($_FILES) )
					echo '<script>$.jGrowl("Image must be jpg, gif, or png.")</script>';
				
				
			$db->insert('showroom_items', $data);
			
			echo 'Item added'; #status message
		}
		else
		{
			#Javascript
			$this->template->rootJS = '$("#container-1").tabs()';
			$this->_show_add_single('showroom', $page_id, $position);
		}
		die();		
	}
	
/*
 * Edit single Item
 */
	public function edit($id=NULL)
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
	
	
/*
 * Upload an image to showroom
 * @Param array $file = $_FILES array
 */ 	
	private function _upload_image($_FILES)
	{		
		$files = new Validation($_FILES);
		$files->add_rules('image', 'upload::valid','upload::type[gif,jpg,png]', 'upload::size[1M]');
		
		if ($files->validate())
		{
			# Temp file name
			$filename	= upload::save('image');
			$image		= new Image($filename);			
			$ext		= $image->__get('ext');
			$file_name	= basename($filename).'.'.$ext;
			$directory	= DOCROOT."data/{$this->site_name}/assets/images/showroom";			
			
			if(! is_dir($directory) )
				mkdir($directory);	
			
			if( $image->__get('width') > 350 )
				$image->resize(350, 650);
			
			$image->save("$directory/$file_name");
		 
			# Remove temp file
			unlink($filename);
			
			return $file_name;
			#status message
			#return '<script> $.jGrowl("Image uploaded!")</script>';
		}
		else
			return FALSE;

		
	}
	
}

/* -- end of application/controllers/showroom.php -- */