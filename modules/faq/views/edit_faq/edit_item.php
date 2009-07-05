
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_faq/edit/<?php echo $item->id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_changes" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit FAQ</div>
	</div>	

	<div class="common_left_panel fieldsets">
		<b>Question</b>
		<br><input type="text" name="question" value="<?php echo $item->question?>" rel="text_req">	
	</div>
	
	<div class="common_main_panel">	
		<textarea name="answer" class="render_html"><?php echo $item->answer?></textarea>
	</div>

</form>