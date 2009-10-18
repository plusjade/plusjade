
<div class="showroom_breadcrumb">
	<b>You are in:</b> <a href="<?php echo url::site($page_name)?>"><?php echo $page_name?></a> &#8594; 
<?php
	if(0 < $path->count())
		foreach($path as $cat):?>
			<a href="<?php echo url::site("$page_name/$cat->id/$cat->url")?>" class="loader"><?php echo $cat->name?></a> &#8594; 
		<?php endforeach;?>
	<a href="<?php echo url::site("$page_name/$category->id/$category->url")?>" class="loader"><?php echo $category->name?></a> &#8594; 
	 <?php echo $item->name?>
</div>


<div class="single_item showroom_item" rel="<?php echo $item->id?>">

	<div class="single_name">
		<? echo $item->name?>
	</div>
	
	<div class="single_link">
		<b>Link to this item:</b> <input type="text" value="<?php echo url::site("$page_name/0$item->id/$item->url")?>" style="width:80%">
	</div>	
	
	<div class="single_body">
		<? echo $item->body?>
	</div>

	<div class="single_image">
		<?php
		
		# images
		$images = json_decode($item->images);
		if(!empty($images) AND is_array($images))
		{
			foreach($images as $image)
			{
				$path = image::thumb($image->path, 0);
				$img = "<img src=\"$img_path/$path\" alt=\"$image->caption\">";
				echo "$img";
				echo "<span>$image->caption</span>";
			}
		}
		?>
	</div>

</div>