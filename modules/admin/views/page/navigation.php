
<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/assets/images/check.png" alt=""/> Save Menu Order
	</button>
	<div id="common_title">Primary Navigation</div>
</div>

<div class="common_left_panel">
	Drag and sort links to update your primary navigation menu.
</div>

<div class="common_main_panel">
	<ul id="generic_sortable_list" class=" ui-tabs-nav">
		<?php
		foreach($pages as $page)
		{	
			?>
			<li id="page_<?php echo $page->id?>">
				<table id="menu_page_list"><tr>
					<td width="60px" class="drag_box"> <span class="icon move"> &#160; &#160; </span> DRAG </td>
					<td width="30px" class="aligncenter"><?php echo $page->position?>. </td>
					<td class="page_edit"><?php echo $page->label?> - <small><?php echo url::site($page->page_name)?></small></td>
				</tr></table>
			</li>		
			<?php
		}
		?>
	</ul>
</div>

<script type="text/javascript">
	$('#generic_sortable_list').sortable({ 
		handle	: '.drag_box',
		axis	: 'y',
		containment: '#generic_sortable_list'
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
			$.facebox.close();
			$('#MAIN_MENU').html('<b>Updating...</b>').load('/get/page/load_menu');
			$('#show_response_beta').html(data);				
		})				
	});
</script>
