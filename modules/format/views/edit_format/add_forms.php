
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/add/<?php echo $tool_id?>" method="POST" class="ajaxForm">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Add Field</button>
		<div id="common_title">Add New Form Field</div>
	</div>	
	
	need type, title, extra instructions, is required?
	<div class="common_left_panel fieldsets">
		<b>Type</b>
		<select name="type">
			<option value="input">text input</option>
			<option value="textarea">textarea</option>
			<option value="select">select</option>
			<option value="radio">radio</option>
		</select>
		
		<br/><br/>		
		<b>Title</b>
		<br><input type="text" name="title" rel="text_req">	
	</div>
	
	<div class="common_main_panel">	
		<textarea name="body" class="render_html"></textarea>
	</div>
	
</form>