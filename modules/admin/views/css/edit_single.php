
<?php echo form::open_multipart("edit_$tool_name/css/$tool_id", array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_css" class="jade_positive" rel="<?php #echo $tool_id?>">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit <?$tool_name?> tool <?$tool_id?> <b>CSS</b>.</div>	
	</div>

	<textarea name="contents" style="height:400px !important; overflow:auto"><?php echo $contents?></textarea>

</form>