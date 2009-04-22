

<?php echo form::open_multipart("edit_navigation/settings/$parent->id", array( 'class' => 'ajaxForm' ) )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_item" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/>  Save Settings
		</button>
		<div id="common_title">Edit Navigation Settings</div>
	</div>	

	<div class="fieldsets">
		<b>List Title</b> <input type="text" name="title" style="width:350px">	
	</div>

	
	
</form>