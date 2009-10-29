<?php defined('SYSPATH') OR die('No direct access allowed.');

class Edit_Album_Controller extends Edit_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
/*
 * manage an image album
 */
	public function manage($parent_id=NULL)
	{
		valid::id_key($parent_id);
		
		$album = ORM::factory('album')
			->where('fk_site', $this->site_id)
			->find($parent_id);	
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
	public function settings($parent_id=NULL)
	{
		valid::id_key($parent_id);		
		
		$album = ORM::factory('album')
			->where('fk_site', $this->site_id)
			->find($parent_id);	
		if(!$album->loaded)
			die('invalid album');
			
		if($_POST)
		{
			$album->name = $_POST['name'];
			$album->view = $_POST['view'];
			$album->toggle = $_POST['toggle'];
			$album->params = $_POST['params'];
			$album->save();
			die('Album Settings Saved.');
		}
		
		
		$gallery_params = array(
			'panel_width' => 660,
			'panel_height' => 250,
			'frame_width' => 75,
			'frame_height' => 75,
			'filmstrip_size' => 3,
			'overlay_height' => 50,
			'transition_speed' => 400,
			'transition_interval' => 2000,
			'overlay_opacity' => 0.6,
			'easing' => "swing",
			'filmstrip_position' => "bottom",
			'overlay_position' => "bottom",
			'show_captions' => 'false',
			'fade_panels' => 'true',
			'pause_on_hover' => 'true'
		);
		
		$params = json_decode($album->params);
		if(NULL == $params OR !is_object($params))
			$params = (object) $gallery_params;
		
		$view = new View('edit_album/settings');
		$view->album = $album;
		$view->params = $params;
		$view->js_rel_command = "update-album-$parent_id";			
		die($view);
	}


}
/* -- end of application/controllers/home.php -- */