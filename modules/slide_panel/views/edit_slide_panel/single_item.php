

<?php echo form::open("edit_slide_panel/edit/$item->id", array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit Slide Panel</div>
	</div>		
		
	<div class="fieldsets">
		<b>Title</b> 
		<input type="text" name="title" value="<?php echo $item->title?>" rel="text_req" size="50">
	</div>
	<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
</form>
