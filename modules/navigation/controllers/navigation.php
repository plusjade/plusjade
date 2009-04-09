<?php

class Navigation_Controller extends Controller {

	function __construct()
	{
		parent::__construct();
	}

	function _index($tool_id)
	{	
		tool_ui::validate_id($tool_id);	
		$primary = new View('navigation/index');	
		
		$db = new Database;
		$parent = $db->query("SELECT * FROM navigations WHERE id = '$tool_id' AND fk_site = '$this->site_id' ")->current();
		$items = $db->query("SELECT * FROM navigation_items WHERE parent_id = '$parent->id' AND fk_site = '$this->site_id' ORDER BY lft ASC ");		
		
		$primary->add_root_js_files('simple_tree/jquery.simple.tree.js');
		//$primary->items = $items;
		
		$primary->global_readyJS('
		
			$simpleTreeCollection = $(".simpleTree").simpleTree({
				autoclose: true,
				animate:true
			});
						

			

			$("#link_save_sort").click(function() {
				var output = "";
				
				$(".navigation_wrapper ul").each(function(){
					var parentId = $(this).parent().attr("rel");
					if(!parentId) parentId = 0;
					var $kids = $(this).children("li:not(.root, .line,.line-last)");
					
					// Data set format: "id:local_parent_id:position#"
					$kids.each(function(i){
						output += $(this).attr("rel") + ":" + parentId + ":" + i + "#";
					});
				});
				
				//alert (output); return false;
				
				
				$.facebox(function() {
						$.post("/get/edit_navigation/save_sort", {output: output}, function(data){
							$.facebox(data, "ajax_status", "facebox_response");
							location.reload();
						})
					}, 
					"ajax_status", 
					"facebox_response"
				);

				
			});		
		
		');
		
		return  Navigation::display_tree($items);
		
		# return $primary;
	}
  
}

/* -- end of application/controllers/showroom.php -- */