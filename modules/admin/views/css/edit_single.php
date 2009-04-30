
<?php echo form::open_multipart("css/edit/$name_id/$tool_id", array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_css" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit <?php echo $tool_name?>(<?php echo $tool_id?>) CSS.</div>	
	</div>

	<div class="fieldsets">
		<b>Add Class to Container:</b> <input type="text" name="attributes" value="<?php echo $attributes?>" style="width:400px">
	</div>
	
	<textarea name="contents" style="height:300px !important; width:99%; overflow:auto"><?php echo $contents?></textarea>

</form>