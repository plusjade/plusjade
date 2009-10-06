
<div class="album_gallery_wrapper">	
	<div id="format_gallery_wrapper" class="galleryview">
		<?php if($has_panels):?>
			<?php foreach($images as $image):?>
				<div class="panel">
					<img src="<?php echo "$img_path/$image->path"?>" /> 
					<div class="panel-overlay">
						<?php echo $image->caption?>
					</div>
				</div>
			<?php endforeach;?>
		<?php endif;?>
		
		<?php if($has_filmstrip):?>
			<ul class="filmstrip">
				<?php foreach($images as $image):?>
					<li><img src="<?php echo "$img_path/$image->thumb"?>" alt="" /></li>
				<?php endforeach;?>
			</ul>
		<?php endif;?>
	</div>
</div>



