<div style="width:750px">

	<div id="common_tool_header" class="buttons">
		<button type="submit" id="save_sort" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Image Order
		</button>
		<div id="common_title">Manage <b>Album:</b></div>
	</div>	

	<ul id="sortable_images_wrapper">
		<?php
			foreach($items as $image)
			{
				?>
				<li id="image_<?php echo $image->id?>">
					<div>
						<img src="<?php echo url::site()?>data/<?php echo $this->site_name?>/assets/images/albums/<?php echo $album->id?>/sm_<?php echo $image->path?>" id="<?php echo $image->id?>" width="120px">
					</div>
					<a href="/get/edit_album/delete_image/<?php echo $image->id?>" class="delete_image" id="<?php echo $image->id?>">[x]</a> 
					<a href="/get/edit_album/edit_item/<?php echo $image->id?>" rel="facebox" class="load_image" id="<?php echo $image->id?>">edit</a>	
				</li>
				<?php
			}				
		?>
	</ul>
	
</div>

<script type="text/javascript">
	$("#sortable_images_wrapper").sortable({ handle : "img" });
	<?php
		echo javascript::save_sort('album', $album->id, NULL, 'sortable_images_wrapper');
		echo javascript::delete_item('image');
	?>
</script>