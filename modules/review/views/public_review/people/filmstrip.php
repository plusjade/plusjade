

<div id="format_people_filmstrip">
	<?php
		foreach($format->format_items as $item):
			$thumb = image::thumb($item->image);
	?>
	<div class="people_thumb">
		<a href="#format_item_<?php echo $item->id?>" rel="<?php echo $item->id?>">
			<img src="<?php echo "$img_path/$item->image"?>" alt="">
		</a>
		<br><?php echo $item->title?>
	</div>
	<?php endforeach;?>
</div>


<div id="format_filmstrip_wrapper">		
	<?php foreach($format->format_items as $item):?>
		<div id="format_item_<?php echo $item->id?>" class="person format_item" rel="<?php echo $item->id?>">
			<div class="body">
				<?php echo $item->body?>
				<br>
				<?php
					if(!empty($item->album))
						echo Load_Tool::factory('album')->_index($item->album, TRUE);
				?>
			</div>
		</div>
	<?php endforeach;?>
</div>


