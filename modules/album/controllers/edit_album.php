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
		valid::id_key($tool_id);
		die( $this->_view_manage_tool_items('album', $tool_id) );
	}

	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		die( $this->_view_add_single('album', $tool_id) );
	}
	
/*
 * ADD images into album (multiple) 
 * Loads into tier 1 Facebox
 * @PARM (INT) $page_id = page id (table pages) album is installed
 */
	public function add_image($tool_id=NULL)
	{
		# TODO: TEST to make sure all names are unique. 
		# Cant have htem rewrite eachother.
		valid::id_key($tool_id);
			
		# validate for file size 5M and type (images)
		if( empty($_FILES['Filedata']['type']) OR ( $_FILES['Filedata']['type'] > 50000 ) )
			die('Invalid File');

		# NOTE:: IS THIS SECURE??
		# Work-around maintaining the session because Flash Player doesn't send the cookies
		if(isset($_POST["PHPSESSID"]))
			session_id($_POST["PHPSESSID"]);
			
		$db = new Database;	
		
		# Get highest position
		$get_highest = $db->query("
			SELECT MAX(position) as highest 
			FROM album_items 
			WHERE parent_id = '$tool_id'
			AND fk_site = '$this->site_id'
		")->current()->highest;

		# Setup image store directory
		$image_store = DOCROOT . "data/$this->site_name/assets/images/albums";			
		if(!is_dir($image_store))
			mkdir($image_store);

		if(! is_dir($image_store.'/'.$tool_id) )
			mkdir($image_store.'/'.$tool_id);		

		$tmp_name	= $_FILES['Filedata']['tmp_name'];			
		$holder		= array ('tmp_name' => $tmp_name);
		$filename	= upload::save($holder);
		$image		= new Image($filename);			
		$ext		= $image->__get('ext');
		$token		= text::random('alnum', 18);
		$name		= "$token.$ext";
		

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
				$image_sm->resize(100,100,Image::HEIGHT)->crop(100,100);
			else
				$image_sm->resize(100,100,Image::WIDTH)->crop(100,100);
			
			$image_sm->save("$image_store/$tool_id/sm_$name");
		}
		unlink($filename);
		die('Images added!!<br>Updating...');	
	}
	
	
# Edit_image 
	public function edit_item($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;		

		if(! empty($_POST['parent_id']) )
		{			
			$data = array(
				'caption'	=> $_POST['caption'],
			);
			$db->update('album_items', $data, array( 'id' => $id, 'fk_site' => $this->site_id) );
			die('Image Updated!');
		}
		else
		{
			die($this->_view_edit_single('album', $id));
		}
	}

/*
 * EDIT album settings
 * Loads into tier 1 facebox
 * [see root JS in this::manage() ]
 * @PARM (INT) $tool_id = id of the parent tool item
 */
	public function settings($tool_id=NULL)
	{
		valid::id_key($tool_id);		
		if($_POST)
		{
			$db = new Database;	
			$data = array(
				'name'		=> $_POST['name'],
				'view'		=> $_POST['view'],
				'params'	=> $_POST['params'],
			);
			$db->update('albums', $data, " id = '$tool_id' AND fk_site = '$this->site_id' ");
			die('Settings Saved!!<br>Updating ...');
		}
		else
		{
			die( $this->_view_edit_settings('album', $tool_id) );
		}
	}


/*
 * DELETE image single
 * Success Response via JGrowl
 * [see root JS in this::manage() ]
 * @PARM (INT) $id = id of image row 
 */
	public function delete_image($id_string=NULL)
	{
		$id_string = trim($id_string, '-');
		$id_array = explode('-', $id_string);
		foreach($id_array as $key => $id)
			if(! is_numeric($id) )
				unset($id_array[$key]);

		if( '0' == count($id_array) )
			die('invalid input');
			
		$id_string = implode(',',$id_array);
		
		foreach($id_array as $id)
		{
			# Get image object
			$image = $this->_grab_tool_child('album', $id);

			# Image File delete
			$image_path	= "$this->site_data_dir/assets/images/albums/$image->parent_id/$image->path";
			$image_sm	= "$this->site_data_dir/assets/images/albums/$image->parent_id/sm_$image->path";
			if( file_exists($image_path) )
				unlink($image_path);
			if( file_exists($image_sm) )
				unlink($image_sm);
		}
		
		$db = new Database;
		$db->query("
			DELETE FROM album_items
			WHERE id IN ($id_string)
			AND fk_site = '$this->site_id'
		");
		die('images deleted');
	}

/*
 * SAVE images sort order
 * Success Response via Facebox_response tier 2
 * [see root JS in this::manage() ]
 */
	public function save_sort()
	{
		if( empty($_GET['image']) )
			die('No items to sort');
			
		die( $this->_save_sort_common($_GET['image'], 'album_items') );
	}
	
	static function _tool_adder($tool_id, $site_id)
	{
		return 'add';
	}
	
/*
 * delete all the images relating to this page and its folder
 */
	function _tool_deleter($tool_id, $site_id)
	{
		$album_dir = DATAPATH . "$this->site_name/assets/images/albums/$tool_id";
		if(is_dir($album_dir))
		{
			$d = dir($album_dir); 
			while($file = $d->read())
			{
				 if('.' != $file && '..' != $file)
					unlink("$album_dir/$file"); 
			} 
			$d->close(); 
			rmdir($album_dir);
		}
	}	
}
/* -- end of application/controllers/home.php -- */