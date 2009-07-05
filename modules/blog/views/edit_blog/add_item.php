
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_blog/add/<?php echo $tool_id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Add Post</button>
		<div id="common_title">Add New Blog Post</div>
	</div>	
	
	<div class="common_left_panel fieldsets">
		<b>Status</b>
		<br><select name="status">
			<option>draft</option>
			<option selected="selected">publish</option>
		</select>
		<br>
		<br>
		<b>Tags</b>
		<br><input type="text" name="tags" style="width:150px">
	</div>	

	
	<div class="common_main_panel fieldsets">
		<div class="inputs">
			<b>Title</b> <input type="text" name="title" rel="text_req">
		</div>
		
		<div class="inputs">
			<b>Url</b> <input type="text" name="url" rel="text_req">
		</div>
		<textarea name="body" class="render_html"></textarea>
	</div>
</form>

<script type="text/javascript">
	$("input[name='title']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
		$("input[name='url']").val(input);
	});
	$("input[name='url']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
		$(this).val(input);
	});
</script>