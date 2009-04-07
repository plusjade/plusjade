<style type="text/css">
	form select{font-size:2em;}
</style>

<?php echo form::open("tool/move/$tool_guid", array('class' => 'ajaxForm') )?>
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_tool" class="jade_positive">
			<img src="/images/check.png" alt=""/> Move Tool
		</button>
		<div id="common_title">Move Tool to New Page</div>
	</div>
	
	<select name="new_page">
	<?php
		foreach($pages as $page)
		{
			echo '<option value=' . $page->id . '>' . url::site($page->page_name) . '</option>'."\n";
		}
	?>
	</select>
	
</form>