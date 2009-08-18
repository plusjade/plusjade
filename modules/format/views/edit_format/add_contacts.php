
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/add/<?php echo $tool_id?>" method="POST" class="ajaxForm">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Add Contact</button>
		<div id="common_title">Add New Contact</div>
	</div>	
	
	<div class="common_left_panel fieldsets">
		<b>Contact Name</b>
		<br><input type="text" name="title" rel="text_req">	
	</div>
	
	<div class="common_main_panel">	
		<textarea name="body" class="render_html"></textarea>
	</div>
	
</form>