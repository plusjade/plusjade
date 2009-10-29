
<span class="on_close">update-album-<?php echo $album->id?></span>

<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_album" class="jade_positive">Save Album</button>
	<div id="common_title">Manage Album</div>
</div>	

 <span class="icon images">&#160; &#160;</span> <a href="#" class="get_file_browser" rel="albums" title="Add images">Add Images</a> - 

<span class="icon cross">&#160; &#160;</span> <a href="#" id="remove_images">Remove Images</a>
	
<div id="sortable_images_wrapper" class="common_full_panel" style="height:350px; overflow:auto">	
	<?php foreach($images as $image):?>
		<div class="album_images">
			<span class="icon move">&#160; &#160;</span>
			<span class="icon cog">&#160; &#160;</span>
			<img src="<?php echo "$img_path/$image->thumb"?>" width="75px" height="75px" alt="<?php echo $image->path?>" title="<?php echo $image->caption?>">
		</div>
	<?php endforeach;?>
</div>

<span class="save_pane" style="display:none">
	<div class="contents">
		<span class="icon cross floatright">&#160; &#160;</span>
		
		<b>Caption</b>
		<br><input type="text" name="caption" maxlength="100" style="width:300px">
		<p>
			<button>Save Caption</button>
		</p>
		<b>**</b>You must save the album to save the captions
	</div>
</span>

<script type="text/javascript">

// make images sortable and selectable
	$("#sortable_images_wrapper").sortable({items:'div', handle:'span.move'});
	$("#sortable_images_wrapper").selectable({filter:'img', delay: 20});

// Save the album
	$('button#save_album').click(function() {
		// JSONize image selections
		var data = new Array();
		$('#sortable_images_wrapper img').each(function(){
			var img = new Object();
			img.path = $(this).attr('alt');
			img.caption = $(this).attr('title');
			data.push(img);
		});
		var dataString = $.toJSON(data); // alert(dataString);
		
		$(document).trigger('show_submit.plusjade');		
		$.post('/get/edit_album/manage/<?php echo $album->id?>', {images : dataString},
			function(data){
				$(document).trigger('server_response.plusjade', data);
			});
		return false;
	});	
	
</script>





