

<!-- this is duplicated on both views -->
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
<!-- end duplicated -->

<div class="list_showroom">
	<?php
	foreach($items as $item):
	
		# images
		$images = json_decode($item->images);
		if(!empty($images) AND is_array($images))
		{
			# show the thumb of the first image in the album.
			# echo kohana::debug($images);
			$small = image::thumb($images[0]->path, 0);
			$image = "<img src=\"$img_path/$small\" alt=\"{$images[0]->caption}\">";
		}		
	?>		
		<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id;?>">
			<div class="item_name">
				<?php echo $item->name;?>
			</div>
			<div class="item_intro">
				<?php echo $item->intro?>
				<br/><br/>
				<a href="<?php echo url::site("$page_name/0$item->id/$item->url")?>" class="loader">More info</a>
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
