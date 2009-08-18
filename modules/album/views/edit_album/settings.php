

<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_album/settings/<?php echo $album->id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_settings" class="jade_positive" accesskey="enter">Save Settings</button>
		<div id="common_title">Edit Album Settings</div>
	</div>	
	
	<div class="common_left_panel">
	
	</div>
	
	<div class="common_main_panel fieldsets">
	
		<b>Album Name</b>
		<br><input type="text" name="name" value="<?php echo $album->name?>">
		
		<br><br>
		
		<b>Album View</b> 
		<select name="view">
			<option>lightbox</option>
			<?php
				if('gallery' == $album->view)
					echo '<option selected="selected">gallery</option>';
				else
					echo '<option>gallery</option>';
			?>
		</select>
		
		<br><br>
		<b>Album Params</b>
		<br><input type="text" name="params" value="<?php echo $album->params?>">		
		
	</div>

</form>
