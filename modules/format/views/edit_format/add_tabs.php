
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/add?pid=<?php echo $this->pid?>" method="POST" class="ajaxForm">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Add Tab</button>
		<div id="common_title">Add New Tab</div>
	</div>	
	
	<div class="common_left_panel fieldsets">
		<b>Tab Label</b>
		<br><input type="text" name="title" rel="text_req">	
	</div>
	
	<div class="common_main_panel">	
		<textarea name="body" class="render_html"></textarea>
	</div>
	
</form>