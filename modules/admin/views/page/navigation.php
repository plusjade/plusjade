
<span class="on_close">update_menu</span>

<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">Save Menu Order</button>
	<div id="common_title">Primary Navigation</div>
</div>

<div class="common_left_panel">
	Drag and sort links to update your primary navigation menu.
</div>

<div class="common_main_panel">
	<ul id="generic_sortable_list" class=" ui-tabs-nav">		
		<?php foreach($pages as $page):?>
			<li id="item_<?php echo $page->id?>"  class="root_entry">
				<ul class="row_wrapper">
					<li class="drag_box"><span class="icon move"> &#160; &#160; </span> DRAG </li>
					<li class="position"><?php echo $page->position?>. </li>
					<li class="data"><span><?php echo $page->label?></span>  - <small><?php echo url::site($page->page_name)?></small></li>
					<li class="delete_item"><span class="icon cross">&#160; &#160;</span> <a href="/get/page/delete/<?php echo $page->id?>" rel="<?php echo $page->id?>">delete</a></li>				
				</ul>
			</li>		
		<?php endforeach;?>
	</ul>
</div>

<script type="text/javascript">
	$('#generic_sortable_list').sortable({ 
		handle	: '.drag_box',
		axis	: 'y',
		containment: '.common_main_panel'
	});	
	
// Save page sort order
	$("#save_sort").click(function() {
		var order = $("#generic_sortable_list").sortable("serialize");
		if(!order){
			alert("No items to sort");
			return false;
		}
		$(".facebox .show_submit").show();
		$.get("/get/page/save_sort?"+order, function(data){
			$(document).trigger('server_response.plusjade', data);
		})				
	});
</script>
