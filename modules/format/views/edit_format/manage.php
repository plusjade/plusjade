
<span class="on_close">update-format-<?php echo $this->pid?></span>

<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">Save Order</button>
	<div id="common_title">Arrange Tugboat Items</div>
</div>

<div class="common_left_panel">

</div>

<div class="common_main_panel">
	<ul id="generic_sortable_list" class="ui-tabs-nav">
		<?php foreach($items as $item):?>
			<li id="item_<?php echo $item->id?>" class="root_entry">
				<ul class="row_wrapper">
					<li class="drag_box"><span class="icon move"> &#160; &#160; </span> DRAG </li>
					<li class="position"><?php echo $item->position?>. </li>
					<li class="data"><?php echo $item->title?></li>
					<li class="delete_item"><span class="icon cross">&#160; &#160;</span> <a href="/get/edit_format/delete/<?php echo $item->id?>" rel="<?php echo $item->id?>">delete</a></li>
				</ul>
			</li>		
		<?php endforeach;?>
	</ul>
</div>

<script type="text/javascript">
	$('#generic_sortable_list').sortable({handle:'.drag_box', axis: 'y'});
	<?php echo javascript::save_sort('format')?>
</script>