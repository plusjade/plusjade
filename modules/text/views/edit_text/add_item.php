
<span id="on_close"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("edit_text/add/$item->id", array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_text" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Text</div>
	</div>	
	
	<textarea name="body" class="render_html"><?php echo $item->body?></textarea>

</form>
