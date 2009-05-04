

<form action="/get/edit_faq/add/<?php echo $tool_id?>" method="POST" class="ajaxForm" style="min-height:300px;">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Question
		</button>
		<div id="common_title">Add New Question</div>
	</div>	
	
	<div class="fieldsets">
		<b>Question</b><br>
		<input type="text" name="question" value="" size="50">
		
		<br><b>Answer</b>	
	</div>
	<textarea name="answer" class="render_html"></textarea>
	
</form>