<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * STATIC helpers that work with the client "tool" interface
 * Includes:
 *	Validating user input, clean ajax calls, user responses, etc
 *
 */
class tool_ui_Core {

#Javascript helpers
#--------------
	/*
	 *	Javascript enabling sort items save
	 */ 
	static function js_save_sort_init($module, $url =NULL, $list_id = 'generic_sortable_list')
	{
		if (empty($url))
			$url = 'edit_'.$module;
			
		$javascript = '
			// Save item sort order
			$("#save_sort").click(function() { 		
				var order = $("#'.$list_id.'").sortable("serialize");
				$.facebox(function() {
						$.get("/get/'.$url.'/save_sort?"+order, function(data){
							$.facebox(data, "ajax_status", "facebox_response");
							location.reload();
						})
					}, 
					"ajax_status", 
					"facebox_response"
				); 
			});
		';
		return $javascript;
	
	}

	/*
	 *	Javascript enabling delete item link
	 * Name = parent module name this item belongs to
	 */ 
	static function js_delete_init($name)
	{
		$javascript = '
			$(".delete_'.$name.'").click(function() {
				if (confirm("This cannot be undone! Delete this item?")) {
					id = this.id;
					url = $(this).attr("href");
					$.get(url, function(){
						$("#'.$name.'_"+id).remove();
					});
				}
				return false;
			});		
		';
		return $javascript;
	}
	
} // End tool_ui_Core