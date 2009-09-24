
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/edit/<?php echo $item->id?>" method="POST" class="ajaxForm">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Person</div>
	</div>	
	
	<div class="common_left_panel">
		<ul id="common_view_toggle" class="ui-tabs-nav">
			<li><a href="#people_profile" class="selected"><b>Profile</b></span></a><li>
			<li><a href="#people_body"><b>Description</b></span></a><li>
		</ul>
		
		<br>
		<a href="#" class="get_file_browser images" rel="albums" title="Choose Image">&#160; &#160;</a>
	</div>
	
	<div class="common_main_panel">
		<div id="people_profile" class="toggle fieldsets">	
			<b>Name</b>
			<br><input type="text" name="title" value="<?php echo $item->title?>" rel="text_req" style="width:400px">
			
			<br><br>
			<b>Drop Image Here</b>
			<div id="portrait_droppable_wrapper" style="border:1px solid #ccc; background:#eee; height:250px; width:99%; overflow:auto">
				<img src="<?php echo "$img_path/$item->image"?>" alt="portrait image">
			</div>
			
			<input type="hidden" name="image" value="<?php echo $item->image?>">
		</div>
		
		<div id="people_body" class="toggle fieldsets">
			<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
		</div>
	</div>
	
</form>

<script type="text/javascript">

	$('div.toggle').hide();
	$('div#people_profile').show();
	
// make space droppable.
	$("#portrait_droppable_wrapper").droppable({
		activeClass: 'ui-state-highlight',
		accept: 'img.image_file',
		drop: function(event, ui) {
			$(this).empty();
			$(ui.draggable).addClass('selected');
			$(ui.draggable).parent('div').addClass('selected');		
			
			var img_path = $(ui.draggable).attr('rel');
			var img = new Image();
			img.src = img_path;
			var html ='<img src="'+ img_path +'" class="selected" rel="'+ img_path +'" alt="">';
			$('#portrait_droppable_wrapper').prepend(html);
			$('#people_profile input[name="image"]').val($(ui.draggable).attr('alt'));
		}
	});
	
</script>