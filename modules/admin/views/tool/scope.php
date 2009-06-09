<style type="text/css">
	form select{font-size:2em;}
</style>

<?php echo form::open("tool/scope/$tool_data->guid/$page_id", array('class' => 'ajaxForm', 'rel' => $js_rel_command) )?>
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_tool" class="jade_positive">
			<img src="<?php url::image_path('admin/check.png')?>" alt=""> Save Changes
		</button>
		<div id="common_title">Configure Tool Scope</div>
	</div>
	
	
	<?php
	$scope = ('5' >= $tool_data->page_id) ? 'global' : 'local';
	$selected = array('local'=>'', 'global'=>'');
	$selected[$scope] = 'selected="selected"';
	?>
	
	<select name="page_id">
		<option value="<?php echo $page_id?>" <?php echo $selected['local']?>>Local</option>
		<option value="<?php echo $tool_data->container?>" <?php echo $selected['global']?>>Global</option>
	</select>
	
</form>