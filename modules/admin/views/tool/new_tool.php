
<style type="text/css">
	#jade_tool_box{
		width:750px;
		padding:5px;
		background:#eee;
		overflow:auto;
		border:1px solid #ccc;
	}
	.tool_box_wrapper{
		width:170px;
		height:170px;
		border:1px solid lightblue;
		margin:5px;
		float:left;
		background:#fff;
	}
	.tool_box_wrapper div{
		padding:10px;
		line-height:1.5em;
		margin-top:15px;
	}
	#jade_tool_box label{
		display:block;
		padding:10px 0;
		cursor:cursor;
		cursor:pointer;
		background: lightblue url(/assets/images/admin/blue_bg.png) repeat-x bottom left;
		color:#fff;
		font-size:1.4em;
	}
	#jade_tool_box label:hover,
	#jade_tool_box label.selected
	{	
		background: #7ebd40 url(/assets/images/admin/light_green_bg.png) repeat-x bottom left;
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
			
			echo '<div class="tool_box_wrapper">';
			echo '<label FOR="radio_'.$key.'">';
			echo '<input type="radio" name="tool" id="radio_'.$key.'" value="' . $tool->id .' " '.$checked.'> '. $tool->name;				
			echo '</label>';
			echo '<div>'. $tool->desc .'</div>';
			echo '</div>';
			
			unset($checked);
		}
		?>
	</div>
	
</form>
<script type="text/javascript">
	// ADD tool label stuff...
	$('#jade_tool_box label').click(function(){
		$('#jade_tool_box label').removeClass('selected');
		$(this).addClass('selected');
	});
		
	// ACTIVATE custom ajax form
	// data = post output from this method (above)
	var options = {
		success: function(data) {
			$.get('/get/edit_'+data, function(data) { 
				$.facebox(data, false, 'facebox_base')				
			});					
		}					
	};
	$('.facebox .custom_ajaxForm').ajaxForm(options);
</script>