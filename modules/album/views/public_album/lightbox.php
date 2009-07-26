
<ul class="album_lightbox">
	<?php 
	foreach($images as $data)
	{
		$data = explode('|', $data);
		?>
		<li>
			<a href="<?php echo "$img_path/$data[1]"?>" title="caption goes here">
				<img src="<?php echo "$img_path/$data[0]"?>" alt="" />
			</a>
		</li>
		<?php
	}
	?>
</ul>
<div class="clearboth"></div>
		