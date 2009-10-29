<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * Display an album instance
 */	
 
class Album_Controller extends Public_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

/*
 * expects parent album table object
 */	
	public function _index($album, $sub_tool=FALSE)
	{		
		# this is a hack for allowed sub_tools only.
		if(!is_object($album))
		{
			$album = ORM::factory('album')
				->where('fk_site', $this->site_id)
				->find($album);	
			if(FALSE === $album->loaded)
				return $this->wrap_tool('album error, please contact support', 'album', $album);	
		}
		
		# set the thumbnail size USES TOGGLE:
		$thumb_size = (isset($album->toggle) AND is_numeric($album->toggle))
			? $album->toggle
			: 75;
			
		# images
		$images = json_decode($album->images);	
				
		if(NULL === $images OR !is_array($images))
			return $this->wrap_tool('no images.', 'album', $album);
			
		foreach($images as $image)
			$image->thumb = image::thumb($image->path, $thumb_size);
		
		$display_view = (empty($album->view)) ? 'lightbox' :  $album->view;
		$primary = $this->$display_view($album, $images);
		
		# add custom javascript;
		$primary->global_readyJS(self::javascripts($album));
		
		return $this->wrap_tool($primary, 'album', $album, $sub_tool);
	}

/*
 * a view for lightbox functionality
 */
	private function lightbox($album, $images)
	{
		$view = new View('public_album/images/lightbox');
		$view->images = $images;
		$view->img_path = $this->assets->assets_url();
		
		# request javascript file
		$view->request_js_files('lightbox/lightbox.js');
		
		return $view;
	}

	
/*
 * a view for gallery functionality
 */
	private function gallery($album, $images)
	{
		$view = new View('public_album/images/gallery');
		$view->images = $images;
		
		# toggle the gallery elements 
		$toggle = explode('|', $album->toggle);
		$view->has_panels = ('no' == $toggle[0]) ? FALSE : TRUE;
		$view->has_filmstrip = (!empty($toggle[1]) AND 'no' == $toggle[1]) ? FALSE : TRUE;
		$view->img_path = $this->assets->assets_url();

		# request javascript file
		$view->request_js_files('gallery/gallery.js');
		
		return $view;
	}

/*
 * output the appropriate javascript based on the album view.
 */	
	private function javascripts($album)
	{
		# prepare the javascript
		switch($album->view)
		{
			case 'lightbox':
					$js = "$('.album_wrapper div.album_lightbox_wrapper a').lightBox();";
				break;
				
			case 'gallery':
			
					# get the paramaters
					$params = json_decode($album->params);
					if(Null !== $params AND is_object($params))
					{
						$js = '$("#format_gallery_wrapper").galleryView({';
						foreach($params as $key => $value)
							if(is_numeric($value) OR $value == 'true' OR $value == 'false')
								$js .= "\n$key:$value,";
							else
								$js .= "\n$key:'$value',";	
						$js .= "nav_theme : 'light'});";
					}
					else
						$js = '
						$("#format_gallery_wrapper").galleryView({
							panel_width: 660,
							panel_height: 250,
							frame_width: 75,
							frame_height: 75,
							filmstrip_size: 3,
							overlay_height: 50,
							transition_speed: 400,
							transition_interval: 2000,
							overlay_opacity: 0.6,
							easing: "swing",
							filmstrip_position: "bottom",
							overlay_position: "bottom",
							show_captions: false,
							fade_panels: true,
							pause_on_hover: true
						});
						';
				break;
				
			default:
				$js = '';
		}
		
		# place the javascript.
		return $this->place_javascript($js, TRUE);
	}

	
/*
 * execute stuff after new album is created.
 */	
	public static function _tool_adder($tool_id, $site_id, $sample=FALSE)
	{
		if($sample)
		{
			$album = ORM::factory('album', $tool_id);
			$album->fk_site	= $site_id;
			$album->name	= 'My Photo Album';
			$album->view	= 'lightbox';
			# as JSON
			$album->images	= '[
				{
					"path": "images/sunflower.jpg",
					"caption": "a sunflower"
				},
				{
					"path": "images/sun.jpg",
					"caption": "a very cool looking sun"
				},
				{
					"path": "images/goose.jpg",
					"caption": "a goose"
				},
				{
					"path": "images/lens.jpg",
					"caption": "a techy camera lens"
				},
				{
					"path": "images/sand-castle.jpg",
					"caption": "a tall sand castle"
				}
			]';
			$album->save();
		}
		
		return 'manage';
	}
	
	
	
} /* -- end -- */