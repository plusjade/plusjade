
<style type="text/css">
	#jade_tool_box{
		width:700px;
		height:450px;
		padding:5px;
		background:#eee;
		overflow:auto;
		border:1px solid #ccc;
	}
	.tool_box_wrapper{
		width:160px;
		height:160px;
		margin:5px;
		float:left;
		background:#fff;
	}
	.tool_box_wrapper button{
		width:100%;
		height:35px;
		border:0;
	}
	.tool_box_wrapper div{
		padding:10px;
		line-height:1.5em;
	}
	#jade_tool_box label:hover,
	#jade_tool_box label.selected
	{	
		background: #7ebd40 url(/assets/images/admin/light_green_bg.png) repeat-x bottom left;
	}
</style>

<?php echo form::open("tool/add/$page_id", array('class' => 'custom_ajaxForm') )?>
		
	<div id="common_tool_header" class="buttons">
		<div id="common_title">Add New Tool to Page</div>
	</div>
		
	<div id="tab_container">		
		
		<ul class="ui-tabs-nav generic_tabs">
			<li><a href="#fragment-1">Content Tools</a><li>
			<li><a href="#fragment-2">Page Builders</a><li>
		</ul>
		
		<div id="fragment-1">
	
			<div id="jade_tool_box">
				<?php	
				foreach($tools_list as $key => $tool)
				{
					?>
					<div class="tool_box_wrapper">
						<button type="submit" name="tool" value="<?php echo $tool->id?>" class="jade_positive">
							<img src="/assets/images/admin/add.png" alt="Add"/> <?php echo $tool->name?>
						</button>
						<div><?php echo $tool->desc?></div>
					</div>
					<?php
				}
				?>
			</div>
			
		</div>

		<div id="fragment-2" class="ui-tabs-hide">
			<div id="jade_tool_box">
				<?php
				if( empty($protected_tools) )
				{
					echo 'Either this is a sub-page or a Page Builder is already installed on this page.';
				}
				else
				{
					foreach($protected_tools as $key => $tool)
					{
						?>
						<div class="tool_box_wrapper">
							<button type="submit" name="tool" value="<?php echo $tool->id?>" class="jade_positive">
								<img src="/assets/images/admin/add.png" alt="Add"/> <?php echo $tool->name?>
							</button>
							<div><?php echo $tool->desc?></div>
						</div>
						<?php
					}
				}
				?>
			</div>
			
		</div>
		

		
	</div>


	
</form>
<script type="text/javascript">

	$("#tab_container").tabs();
	
	
	// ADD tool label stuff...
	$('#jade_tool_box label').click(function(){
		$('#jade_tool_box label').removeClass('selected');
		$(this).addClass('selected');
	});
		
	// ACTIVATE custom ajax form
	// data = post output from this method (above)
	// receives the custom url of where the next 'add' page is for the particular tool
	var options = {
		success: function(data) {
			$.get('/get/edit_'+data, function(data) { 
				$.facebox(data, false, 'facebox_base')				
			});					
		}					
	};
	$('.facebox .custom_ajaxForm').ajaxForm(options);
</script>