<?php
class Edit_Album_Controller extends Edit_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * manage an image album
 */
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		$db = new Database;	
		
		if($_POST)
		{
			$data = array(
				'images'	=> trim($_POST['images'], '|'),
			);
			$db->update('albums', $data, " id = '$tool_id' AND fk_site = '$this->site_id' ");
			die('Album saved');
		}
		
		$album = $db->query("
			SELECT * 
			FROM albums
			WHERE id = '$tool_id'
			AND fk_site = '$this->site_id'
		")->current();
		
		$primary = new View('edit_album/manage');
		$primary->album = $album;
		
		# images 
		$image_array = explode('|', $album->images);
		$images = array();		
		foreach($image_array as $image)
		{
			if(0 < substr_count($image, '/'))
			{
				$filename = strrchr($image, '/');
				$small = str_replace($filename, "/_sm$filename", $image);
			}
			else
				$small = "/_sm/$image";
			
			$images[] = "$small|$image";
		}
		$primary->images = $images;	
		die($primary);
	}

	
/*
 * Edit_image 
 * currently no way to access this since images are no longer db items
 */
	public function edit_item($id=NULL)
	{
		valid::id_key($id);
		$db = new Database;		

		if(! empty($_POST['parent_id']) )
		{			
			$data = array(
				'caption'	=> $_POST['caption']
			);
			$db->update('album_items', $data, array( 'id' => $id, 'fk_site' => $this->site_id) );
			die('Image updated');
		}
		die($this->_view_edit_single('album', $id));
	}

/*
 * EDIT album settings
 */
	public function settings($tool_id=NULL)
	{
		die('album settings is temporarily disabled while we update our code. Thanks!');
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
			die('Album Settings Saved.');
		}

		die( $this->_view_edit_settings('album', $tool_id) );
	}

/*
 * which function to go after album is created?
 */	
	static function _tool_adder($tool_id, $site_id)
	{
		return 'manage';
	}
	
/*
 * delete all the images relating to this page and its folder
 */
	function _tool_deleter($tool_id, $site_id)
	{
		/*
		$album_dir = Assets::assets_dir("tools/albums/$tool_id");
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
		*/
	}	
}
/* -- end of application/controllers/home.php -- */