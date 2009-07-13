

<?php echo form::open("tool/add/$page_id", array('class' => 'custom_ajaxForm') )?>
		
	<div id="common_tool_header" class="buttons">
		<div id="common_title">Add New Content Tool to this Page</div>
	</div>
		
	<div id="tool_list_wrapper" class="common_left_panel">
		<h3>Content Tools</h3>
		<ul>
		<?php 
			foreach($tools_list as $key => $tool)
				echo "<li><a href='#' rel='$tool->id'>$tool->name</a></li>";
		?>
		</ul>	
	</div>
	
	<div id="tool_view_wrapper" class="common_main_panel">		
		<?php	
		foreach($tools_list as $key => $tool)
		{
			?>
			<div id="tool_<?php echo $tool->id?>" class="each_tool">
				<button type="submit" name="tool" value="<?php echo $tool->id?>" class="jade_positive">
					<?php echo $tool->name?>
				</button>
				<div class="desc"><?php echo $tool->desc?></div>
			</div>
			<?php
		}
		?>

	</div>

</form>
<script type="text/javascript">
$(document).ready(function()
{
	$('#tool_1').show();
	$('#tool_list_wrapper a').click(function(){
		var id = $(this).attr('rel');	
		$('div.each_tool').hide(); 
		$('#tool_'+id).show();
		return false;
	});
	
/*
 * ACTIVATE custom ajax form
 * tool_data = post output from this method (above)
 * receives the custom url of where the next 'add' page is for the particular tool
 */
	$('.facebox .custom_ajaxForm').ajaxForm({	
		beforeSubmit: function(){
			$('.facebox .custom_ajaxForm button')
			.attr('disabled','disabled')
			.removeClass('jade_positive');
			$('.facebox .show_submit').show();
		},			
		success: function(tool_data) {
			//tool_data format: toolname:load_method:tool_id:tool_guid
			var tool_data = tool_data.split(':');
			// add tool to DOM
			$().jade_update_tool_html('add', tool_data[0], tool_data[2], tool_data[3]);	
			
			// load the 'load_method' tool::method
			$.get('/get/edit_'+ tool_data[0] +'/'+ tool_data[1] +'/'+ tool_data[2], 
				function(data){$.facebox(data, false, 'facebox_base')}
			);
		}
	});
});
</script>