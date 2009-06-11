

<?php echo form::open("tool/add/$page_id", array('class' => 'custom_ajaxForm') )?>
		
	<div id="common_tool_header" class="buttons">
		<div id="common_title">Add New Tool to Page</div>
	</div>
		
	<div id="tool_list_wrapper" class="common_left_panel">
		<h3>Content Tools</h3>
		<ul>
		<?php 
			foreach($tools_list as $key => $tool)
				echo "<li><a href='#' rel='$tool->id'>$tool->name</a></li>";
		?>
		</ul>
		
		<h3>Page Builders</h3>
		<?php
			if( is_object($protected_tools) )
			{
				echo '<ul>';
				foreach($protected_tools as $key => $tool)
					echo "<li><a href='#' rel='$tool->id'>$tool->name</a></li>";
			
				echo '</ul>';
			}
			else
				echo "<small>$protected_tools</small>";
		?>		
	</div>
	
	<div id="tool_view_wrapper" class="common_main_panel">		
		<?php	
		foreach($tools_list as $key => $tool)
		{
			?>
			<div id="tool_<?php echo $tool->id?>" class="each_tool">
				<button type="submit" name="tool" value="<?php echo $tool->id?>" class="jade_positive">
					<img src="/assets/images/admin/add.png" alt="Add"/> <?php echo $tool->name?>
				</button>
				<div class="desc"><?php echo $tool->desc?></div>
			</div>
			<?php
		}
		
		if( is_object($protected_tools) )
		{
			foreach($protected_tools as $key => $tool)
			{
				?>
				<div id="tool_<?php echo $tool->id?>" class="each_tool">
					<button type="submit" name="tool" value="<?php echo $tool->id?>" class="jade_positive">
						<img src="/assets/images/admin/add.png" alt="Add"/> <?php echo $tool->name?>
					</button>
					<div class="desc"><?php echo $tool->desc?></div>
				</div>
				<?php
			}
		}
		?>

	</div>

</form>
<script type="text/javascript">
$(document).ready(function()
{
	$('#tool_1').show();
	$('#tool_list_wrapper a').click(function(){
		id = $(this).attr('rel');	
		$('div.each_tool').hide(); 
		$('#tool_'+id).show();
		return false;
	});
	

	// ACTIVATE custom ajax form
	// tool_data = post output from this method (above)
	// receives the custom url of where the next 'add' page is for the particular tool
	$('.facebox .custom_ajaxForm').ajaxForm({	
		beforeSubmit: function(){
			$('.facebox .show_submit').show();
		},			
		success: function(tool_data) {
			//alert(tool_data); return false;
			//data format: toolname:step2:tool_id:tool_guid
			tool_data = tool_data.split(':');
			
			// add tool to dom
			$().jade_update_tool_html('add', tool_data[0], tool_data[2], tool_data[3]);
			
			// load the step2 tool::method
			$.get('/get/edit_'+ tool_data[0] +'/'+ tool_data[1] +'/'+ tool_data[2], 
				{guid : tool_data[3]},
				function(data){
					$.facebox(data, '', 'facebox_base');
					$('.facebox .show_submit').hide();	
				}
			);
		}
	});
});
</script>