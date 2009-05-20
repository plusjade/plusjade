
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
	# setup the page list.
	foreach($pages as $page)
	{
		$class='';
		$page_name = $page->page_name;
		if($page->menu == 'no') $class = 'class="no_menu"';
		if($page->enable == 'no') $class = 'class="no_access"';	
		
		?>
		<li id="page_<?php echo $page->id?>" <?php echo $class?>>
			<table id="menu_page_list"><tr>
				<td width="80px" class="drag_box"><img src="<?php echo url::image_path('arrow.png')?>" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo $page->position?>. </td>
				<td width="20px" class="aligncenter"> 
				<?php if(array_key_exists($page_name, $protected_pages)) echo "<img src='".url::image_path('admin/shield.png')."' title='$protected_pages[$page_name]' alt='' >"?>
				</td>
				<td class="page_edit"><a href="<?php echo url::site($page_name)?>"><?php echo $page->label?> - <small><?php echo url::site($page->page_name)?></small></a></td>
				<td class="alignright" width="50px"><a href="/get/page/delete/<?php echo $page->id?>" class="delete_page" id="<?php echo $page->id?>">delete</a></td>
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
	
	<?php
		echo tool_ui::js_delete_init('page');
		echo tool_ui::js_save_sort_init('page', 'page');
	?>	
</script>
