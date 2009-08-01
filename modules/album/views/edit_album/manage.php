
<span class="on_close">update-album-<?php echo $album->id?></span>

<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_album" class="jade_positive">Save Album</button>
	<div id="common_title">Manage Album</div>
</div>	

<div class="common_left_panel">
	<a href="#" class="get_file_browser" rel="albums">Add Images</a>
	<br>
	<br><a href="#" id="remove_images">Remove Selected</a>
	<p>
		<b>Click</b> or <b>click+drag</b>
		<br>to select images.
	</p>
	<div id="select-result">
	
	</div>
</div>
<div id="sortable_images_wrapper" class="common_main_panel" style="height:350px; overflow:auto">	
	<?php
	foreach($images as $data)
	{
		$data = explode('|', $data);
		?>
		<div>
			<span>drag</span>
			<img src="<?php echo "$img_path/$data[0]"?>" title="<?php echo $data['1']?>" alt="<?php echo $data['1']?>">	
		</div>
		<?php
	}
	?>
</div>

<script type="text/javascript">
	
	$("#sortable_images_wrapper").sortable({items:'div', handle:'span'});
	$("#sortable_images_wrapper").selectable({		
		filter:'img',
		stop: function(){
			var result = $("#select-result").empty();
			$(".ui-selected:first", this).each(function() {
				var id = $(this).attr('rel');
				var actions ='<p><a href="get/edit_album/edit_item/'+ id +'" class="edit_image" rel="facebox" id="2">Edit this</a></p>';
				result.append(actions);
				$(this).clone().prependTo(result);
			});	
		}
	});

// make space droppable.	
	$("#sortable_images_wrapper").droppable({
		activeClass: 'ui-state-highlight',
		accept: 'img.image_file',
		drop: function(event, ui) {
			$(ui.draggable).addClass('selected');
			$(ui.draggable).parent('div').addClass('selected');		
			
			$('<div></div>')
			.prepend('<span>drag</span>')
			.append($(ui.draggable ).clone())
			.prependTo(this);
			return false;
		}
	});
	

// Save the album
	$('button#save_album').click(function(){
		// the order should matter too.
		var output = '';
		$('#sortable_images_wrapper img').each(function(i){
			output += $(this).attr('alt') + '|';
		});
		$('.facebox .show_submit').show();
		$.post('/get/edit_album/manage/<?php echo $album->id?>', {images : output},
			function(data){
				$(document).trigger('server_response.plusjade', data);
			});
		return false;
	});	
</script>