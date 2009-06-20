
<?php echo form::open_multipart("edit_calendar/edit/$item->id", array('class' => 'ajaxForm', 'rel' => $js_rel_command))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_item" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Calendar Event</div>
	</div>	

	<div class="fieldsets">
		<b>Title</b> <input type="text" name="title" value="<?php echo $item->title?>" rel="text_req" style="width:400px">
		<br><br>
		<textarea name="desc" class="render_html"><?php echo $item->desc?></textarea>
	</div>	
	
</form>


