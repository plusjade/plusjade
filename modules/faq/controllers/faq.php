<?php
class Faq_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}
  
	function _index($tool_id)
	{
		$db = new Database;
		$primary = new View("faq/index");
		
		# get parent 
		$parent = $db->query("SELECT * FROM faqs 
			WHERE id = '$tool_id' AND fk_site = '$this->site_id'
		")->current();	

		# get faq items
		$items = $db->query("SELECT * FROM faq_items 
			WHERE parent_id = '$tool_id' AND fk_site = '$this->site_id'
			ORDER BY position
		");
		
		$primary->parent = $parent;	
		$primary->items = $items;	
		# Javascript
		$embed_js = '
			$("#faq_wrapper_'. $parent->id .' dd.faq_answer").hide();
			
			// add open/close icons
			$("#faq_wrapper_'. $parent->id .' dt.faq_item a.toggle").click(function(){				
				iconClass = $(this).siblings("span").attr("class");
				
				if("minus" == iconClass) iconClass = "plus";
				else iconClass = "minus";
					
				img = "<img src=\"/images/public/"+ iconClass +".png\" alt=\"\">";
					
				$(this).siblings("span").removeClass().addClass(iconClass).html(img);
				$(this).parent("dt").next("dd.faq_answer").slideToggle("fast");
				return false;
			});
		';
		
		#$primary->add_root_js_files('accordion/accordion.js');
		$primary->global_readyJS($embed_js);

		
		return $primary;	
	}
}

/* -- end of application/controllers/faq.php -- */