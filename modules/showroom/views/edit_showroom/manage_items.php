
<div  id="common_tool_header" class="buttons">
	<div id="common_title">Items in Category: </div>
</div>	

<div id="load_box" style="min-height:400px">
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
					<td class="page_edit"><a href="/get/edit_showroom/edit/<?php echo $item->id?>" rel="facebox" class="secondary" id="<?php echo $item->id?>"><?php echo $item->name?></a></td>
					<td width="60px" class="alignright"><a href="/get/edit_showroom/delete/<?php echo $item->id?>" class="delete_showroom" id="<?php echo $item->id?>">Delete!</a></td>
				</tr></table>
			</li>		
			<?php
			++$counter;
		}
		?>		
	</ul>	
</div>

<script type="text/javascript">
	$("#generic_sortable_list").sortable({handle:'.handle'});
	// should have javascript::save_sort() here...
	
	$("#admin_cat_dropdown").change(function(){
		val = $("option:selected", this).val();
		$("#load_box").load("get/edit_showroom/list_items/"+val);
	})
</script>