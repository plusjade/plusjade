
<div class="showroom_breadcrumb">
	<b>You are in:</b> <a href="<?php echo url::site($page_name)?>"><?php echo $page_name?></a> &#8594; 
<?php
	if(0 < $path->count())
		foreach($path as $cat):?>
			<a href="<?php echo url::site("$page_name/$cat->id/$cat->url")?>" class="loader"><?php echo $cat->name?></a> &#8594; 
		<?php endforeach;?>
	<a href="<?php echo url::site("$page_name/$category->id/$category->url")?>" class="loader"><?php echo $category->name?></a>
</div>

<?php
	if(!empty($sub_categories))
		echo "<div><b>Filter By Sub Category</b>$sub_categories</div>";
?>

<div class="category_header">
	<b>Showing <?php echo $category->name?></b>
	 <small>(<?php echo $items->count()?> results)</small>
</div>

<div class="showroom_items_wrapper">
	<?php
	$counter = 0;
	foreach($items as $item)
	{
		# images
		$images = json_decode($item->images);
		# echo kohana::debug($images);
		if(!empty($images) AND is_array($images))
		{
			# show the thumb of the first image in the album.
			$full_path = $images[0]->path;
			$thumb = image::thumb($full_path, $thumb_size);
			$image = "<img src=\"$img_path/$thumb\" alt=\"{$images[0]->caption}\">";
		}	
		?>	
		<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id?>">
			<?php if(isset($image)):?>
				<div class="item_image"><a href="<?php echo "$img_path/$full_path"?>" title="<?php echo $item->intro?>"><?php echo $image?></a></div>
			<?php endif;?>
			<div class="item_name"><?php echo $item->name?></div>
			<div class="item_intro"><?php echo $item->intro;?></div>
		</div>
		
		<?php
		++$counter;
		if(($counter % $columns) == 0)
			echo "<div class=\"clearboth\"></div>\n";
	}
?>
	</div>
<div class="clearboth"></div>	


<script type="text/javascript">
	$('div.item_image a').lightBox();
</script>












