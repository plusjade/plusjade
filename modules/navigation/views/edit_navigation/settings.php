
<span class="on_close"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("edit_navigation/settings/$navigation->id", array( 'class' => 'ajaxForm') )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_item" class="jade_positive" accesskey="enter">Save Settings</button>
		<div id="common_title">Edit Navigation Settings</div>
	</div>	

	<div class="fieldsets">
		<b>List Name</b> <input type="text" name="name" value="<?php echo $navigation->name?>"  maxlength="100" style="width:350px">	
	
		<p style="line-height:1.6em">
			A List Name displays at the top of your Navigation list between <b>&lt;h2 class="navigation_title"&gt; &lt;/h2&gt;</b> tags.
			<br>
			<br>Customize the look by editing this tool's CSS file.  Look for the "navigation_title" class =).
		</p>
	</div>
	
</form>