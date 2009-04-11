
<div id="common_tool_header" class="buttons">
	<a href="/get/page/add" rel="facebox" id="add_page" class="jade_positive" style="float:left">Add New Page</a>	
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Menu Order
	</button>
</div>

<div id="common_tool_info">
	Load pages for editing by clicking on the page name link.
	<br><b style="color:#ccc">Gray</b> links are accessible but not on the menu.
	<br><b style="color:red">Red</b> links are not publicly accessible.
</div>

<ul id="generic_sortable_list" class="ui-tabs-nav">
	<?php
	# setup the page list.
	foreach($pages as $page)
	{
		$class='';
		if($page->menu == 'no') $class = 'class="no_menu"';
		if($page->enable == 'no') $class = 'class="no_access"';
		?>
		<li id="page_<?php echo $page->id?>" <?php echo $class?>>
			<table id="menu_page_list"><tr>
				<td width="80px" class="drag_box"><img src="/images/arrow.png" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo $page->position?>. </td>
				<td class="page_edit"><a href="<?php echo url::site($page->page_name)?>"><?php echo $page->label?> - <small><?php echo url::site($page->page_name)?></small></a></td>
				<td class="alignright" width="50px"><a href="/get/page/delete/<?php echo $page->id?>" class="delete_page" id="<?php echo $page->id?>">delete</a></td>
			</tr></table>
		</li>		
		<?php
	}
	?>
</ul>

