<div id="showroom_wrapper_PARENT_ID" class="showroom_wrapper gallery_showroom">
	<?php
		$counter = 0;
		foreach($items as $item)
		{
			$image = '';
			if(!empty($item->img))
				$image = '<img src="'."$data_path/assets/images/showroom/$item->img".'" alt="'.$item->name.'">';
				
			?>	
			<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id?>">
				<div class="item_name"><?php echo $item->name?></div>	
				<div class="item_image"><a href="<?php echo url::site("$page_name/$item->id")?>"><?php echo $image?></a></div>
				<div class="item_intro"><?php echo $item->intro;?></div>
			</div>
			
			<?php
			++$counter;
			if (($counter % 2) == 0)
				echo '<div class="clearboth"></div>'."\n";
		}
	?>
	<div class="clearboth"></div>	
</div>
