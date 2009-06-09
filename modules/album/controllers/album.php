<?php
class Album_Controller extends Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	/*
	 * Display an album instance
	 */	
	function _index($tool_id)
	{
		$db = new Database;
		
		# Get album
		$album	= $db->query("
			SELECT * FROM albums 
			WHERE id = '$tool_id' 
			AND fk_site = '$this->site_id'
		")->current();
		if(! is_object($album) )
			die('album does not exist');
			
		# Get images in album
		$images = $db->query("
			SELECT * FROM album_items 
			WHERE parent_id = '$album->id' 
			ORDER BY position
		");
		if( '0' == $images->count() )
			return $this->public_template('(no images)', 'album', $tool_id);

		$display_view = (empty($album->view)) ? 'lightbox' :  $album->view;
		$img_path = 'http://' . ROOTDOMAIN . "/data/$this->site_name/assets/images/albums/$album->id";  

		$primary = new View('public_album/index');
		$primary->display_view = $this->$display_view($images, $img_path);
		$primary->view_name = $display_view;

		return $this->public_template($primary, 'album', $tool_id);
	}

	private function lightbox($images, $img_path)
	{
		$primary = new View('public_album/lightbox');
		$primary->images = $images;
		$primary->img_path = $img_path;
		$primary->add_root_js_files('lightbox/lightbox.js');
		return $primary;
	}


	/* doesnt work need to update */
	private function cycle($images, $img_path)
	{
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
	
	/* doesnt work need to update */
	private function galleria($images, $img_path)
	{
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
}

/* -- end of application/modules/album.php -- */