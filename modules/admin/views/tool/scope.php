
<?php echo form::open("tool/scope/$tool_data->guid/$page_id", array('class' => 'ajaxForm', 'rel' => $js_rel_command) )?>
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_tool" class="jade_positive">Save Changes</button>
		<div id="common_title">Configure Tool Scope</div>
	</div>
	
	<?php
	$scope = ('5' >= $tool_data->page_id) ? 'global' : 'local';
	$selected = array('local'=>'', 'global'=>'');
	$selected[$scope] = 'selected="selected"';
	?>
	
	<div class="common_left_panel">
		Choose a scope! =D
	</div>
	
	<div class="common_main_panel">
		Scope: <select name="page_id">
			<option value="<?php echo $page_id?>" <?php echo $selected['local']?>>Local</option>
			<option value="<?php echo $tool_data->container?>" <?php echo $selected['global']?>>Global</option>
		</select>
		
		<p>
			<dl style="line-height:1.6em">
				<dt><b>Local</b></dt>
				<dd>Tools exist and display on their parent page only.</dd>

				<dt><b>Global</b></dt>
				<dd>Tools display on all pages and do not belong to any page.</dd>
			</dl>
		</p>
	</div>
</form>