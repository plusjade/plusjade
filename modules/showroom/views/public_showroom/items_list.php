
<div class="list_showroom">
<?php
	foreach($items as $item)
	{
		$image = '';
		$img_path = Assets::assets_url();
		if(! empty($item->images))
		{
			if(0 < substr_count($item->images, '/'))
			{
				$filename = strrchr($item->images, '/');
				$small = str_replace($filename, "/_sm$filename", $item->images);
			}
			else
				$small = "/_sm/$item->images";
			
			$image = "<img src=\"$img_path/$small\" alt=\"pic\">";
		}		
?>		
		<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id;?>">
			<div class="item_name"><?php echo $item->name;?></div>
			<div class="item_body">
				<a href="<?php echo url::site("$page_name/$category/$item->url")?>" class="loader">More info</a><br>
				<?php echo $item->intro?>
				<?php echo $item->body?>
			</div>
			<div class="item_image"><?php echo $image?></div>
		</div>
		
<?php
	}
?>
</div>
