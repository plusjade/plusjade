<div class="showroom_wrapper list_showroom">
<?php
	foreach($items as $item)
	{
		if(!empty($item->image))
			$image = '<img src="'."$data_path/assets/images/showroom/$item->image".'" alt="pic">';
		else
			$image = '';
?>		<div class="showroom_item" rel="<?php echo $item->id;?>">
			<div class="item_name"><?php echo $item->name;?></div>
			<div class="item_body">
				<?php echo $item->intro;?>
				<br><a href="/<?php echo  $page_name . '/' . $item->id?>">more info</a>
			</div>
			<div class="item_image"><?php echo $image?></div>
		</div>
		
<?php
	}
?>
</div>
