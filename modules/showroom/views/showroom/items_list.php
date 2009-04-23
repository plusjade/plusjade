<div class="list_showroom">
<?php
	foreach($items as $item)
	{
		$image = '';
		if(!empty($item->img))
			$image = '<img src="'."$img_path/$item->img".'" alt="pic">';

			
?>		<div id="showroom_item_<?php echo $item->id?>" class="showroom_item" rel="<?php echo $item->id;?>">
			<div class="item_name"><?php echo $item->name;?></div>
			<div class="item_body">
				<a href="/showroom/<?php echo "$category/$item->url"?>" class="loader">More info</a><br>
				<?php echo $item->intro?>
				<?php echo $item->body?>
			</div>
			<div class="item_image"><?php echo $image?></div>
		</div>
		
<?php
	}
?>
</div>
