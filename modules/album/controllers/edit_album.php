<?php defined('SYSPATH') OR die('No direct access allowed.');

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
			if(NULL === json_decode($_POST['images']))
				die('data is not properly formed JSON');
				
			$album->images = $_POST['images'];
			$album->save();
			die('Album saved');
		}
		
		# images	
		$images = json_decode($album->images);
		if(NULL === $images)
			$images = array();
		
		foreach($images as $image)
			$image->thumb = image::thumb($image->path);

		$primary = new View('edit_album/manage');
		$primary->album = $album;
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

		$view = new View('edit_album/settings');
		$view->album = $album;
		$view->js_rel_command = "update-album-$tool_id";			
		die($view);
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