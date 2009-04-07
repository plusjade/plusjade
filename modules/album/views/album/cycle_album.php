

<div id="album_cycle_title_<?php echo $album->id?>" class="album_cycle_title"></div>	
<div id="album_cycle_wrapper_<?php echo $album->id?>" class="album_cycle_wrapper" rel="<?php echo $album->id?>">
	<?php 
		foreach($images as $image)
		{
			echo '<img src="' . $img_path . '/' . $image->path . '" alt="' . $image->caption . '" />'."\n";
		}
	?>
</div>
