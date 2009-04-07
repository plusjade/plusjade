
<div id="common_tool_header" class="buttons">
	<a href="/get/page/add" rel="facebox" id="add_page" class="jade_positive" style="float:left">Add New Page</a>	
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Menu Order
	</button>
</div>

<div id="common_tool_info">
	Load pages for editing by clicking on the page name link.
</div>

<ul id="generic_sortable_list" class="ui-tabs-nav">
	<?php
	# setup the page list.
	foreach($menu_items as $item)
	{
		$class='';
		if($item->enable == 'no') $class = 'class="not_enabled"';
		?>
		<li id="page_<?php echo $item->id?>" <?php echo $class?>>
			<table id="menu_page_list"><tr>
				<td width="80px" class="drag_box"><img src="/images/arrow.png" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo $item->position?>. </td>
				<td class="page_edit"><a href="<?php echo url::site($item->page_name)?>"><?php echo $item->display_name?> - <small><?php echo url::site($item->page_name)?></small></a></td>
				<td class="alignright" width="50px"><a href="/get/page/delete/<?php echo $item->page_id .'/'.$item->id?>" class="delete_page" id="<?php echo $item->id?>">delete</a></td>
			</tr></table>
		</li>		
		<?php
	}
	?>
</ul>

