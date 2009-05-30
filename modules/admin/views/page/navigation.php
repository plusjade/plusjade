
<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/assets/images/check.png" alt=""/> Save Menu Order
	</button>
	<div id="common_title">Primary Navigation</div>
</div>

<div id="common_tool_info">
	Drag and sort links to update your primary navigation menu.
</div>

<ul id="generic_sortable_list" class="ui-tabs-nav">
	<?php
	foreach($pages as $page)
	{	
		?>
		<li id="page_<?php echo $page->id?>">
			<table id="menu_page_list"><tr>
				<td width="80px" class="drag_box"><img src="<?php echo url::image_path('arrow.png')?>" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo $page->position?>. </td>
				<td class="page_edit"><a href="<?php echo url::site($page->page_name)?>"><?php echo $page->label?> - <small><?php echo url::site($page->page_name)?></small></a></td>
			</tr></table>
		</li>		
		<?php
	}
	?>
</ul>

<script type="text/javascript">
	$("#container-1").tabs({ fx: { opacity: "toggle",duration: "fast"} });
	
	$("#generic_sortable_list").sortable({ 
		handle	: ".handle",
		axis	: "y"
	});	
	<?php echo tool_ui::js_save_sort_init('page', 'page')?>	
</script>
