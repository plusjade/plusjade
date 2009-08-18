
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/settings/<?php echo $format->id?>" method="POST" class="ajaxForm">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_settings" class="jade_positive" accesskey="enter">Save Settings</button>
		<div id="common_title">Edit Formt Settings</div>
	</div>	
	
	<div class="common_left_panel">
	
	</div>
	<div class="common_main_panel fieldsets">
		<b>Format Name</b>
		<br><input type="text" name="name" value="<?php echo $format->name?>" style="width:300px">
	
		<br><br>
		
		<b>Format Type</b>
		<br><select name="type">
			<?php
				$types = array('people','faqs', 'contacts');
				foreach($types as $type)
					if($type == $format->type)
						echo "<option selected=\"selected\">$type</option>";
					else
						echo "<option>$type</option>";
			?>
		</select>
	</div>

</form>