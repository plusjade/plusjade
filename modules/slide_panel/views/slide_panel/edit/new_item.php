
<?php echo form::open("edit_slide_panel/add/$tool_id", array('class' => 'ajaxForm'))?>
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_slide_panel" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/> Add Panel
		</button>
		<div id="common_title">Add New Panel to Slide</div>
	</div>		
		
	<div class="fieldsets">
		<b>Title</b> 
		<input type="text" name="title" value="" rel="text_req" size="50">
	</div>
	<textarea name="body" class="render_html"></textarea>
</form>