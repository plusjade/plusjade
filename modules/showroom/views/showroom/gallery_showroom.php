<div class="showroom_wrapper gallery_showroom">
	<?php
		$counter = 0;
		foreach($items as $item)
		{
			if(!empty($item->image))
				$image = '<img src="'."$data_path/assets/images/showroom/$item->image".'" alt="'.$item->name.'">';
			else
				$image = '';
			?>	
			<div class="showroom_item" rel="<?php echo $item->id;?>">
				<div class="gallery_item_name"><?php echo $item->name;?></div>	
				<div class="gallery_item_image"><a href="<?php echo url::site("$page_name/$item->id")?>"><?php echo $image?></a></div>
				<div class="gallery_item_intro"><?php echo $item->intro;?></div>
			</div>
			
			<?php
			++$counter;
			if (($counter % 2) == 0)
				echo '<div class="clearboth"></div>'."\n";
		}
	?>
	<div class="clearboth"></div>	
</div>
