
<span id="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_faq/settings/<?php echo $tool_id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_settings" class="jade_positive" accesskey="enter">Save Settings</button>
		<div id="common_title">Edit FAQ Settings</div>
	</div>	
	
	<div class="fieldsets">
		<b>Title Header</b>
		<br><input type="text" name="title" value="<?php echo $faq->title?>" style="width:400px">
	</div>
	<p style="line-height:1.6em">
		A title header displays at the top of your FAQ list between <b>&lt;h2 class="faq_header"&gt; &lt;/h2&gt;</b> tags.
		<br>
		<br>Customize the look by editing this tool's CSS file.  Look for the "faq_title" class =).
 	</p>

</form>