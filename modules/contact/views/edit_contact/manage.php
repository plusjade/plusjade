
<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">Save Order</button>
	<div id="common_title">Rearrange Contact Order</div>	
</div>

<div class="common_left_panel">
	Click and hold the "drag" handle to re-position
	contact items.
</div>

<div class="common_main_panel">
	<ul id="generic_sortable_list" class="ui-tabs-nav">
		<?php
		foreach($items as $contact)
		{
			$class = (($contact->enable == 'no')) ? 'class="not_enabled"' : '';
			?>
			<li id="contact_<?php echo $contact->id?>" <?php echo $class?>> 
				<table><tr>
					<td width="60px" class="drag_box"> <span class="icon move"> &#160; &#160; </span> DRAG </td>
					<td width="30px" class="aligncenter"><?php echo $contact->position?>. </td>
					<td class="page_edit"><span><?php echo $contact->type?></span></td>
				</tr></table>
			</li>		
			<?php
		}
		?>
	</ul>	
</div>

<script type="text/javascript">
	$("#generic_sortable_list").sortable({
		handle : ".drag_box",
		axis : "y",
		containment : '#generic_sortable_list'
	});	
	<?php echo javascript::save_sort('contact', $tool_id)?>
</script>