
<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Order
	</button>
</div>

<ul id="generic_sortable_list" class="ui-tabs-nav" style="min-height:320px">
	<?php
	$counter = 0;
	foreach($items as $item)
	{
		$class='';
		?>
		<li id="faq_<?php echo $item->id?>" <?php echo $class?>>
			<table id="menu_page_list"><tr>
				<td width="80px" class="drag_box"><img src="/images/arrow.png" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo ++$counter?>. </td>
				<td class="page_edit"><a href="/get/edit_faq/edit/<?php echo $item->id?>" rel="facebox" id="2"><?php echo $item->question?></a></td>
				<td class="alignright" width="50px"><a href="/get/edit_faq/delete/<?php echo $item->id?>" class="delete_page" id="<?php echo $item->id?>">delete</a></td>
			</tr></table>
		</li>		
		<?php
	}
	?>
</ul>

<script type="text/javascript">
	$("#generic_sortable_list").sortable({handle:".handle"});
	<?php 
		echo tool_ui::js_save_sort_init('faq');
	?>
</script>