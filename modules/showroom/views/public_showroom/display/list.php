
<div class="list_showroom">
	<?php
	foreach($items as $item):
	
		# images
		$images = json_decode($item->images);
		if(!empty($images) AND is_array($images))
		{
			#show the thumb of the first image in the album.
			#echo kohana::debug($images);
			$small = image::thumb($images[0]->path);
			$image = "<img src=\"$img_path/$small\" alt=\"{$images[0]->caption}\">";
		}		
	?>		
		<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id;?>">
			<div class="item_name"><?php echo $item->name;?></div>
			<div class="item_body">
				<a href="<?php echo url::site("$page_name/$category/$item->url")?>" class="loader">More info</a><br>
				<?php echo $item->intro?>
				<?php echo $item->body?>
			</div>
			<?php if(isset($image)):?>
				<div class="item_image"><?php echo $image?></div>
			<?php endif;?>
			
		</div>
		
	<?php 
		unset($image);
	endforeach;
	?>
</div>
