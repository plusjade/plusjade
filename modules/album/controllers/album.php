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
		$album	= $db->query("SELECT * FROM albums WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();
		$display_view = $album->view;
		$album_id = $album->id;
		
		if( empty($display_view) )
			$display_view = 'galleria';
			
		# Get images in album
		$images = $db->query("SELECT * FROM album_items WHERE parent_id = '$album_id' ORDER BY position");
		

		if($images->count() > 0)
		{
			# Load View based on album
			$primary = new View("album/{$display_view}_album");

			# Pass album to view
			$primary->album = $album;
			
			# Pass images to view
			$primary->images = $images;
			
			$primary->img_path = 'http://' . ROOTDOMAIN . "/data/{$this->site_name}" . '/assets/images/albums/' . $album->id;  
			
		
			switch($display_view)
			{
				case 'galleria':
					# Javascript
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
				break;
				
				case 'cycle':		
					# Javascript
					$primary->add_root_js_files('cycle_lite/cycle_lite.js');								
					$primary->global_readyJS('
						$("#album_cycle_wrapper_'.$album->id.'").cycle({
							delay: -1000,
							speed:  1000, 
							before: function(){$("#cycle_title_'.$album->id.'").html(this.alt);}
						});   
					');
				break;
				
				case 'lightbox':
					# Javascript
					$primary->add_root_js_files('lightbox/lightbox.js');				
					$primary->global_readyJS("		
						$(function() {
							$('.jade_album_lightbox a').lightBox();
						});
					");
				break;	
				
				default:		
					# Javascript (lightbox)
					$primary->add_root_js_files('lightbox/lightbox.js');					
					$primary->global_readyJS("		
						$(function() {
							$('.jade_album_lightbox a').lightBox();
						});
					");
				break;
			}
		}
		else
		{
			$primary = new View('empty');
		}
		
		/*
		 * TODO GET THIS SHIT OUT OF HERE!!!
		 * SHOULD BE IN EDIT CONTROLLER.
		 */
		# Load stuff for edit website mode
		if($this->client->logged_in() AND $this->client->get_user()->client_site_id == $this->site_id )
		{	
			# Javascript		
			$primary->add_root_js_files('multi_form/MultiFile.pack.js');
		}

		
		return $primary;
	}
}

/* -- end of application/modules/album.php -- */