

<form action="/get/edit_blog/settings/<?php echo $tool_id?>" method="POST" class="ajaxForm" rel="<?php echo $js_rel_command?>">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_settings" class="jade_positive" accesskey="enter">
			<img src="<?php echo url::image_path('admin/check.png')?>" alt=""/> Save Settings
		</button>
		<div id="common_title">Edit Blog Settings</div>
	</div>	
	
	<div class="fieldsets">
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
		
	</div>

</form>