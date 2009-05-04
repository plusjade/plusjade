
<div id="common_module_header">
	Manage <b>Slide Panels</b>.
</div>

<div class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Panel Order
	</button>
</div>	

<ul id="generic_sortable_list" class="ui-tabs-nav">
	<?php
	$counter = 1;	
	foreach($items as $slide_panel)
	{
		$class = '';
		# if($item['enable'] == 'no') $class = 'class="not_enabled"';
		?>
		<li id="slide_panel_<?php echo $slide_panel->id?>" <?php echo $class?>>
			<table><tr>
				<td width="15px"><?php echo $slide_panel->position?>. </td>
				<td class="page_edit"><a href="/e/edit_slide_panel/edit/<?php echo $slide_panel->id?>" rel="facebox" id="<?php echo $slide_panel->id?>"><span><?php echo $slide_panel->title?></span></a></td>
				<td width="30px" class="center"><a href="/e/edit_slide_panel/delete/<?php echo $slide_panel->id?>" class="delete_slide_panel" id="<?php echo $slide_panel->id?>">Delete!</a></td>
				<td class="center" width="30px"><img src="/images/arrow.png" alt="handle" class="handle"></td>
			</tr></table>
		</li>		
		<?php
		++$counter;
	}
	?>		
</ul>

<script type="text/javascript">
	$("#generic_sortable_list").sortable({ handle : "img", axis : "y" });
	<?php 
		echo tool_ui::js_save_sort_init('slide_panel');
		echo tool_ui::js_delete_init('slide_panel');
	?>
</script>