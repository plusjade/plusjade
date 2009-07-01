
<span id="on_close">update-faq-<?php echo $tool_id?></span>

<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">Save Order</button>
	<div id="common_title">Arrange Questions</div>
</div>

<ul id="generic_sortable_list" class="ui-tabs-nav">
	<?php
	$counter = 0;
	foreach($items as $item)
	{
		$class='';
		?>
		<li id="faq_<?php echo $item->id?>" <?php echo $class?>>
			<table id="menu_page_list"><tr>
				<td width="80px" class="drag_box"><img src="<?php echo url::image_path('arrow.png')?>" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo ++$counter?>. </td>
				<td class="page_edit"><?php echo $item->question?></td>
			</tr></table>
		</li>		
		<?php
	}
	?>
</ul>

<script type="text/javascript">
	$('#generic_sortable_list').sortable({handle:'.handle', axis: 'y'});
	<?php echo javascript::save_sort('faq', $tool_id)?>
</script>