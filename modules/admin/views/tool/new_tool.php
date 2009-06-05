
<style type="text/css">
	#tool_list_wrapper{
		float:left;
		width:180px;
	}
	#tool_list_wrapper ul{
		line-height:1.5em;
	}
	#tool_view_wrapper{
		float:right;
		height:360px;
		padding:5px;
		background:#eee;
		overflow:auto;
		border:1px solid #ccc;
		width:600px;
	}
	.each_tool{
		display:none;
		margin:5px;	
	}
	.each_tool button{
		width:200px;;
		height:30px;
		border:0;
	}
	.each_tool div.desc{
		margin-top:10px;
		padding:10px;
		background:#fff;
	}
	#jade_tool_box label:hover,
	#jade_tool_box label.selected{	
		background: #7ebd40 url(/assets/images/admin/light_green_bg.png) repeat-x bottom left;
	}
</style>

<?php echo form::open("tool/add/$page_id", array('class' => 'custom_ajaxForm') )?>
		
	<div id="common_tool_header" class="buttons">
		<div id="common_title">Add New Tool to Page</div>
	</div>
		
	<div id="tool_list_wrapper">
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
	
	<div id="tool_view_wrapper">		
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
	var options = {
		success: function(tool_data) {
			/* 
				the data we get back should be JSON (when i learn it)
				data format:
				toolname:next_step:tool_id:tool_guid
				TODO: clean this up, its too cryptic
			*/
			
			tool_data = tool_data.split(':');
			// load up the add-item method
			$.get('/get/edit_'+ tool_data[0] +'/'+ tool_data[1] +'/'+ tool_data[2], 
			{guid : tool_data[3]},
			function(data){ 
				$.facebox(data, false, 'facebox_base')				
			});					
		}					
	};
	$('.facebox .custom_ajaxForm').ajaxForm(options);
</script>