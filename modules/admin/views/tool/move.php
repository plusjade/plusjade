

<?php echo form::open("tool/move/$tool_guid", array('class' => 'ajaxForm') )?>	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_tool" class="jade_positive">Move Tool</button>
		<div id="common_title">Move Tool to New Page</div>
	</div>
	
	<select name="new_page">
		<?php foreach($pages as $page)?>
			<option value="<?php echo $page->id?>"><?php echo url::site($page->page_name)?></option>
		<?php endforeach;?>
	</select>
</form>