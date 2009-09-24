
<div class="breadcrumb">
	<a href="<?php echo url::site("$page_name/$category")?>" class="loader"><?php echo $category?></a>
	&#8594; <?php echo $item->url?>
</div>

<div class="single_item showroom_item" rel="<?php echo $item->id?>">

	<div class="single_name">
		<? echo $item->name?>
	</div>
	
	<div class="single_intro">
		<? echo $item->intro?>
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
				$path = image::thumb($image->path);
				$image = "<img src=\"$img_path/$path\" alt=\"$image->caption\">";
			
				echo $image;
			
			}
		
		}
		?>
	</div>

	<div class="aligncenter">
		<b>Link to this item:</b> <input type="text" value="<?php echo url::site("$page_name/$category/$item->url")?>" style="width:80%">
	</div>	
</div>