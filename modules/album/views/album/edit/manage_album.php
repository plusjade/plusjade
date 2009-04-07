
<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="/images/check.png" alt=""/> Save Image Order
	</button>
	<div id="common_title">Manage <b>Album:</b> "<?php echo $album->name?>"</div>
</div>	

<ul id="sortable_images_wrapper">
	<?php
		foreach($items as $image)
		{
			echo '<li id="image_'.$image->id.'"><div><img src="'.$data_path.'/assets/images/albums/' . $album->id . '/sm_' . $image->path . '" id="' . $image->id . '" width="120px""></div>';
			echo '<a href="/get/edit_album/delete_image/' . $image->id .'" class="delete_image" id="'.$image->id.'">[x]</a> <a href="/e/edit_album/edit_item/'.$image->id.'" rel="facebox" class="load_image" id="'.$image->id.'" >edit</a></li>'."\n";
		}				
	?>
</ul>	
