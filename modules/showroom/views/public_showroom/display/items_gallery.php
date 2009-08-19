
<div class="gallery_showroom">
	<?php
		$counter = 0;
		foreach($items as $item)
		{
			$image = (empty($item->img)) ?
				'' : $image = '<img src="'."$img_path/_sm/$item->img".'" class="loader" alt="'.$item->url.'">';
			?>	
			<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id?>">
				<div class="item_name"><?php echo $item->name?></div>	
				<div class="item_image"><a href="<?php echo url::site("$page_name/$category/$item->url")?>"><?php echo $image?></a></div>
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
