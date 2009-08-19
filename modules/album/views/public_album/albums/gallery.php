

<div class="album_gallery_wrapper">	
	<div id="format_gallery_wrapper" class="galleryview">
		<?php foreach($images as $image):?>
			<div class="panel">
				<img src="<?php echo "$img_path/$image->path"?>" /> 
				<div class="panel-overlay">
					<?php echo $image->caption?>
				</div>
			</div>
			<?php endforeach;?>
		<ul class="filmstrip">
			<?php foreach($images as $image):?>
				<li><img src="<?php echo "$img_path/$image->thumb"?>" alt="" /></li>
			<?php endforeach;?>
		</ul>
	</div>
</div>



