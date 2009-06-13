<style type="text/css">
#editable_wrapper{
	border:1px solid red;
}
#show_edit{

}
#show_html{
	display:none;
}
</style>

<?php echo form::open_multipart("edit_text/add/$item->id", array('class' => 'ajaxForm', 'rel'=>"$js_rel_command"))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_text" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Text</div>
	</div>	
	
	<textarea name="body" class="render_html"><?php echo $item->body?></textarea>

</form>
