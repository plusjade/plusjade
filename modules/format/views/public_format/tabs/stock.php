


<h2 class="tabs_header"><?php echo $format->name?></h2> 

<ul class="tabs_tab_list">
	<?php foreach($format->format_items as $item):
		$url_title = valid::filter_php_url($item->title);
	?>
		<li><a href="#format_item_<?php echo $item->id?>"><?php echo $item->title?></a></li>
	<?php endforeach;?>
</ul>


<div class="tabs_content_wrapper">
	<?php foreach($format->format_items as $item):
		$url_title = valid::filter_php_url($item->title);
	?>
		<div id="format_item_<?php echo $item->id?>" class="format_item" rel="<?php echo $item->id?>">
			<?php echo $item->body?>
		</div>
	<?php endforeach;?>
</div>



