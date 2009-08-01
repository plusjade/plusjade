<?php
class Album_Controller extends Public_Tool_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

/*
 * Display an album instance
 */	
	function _index($tool_id)
	{
		$album = ORM::factory('album')
			->where('fk_site', $this->site_id)
			->find($tool_id);	
		if(FALSE === $album->loaded)
			return $this->public_template('album error, please contact support', 'album', $tool_id);

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
		
		$primary = new View('public_album/index');
		$display_view = (empty($album->view)) ? 'lightbox' :  $album->view;
		$primary->display_view = $this->$display_view($images);
		$primary->view_name = $display_view;
		$primary->add_root_js_files("$display_view/$display_view.js");
		return $this->public_template($primary, 'album', $tool_id, $album->attributes);
	}

/*
 * a view for lightbox functionality
 */
	private function lightbox($images)
	{
		$primary = new View('public_album/lightbox');
		$primary->images = $images;
		$primary->img_path = $this->assets->assets_url();
		return $primary;
	}


/* 
 * doesnt work need to update
 */
	private function cycle($images, $img_path)
	{
		die('offline');
		$primary = new View('public_album/cycle');
		# Javascript
		$primary->add_root_js_files('easing/jquery.easing.1.3.js');										
		$primary->add_root_js_files('cycle_lite/jquery.cycle.all.min.js');								

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
 * doesnt work need to update
 */
	private function galleria($images, $img_path)
	{
		die('offline');
		$primary = new View('public_album/galleria');
		$primary->add_root_js_files('galleria/galleria.js');
		$primary->global_readyJS('									
			// $("#galleria_'.$album->id .'").addClass("gallery_demo"); // adds new class name to maintain degradability
			// $(".nav").css("display","none"); // hides the nav initially
			
			$("ul#galleria_'.$album->id .'").galleria({
				history   : false, 
				clickNext : true, 
				insert    : "#main_container_'. $album->id .'",
				onImage   : function(){$("#placeholder_image_'.$album->id .'").remove();}
				
				//onImage   : function() { $(".nav").css("display","block"); } // shows the nav when the image is showing
				
			});
		');	
	}
	
/*
 * which function to go after album is created?
 */	
	public static function _tool_adder($tool_id, $site_id)
	{
		return 'manage';
	}
	
	
	
} /* -- end -- */