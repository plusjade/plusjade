
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_blog/settings/<?php echo $tool->id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_settings" class="jade_positive" accesskey="enter">Save Settings</button>
		<div id="common_title">Edit Blog Settings</div>
	</div>	
	
	<div class="common_left_panel fieldsets">
	
	</div>
	
	<div class="common_main_panel fieldsets">
		<b>Ajax</b> 
		<select>
			<option>Enable Ajax</option>
			<option>Disable Ajax</option>
		</select>
		
		<br>
		<br>
		<b>Comments</b> 
		<select>
			<option>Enable Comments</option>
			<option>Disable Comments</option>
		</select>		
		<br>
		<br>
		
		<b>Posts Per Page</b>  
		<select>
			<option>1</option>
			<option>5</option>
			<option>10</option>
		</select>		
		
		<p>Stick Posts</p>
	</div>

</form>