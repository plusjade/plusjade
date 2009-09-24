
<span class="on_close">update-album-<?php echo $album->id?></span>

<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_album" class="jade_positive">Save Album</button>
	<div id="common_title">Manage Album</div>
</div>	

<div class="common_left_panel aligncenter">
	<a href="#" class="get_file_browser images" rel="albums" title="Add images">&#160; &#160;</a>
	
	<div id="image_trash"></div>
	<div><b>Drag images to Trash</b></div>
	<br><a href="#" id="remove_images">Remove Selected images</a>
</div>

<div id="sortable_images_wrapper" class="common_main_panel" style="height:350px; overflow:auto">	
	<?php foreach($images as $image):?>
		<div class="album_images">
			<span class="handle"><b>edit</b> <em>drag</em></span>
			<img src="<?php echo "$img_path/$image->thumb"?>" alt="<?php echo $image->path?>" title="<?php echo $image->caption?>">
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

// Load image album user interface functions.	
<?php include Kohana::find_file('views', 'javascripts/image_album_ux', FALSE, 'js');?>
	

// Save the album
	$('button#save_album').click(function(){
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





