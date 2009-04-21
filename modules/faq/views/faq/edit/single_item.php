

<form action="/get/edit_faq/edit/<?php echo $item->id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_changes" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit FAQ</div>
	</div>	
	
	<div class="fieldsets">
		<b>Question</b><br>
		<input type="text" name="question" value="<?php echo $item->question?>" size="50">
		
		<br><b>Answer</b>	
	</div>
	<textarea name="answer" class="render_html"><?php echo $item->answer?></textarea>
	
</form>