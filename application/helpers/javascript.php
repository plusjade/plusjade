<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * output reusable javascript code
 *
 */
class javascript_Core {

#Javascript helpers
#--------------
	/*
	 *	Javascript enabling sort items save
	 */ 
	static function save_sort($toolname, $tool_id, $url =NULL, $list_id = 'generic_sortable_list')
	{
		$toolname = strtolower($toolname);
		if (empty($url))
			$url = 'edit_'.$toolname;
			
		$javascript = '
			// Save item sort order
			$("#save_sort").click(function() {
				var order = $("#'. $list_id .'").sortable("serialize");
				if(!order){
					alert("No items to sort");
					return false;
				}
				$(".facebox .show_submit").show();
				$.get("/get/'. $url .'/save_sort?"+order, function(data){
					$.facebox.close();
					$().jade_update_tool_html("update", "'.$toolname.'", "'.$tool_id.'", data);	
				})				
			});
		';
		return $javascript;
	
	}

	/*
	 * Javascript enabling delete item link
	 * Name = parent module name this item belongs to
	 */ 
	static function delete_item($name)
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
	
}