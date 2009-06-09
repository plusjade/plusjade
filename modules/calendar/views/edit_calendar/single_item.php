
<?php echo form::open_multipart("edit_calendar/edit/$item->id", array('class' => 'ajaxForm', 'id' => $item->id, 'rel' => $js_rel_command))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_item" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit Calendar Event</div>
	</div>	

	<div class="fieldsets">
		<b>Title</b><br>
		<input type="text" name="title" value="<?php echo $item->title?>" rel="text_req" class="full_width">
	</div>

	<div class="fieldsets">
		<b>Description</b><br>
		<textarea name="desc" class="render_html"><?php echo $item->desc?></textarea>
	</div>	
	
	

</form>


