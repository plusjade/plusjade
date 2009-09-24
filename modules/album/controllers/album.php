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
		
		# images
		$images = json_decode($album->images);	
		
		if(NULL === $images OR !is_array($images))
			return $this->wrap_tool('no images.', 'album', $album);
			
		foreach($images as $image)
			$image->thumb = image::thumb($image->path);
		
		$display_view = (empty($album->view)) ? 'lightbox' :  $album->view;
		$primary = $this->$display_view($images);
		
		# add custom javascript;
		$primary->global_readyJS(self::javascripts($album));
		
		return $this->wrap_tool($primary, 'album', $album, $sub_tool);
	}

/*
 * a view for lightbox functionality
 */
	private function lightbox($images)
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
	private function gallery($images)
	{
		$view = new View('public_album/images/gallery');
		$view->images = $images;
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
					$js =  '
					$("#format_gallery_wrapper").galleryView({
						panel_width: 800,
						panel_height: 400,
						frame_width: 75,
						frame_height: 75,
						filmstrip_size: 3,
						overlay_height: 50,
						overlay_font_size: "1em",
						transition_speed: 400,
						transition_interval: 3000,
						overlay_opacity: 0.6,
						overlay_color: "black",
						background_color: "black",
						overlay_text_color: "white",
						caption_text_color: "white",
						border: "1px solid black",
						nav_theme: "light",
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
 * doesnt work need to update
 */
	private function cycle($images, $img_path)
	{
		die('offline');
		$primary = new View('public_album/images/cycle');
		# Javascript
		$primary->request_js_files('easing/jquery.easing.1.3.js');										
		$primary->request_js_files('cycle_lite/jquery.cycle.all.min.js');								

		$options = array(
			'fx'		=> '"scrollDown"',
			'speedIn'	=> '2000',
			'speedOut'	=> '500',
			'easeIn'	=> '"easeOutBounce"',
			'easeOut'	=> '"easeInBack"',
			
			'next'		=> '',
			'prev'		=> '',
			'before'	=> '',
			'after'		=> '',
			'height'	=> '',
			'sync'		=> '',
			'fit'		=> '',
			'pause'		=> '',
			'delay'		=> '-2000',
			'slideExpr'	=> '',
			'random'	=> '',
			'animOut'	=> '',
		);
					
		//function(){$("#cycle_title_'.$album->id.'").html(this.alt)},				


		$params = explode(':', $album->params);	

		$effect = '"SCROLL"';
		if(! empty($params['0']) )
			$effect = $params['0'];

		if( empty($params['1']) )
		{
			$effect = '
				{
					fx:"'.$params['0'].'",
					speed:2000,
					delay:-1000,
					sync: 1	
				}
			';
		}

		$initialize = "$('#cycle_wrapper_$album->id').cycle($effect);";

		$primary->global_readyJS('
			$.fn.cycle.transitions.TOSS = function($cont, $slides, opts) { 
				opts.fx			=	"toss";
				opts.easing		=	"easeOutExpo";
				opts.delay		=	-2000;
				opts.animOut	=  { top: 200, left: -300 };
			};	
			$.fn.cycle.transitions.SCROLL = function($cont, $slides, opts) { 
				opts.fx =      "scrollDown";
				opts.speedIn =  2000; 
				opts.speedOut = 500; 
				opts.easeIn =  "easeOutBounce";
				opts.easeOut = "easeInBack";
				opts.before = function(){$("#cycle_title_'.$album->id.'").html(this.alt)}; // transition callback (scope set to element to be shown) 

			};
			'.$initialize.'
		');	
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