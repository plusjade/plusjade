
<div id="album_wrapper_<?php echo $album->id?>" class="album_wrapper" rel="<?php echo $album->id?>">
	
	<div id="cycle_wrapper_<?php echo $album->id?>" class="cycle_wrapper" rel="<?php echo $album->id?>">
			
		<?php 
			foreach($images as $image)
			{
				echo '<img src="' , $img_path , '/' , $image->path , '" alt="' , $image->caption , "\" />\n";
			}
		?>
	
	</div>
	
	<div id="cycle_title_<?php echo $album->id?>" class="clearboth">

	</div>	
</div>