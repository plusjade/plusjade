<?php
class Edit_Album_Controller extends Edit_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * MANAGE images in page album  
 * Loads into tier 1 Facebox
 * @PARM (INT) $page_id = page id (table pages) album is installed
 */
	function manage($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		echo $this->_view_manage_tool_items('album', $tool_id);		
		die();
	}

	public function add($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);
		echo $this->_view_add_single('album', $tool_id);
		die();
	}
	
/*
 * ADD images into album (multiple) 
 * Loads into tier 1 Facebox
 * @PARM (INT) $page_id = page id (table pages) album is installed
 */
	public function add_image($tool_id=NULL)
	{
		#TODO: TEST to make sure all names are unique. Cant have htem rewrite eachother.
		tool_ui::validate_id($tool_id);
		$db = new Database;	

		// Work-around for setting up a session because Flash Player doesn't send the cookies
		if (isset($_POST["PHPSESSID"])) {
			session_id($_POST["PHPSESSID"]);
		}
		
		# validate for file size 5M and type (images)
		if ( empty($_FILES['Filedata']['type']) OR ( $_FILES['Filedata']['type'] > 50000 ) )
		{
			echo 'Invalid File';
			die();
		}
		
		# Get highest position
		$get_highest = $db->query("SELECT MAX(position) as highest 
			FROM album_items 
			WHERE parent_id = '$tool_id' 
		")->current()->highest;

		# Setup image store directory
		$image_store = DOCROOT."data/$this->site_name/assets/images/albums";			
		if(!is_dir($image_store))
			mkdir($image_store);

		if(! is_dir($image_store.'/'.$tool_id) )
			mkdir($image_store.'/'.$tool_id);		

		$tmp_name	= $_FILES['Filedata']['tmp_name'];			
		$holder		= array ('tmp_name' => $tmp_name);
		$filename	= upload::save($holder);
		$image		= new Image($filename);			
		$ext		= $image->__get('ext');
		$name		= time()."$tool_id".".$ext";

		if( $image->save("$image_store/$tool_id/$name") )
		{
			# add to database
			$data = array(
				'parent_id'	=> $tool_id,
				'fk_site'	=> $this->site_id,
				'path'		=> $name,
				'position'	=> ++$get_highest,
			);
			$db->insert('album_items', $data);
			
			# Create sm thumb (TODO: optimize this later)
			$image_sm	= new Image($filename);			
			$sm_width	= $image_sm->__get('width');
			$sm_height	= $image_sm->__get('height');
			
			# Make square thumbnails
			if( $sm_width > $sm_height )
				$image_sm->resize(120,120,Image::HEIGHT)->crop(120,120);
			else
				$image_sm->resize(120,120,Image::WIDTH)->crop(120,120);
			
			$image_sm->save("$image_store/$tool_id/sm_$name");
		}
		unlink($filename);
		echo 'Images added!!<br>Updating...';	
		die();	
	}
	
	
# Edit_image 
	public function edit_item($id=NULL)
	{
		tool_ui::validate_id($id);
		$db = new Database;		

		if(! empty($_POST['parent_id']) )
		{			
			$data = array(
				'caption'	=> $_POST['caption'],
			);
			$db->update('album_items', $data, array( 'id' => $id, 'fk_site' => $this->site_id) );
			echo 'Image Updated!';
		}
		else
		{
			$this->_view_edit_single('album', $id);
		}
		die();
	}

/*
 * EDIT album settings
 * Loads into tier 1 facebox
 * [see root JS in this::manage() ]
 * @PARM (INT) $tool_id = id of the parent tool item
 */
	public function settings($tool_id=NULL)
	{
		tool_ui::validate_id($tool_id);		
		$db = new Database;	
			
		if($_POST)
		{
			$data = array(
				'name'		=> $_POST['name'],
				'view'		=> $_POST['view'],
				'params'	=> $_POST['params'],
			);
			$db->update('albums', $data, " id = '$tool_id' AND fk_site = '$this->site_id' ");
			echo 'Settings Saved!!<br>Updating ...';
		}
		else
		{
			$this->_view_edit_settings('album', $tool_id);
		}
		die();
	}


/*
 * DELETE image single
 * Success Response via JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of image row 
 */
	public function delete_image($id=NULL)
	{
		tool_ui::validate_id($id);		
		
		# Get image object
		$image = $this->_grab_tool_child('album', $id);

		# Image File delete
		$image_path	= "$this->site_data_dir/assets/images/albums/$image->parent_id/$image->path";
		$image_sm	= "$this->site_data_dir/assets/images/albums/$image->parent_id/sm_$image->path";
		unlink($image_path);
		unlink($image_sm);
		
		$this->_delete_single_common('album', $id);
		die();
	}

/*
 * SAVE images sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		$this->_save_sort_common($_GET['image'], 'album_items');
		die();	
	}
	
	
}
/* -- end of application/controllers/home.php -- */