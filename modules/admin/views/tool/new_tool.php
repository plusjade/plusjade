
<style type="text/css">
	#jade_tool_box{
		width:80%;
		margin:0 auto;
	}
	#jade_tool_box label{
		display:block;
		padding:10px;
		margin:10px;
		cursor:cursor;
		cursor:pointer;
		background:lightblue;
		color:#fff;
		font-size:1.6em;
		width:99%;
	}
	#jade_tool_box label:hover,
	#jade_tool_box label.selected
	{	
		background: #7ebd40 url(/images/admin/light_green_bg.png) repeat-x bottom left;
	}
</style>

<?php echo form::open("tool/add/$page_id", array('class' => 'custom_ajaxForm') )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_tool" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Tool
		</button>
		<div id="common_title">Add New Tool to Page</div>
	</div>
	
	<div id="common_tool_info">
		The complete Tool guide, usage instructions, and examples 
		<br>can be found on our <a href="http://localhost.com/tools" target="_blank">Tools Page</a> <small>(new window)</small>.
	</div>
	
	<div id="jade_tool_box">
		<?php	
		foreach($tools_list as $key => $tool)
		{
			$checked ='';
			if('0' == $key ) $checked = 'CHECKED';
			
			echo '<label FOR="radio_'.$key.'">';
			echo '<input type="radio" name="tool" id="radio_'.$key.'" value="' . $tool->id .' " '.$checked.'> '. $tool->name;				
			
			echo '</label>';
			
			unset($checked);
		}
		?>
	</div>
	
</form>

