

<form action="/get/edit_faq/settings/<?php echo $tool_id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_settings" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/> Save Settings
		</button>
		<div id="common_title">Edit FAQ Settings</div>
	</div>	
	
	<div class="fieldsets">
		<b>Title Header</b><br>
		<input type="text" name="title" value="<?php echo $faq->title?>" style="width:400px">
	</div>

</form>