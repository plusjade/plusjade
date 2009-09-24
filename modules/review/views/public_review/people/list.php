


<div id="format_people_wrapper">		
	<?php foreach($format->format_items as $item):?>
		<div id="format_item_<?php echo $item->id?>" class="person format_item" rel="<?php echo $item->id?>">
			<div class="portrait">
				<img src="<?php echo "$img_path/$item->image"?>" width="200px" height="225px" alt="">
				<br><?php echo $item->title?>
			</div>
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