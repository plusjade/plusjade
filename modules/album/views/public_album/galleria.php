<?php
	$layout = 'left';
	if(! empty($album->params) )
		$layout = $album->params;

?>

<div id="album_galleria_wrapper_<?php echo $album->id?>">

	<div id="main_container_<?php echo $album->id?>" class="main_image_<?php echo $layout?>">
		<?php /* <img id="placeholder_image_<?php echo $album->id?>" src="<?php  echo $img_path.'/'.$images->current()->path?>">  */?>
	</div>


	<div class="galleria_thumbs_<?php echo $layout?>">
		<ul id="galleria_<?php echo $album->id?>" class="galleria">
			<?php 

				foreach($images as $image)
				{
					echo '<li><a href="'.$data_path.'/assets/images/albums/'.$album->id.'/'.$image->path.'"><img src="'.$data_path.'/assets/images/albums/'.$album->id.'/'.$image->path.'" class="noscale" alt="' . $image->caption . '" title="' . $image->caption . '" /></a></li>'."\n";
				}
			?>
		</ul>	
	</div>

</div>


<div class="clearboth"></div>
<p class="nav" style="display:none;"><a href="#" onclick="$.galleria.prev(); return false;">previous</a> | <a href="#" onclick="$.galleria.next(); return false;">next</a></p>


