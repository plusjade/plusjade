


<ul class="album_lightbox">
	<?php 
	foreach($images as $image)
	{
		?>
		<li>
			<a href="<?php echo "$img_path/$image->path"?>" title="<?php echo $image->caption?>">
				<img src="<?php echo "$img_path/_sm/$image->path"?>" width="120px" alt="" />
			</a>
		</li>
		<?php
	}
	?>
</ul>
<div class="clearboth"></div>
		