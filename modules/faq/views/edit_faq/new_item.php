

<form action="/get/edit_faq/add/<?php echo $tool_id?>" method="POST" class="ajaxForm" rel="<?php echo $js_rel_command?>">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Add Question</button>
		<div id="common_title">Add New Question</div>
	</div>	
	
	<div class="common_left_panel fieldsets">
		<b>Question</b>
		<br><input type="text" name="question" rel="text_req">	
	</div>
	
	<div class="common_main_panel">	
		<textarea name="answer" class="render_html"></textarea>
	</div>
	
</form>