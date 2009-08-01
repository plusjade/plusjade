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
		
		$album = ORM::factory('album')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $album->loaded)
			die('invalid album');
			
		if($_POST)
		{
			$album->images = trim($_POST['images'], '|');
			$album->save();
			die('Album saved');
		}

		$primary = new View('edit_album/manage');
		$primary->album = $album;
		
		# images 
		$image_array = explode('|', $album->images);
		$images = array();
		if(!empty($image_array['0']))	
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
		$primary->img_path = $this->assets->assets_url();
		die($primary);
	}

	
/*
 * Edit_image 
 * currently no way to access this since images are no longer db items
 */
	public function edit_item($id=NULL)
	{
		die('offline');
		valid::id_key($id);
	}

/*
 * EDIT album settings
 */
	public function settings($tool_id=NULL)
	{
		die('album settings is temporarily disabled while we update our code. Thanks!');
		
		valid::id_key($tool_id);		
		
		$album = ORM::factory('album')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $album->loaded)
			die('invalid album');
			
		if($_POST)
		{
			$album->name = $_POST['name'];
			$album->view = $_POST['view'];
			$album->params = $_POST['params'];
			$album->save();
			die('Album Settings Saved.');
		}

		$primary = new View('edit_album/settings');
		$primary->tool = $album;
		$primary->js_rel_command = "update-album-$tool_id";			
		die($primary);
	}


	
/*
 * nothing
 */
	public static function _tool_deleter($tool_id, $site_id)
	{
		return TRUE;
	}	
}
/* -- end of application/controllers/home.php -- */