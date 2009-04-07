
<div id="album_lightbox_wrapper_<?php echo $album->id?>" class="album_wrapper album_lightbox_wrapper">
	<ul>
		<?php 
		foreach($images as $image)
		{
			?>
			<li>
				<a href="<?php echo $img_path.'/'.$image->path?>" title="<?php echo $image->caption?>">
					<img src="<?php echo $img_path.'/sm_'.$image->path?>" width="120px" alt="" />
				</a>
			</li>
			<?php
		}
		?>
	</ul>
		<div class="clearboth"></div>
</div>
