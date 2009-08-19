

<div class="album_lightbox_wrapper">
	<ul class="album_lightbox">
		<?php foreach($images as $image):?>
			<li>
				<a href="<?php echo "$img_path/$image->path"?>" title="<?php echo $image->caption?>">
					<img src="<?php echo "$img_path/$image->thumb"?>" alt="" />
				</a>
			</li>
		<?php endforeach;?>
	</ul>
	<div class="clearboth"></div>
</div>	