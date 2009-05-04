
<div  id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Item Order
	</button>
	<strong>Manage <b>Showroom</b> tool.</strong>
</div>	
	
<ul id="generic_sortable_list" class="ui-tabs-nav">
	<?php
	$counter = 1;	
	foreach($items as $item)
	{
		$class = '';
		# if($item['enable'] == 'no') $class = 'class="not_enabled"';
		?>
		<li id="showroom_<?php echo $item->id?>" <?php echo $class?>>
			<table><tr>
				<td width="80px" class="drag_box"><img src="/images/arrow.png" alt="handle" class="handle"></td>
				<td width="30px" class="aligncenter"><?php echo $item->position?>. </td>
				<td class="page_edit"><a href="/e/edit_showroom/edit/<?php echo $item->id?>" rel="facebox" class="secondary" id="<?php echo $item->id?>"><span><?php echo $item->name?></span></a></td>
				<td width="60px" class="alignright"><a href="/e/edit_showroom/delete/<?php echo $item->id?>" class="delete_showroom" id="<?php echo $item->id?>">Delete!</a></td>
			</tr></table>
		</li>		
		<?php
		++$counter;
	}
	?>		
</ul>