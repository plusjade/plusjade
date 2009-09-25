
<h1><?php echo $category->name?></h1>
<?php
	$counter = 0;
	foreach($items as $item)
	{
		# images
		$images = json_decode($item->images);
		if(!empty($images) AND is_array($images))
		{
			# show the thumb of the first image in the album.
			$small = image::thumb($images[0]->path);
			$image = "<img src=\"$img_path/$small\" alt=\"{$images[0]->caption}\">";
		}	
		?>	
		<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id?>">
			<div class="item_image"><a href="<?php echo "$img_path/{$images[0]->path}"?>" title="<?php echo $item->intro?>"><?php echo $image?></a></div>
			<div class="item_name"><?php echo $item->name?></div>
			<div class="item_intro"><?php echo $item->intro;?></div>
		</div>
		
		<?php
		++$counter;
		if(($counter % $columns) == 0)
			echo "<div class=\"clearboth\"></div>\n";
	}
?>
<div class="clearboth"></div>	


<script type="text/javascript">
	$('div.item_image a').lightBox();
</script>












