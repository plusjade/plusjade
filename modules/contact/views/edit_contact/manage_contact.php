
<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Order
	</button>
	<strong>Edit <b>Contact</b> Tool.</strong>	
</div>
	
<ul id="generic_sortable_list" class="ui-tabs-nav">
	<?php
	foreach($items as $contact)
	{
		$class = '';
		if($contact->enable == 'no') $class = 'class="not_enabled"';
		?>
		<li id="contact_<?php echo $contact->id?>" <?php echo $class?>> 
			<table><tr>
				<td width="80px" class="drag_box"><img src="<?php echo url::image_path('arrow.png')?>" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo $contact->position?>. </td>
				<td class="page_edit"><a href="/get/edit_contact/edit/<?php echo $contact->id?>" rel="facebox" id="<?php echo $contact->id?>"><span><?php echo $contact->type?></span></a></td>
				<td width="60px" class="alignright"><a href="/get/edit_contact/delete/<?php echo $contact->id?>" class="delete_contact" id="<?php echo $contact->id?>">Delete!</a></td>
			</tr></table>
		</li>		
		<?php
	}
	?>
</ul>	

<script type="text/javascript">
	$("#generic_sortable_list").sortable({ handle : ".handle", axis : "y" });	
	<?php	
		echo javascript::save_sort('contact');
		echo javascript::delete_item('contact');
	?>
</script>