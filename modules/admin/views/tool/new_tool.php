
<style type="text/css">
.droppable_tool{
	width:120px;
	height:120px;
	float:left;
	margin:5px;
	padding:5px;
	border:1px solid #ccc;
	background:#fff;
	text-align:center;
	cursor:move;
}
.each_tool .desc{
	background:#ffffcc;
}
#tool_droppable_wrapper{
	background:lightblue;
	border:1px solid #ccc;
	height:200px;
}
</style>	

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

	<div id="tool_droppable_wrapper">
		
	</div>
</div>

<div id="tool_view_wrapper" class="common_main_panel">		
	<?php foreach($tools_list as $tool):?>
		<div id="tool_<?php echo $tool->id?>" class="each_tool">
			<div class="desc"><?php echo $tool->desc?></div>
			
			<?php foreach($tool->system_tool_types as $type):?>
				<div class="droppable_tool" rel="<?php echo $tool->id?>" title="<?php echo $type->type?>">
					<?php echo $type->type?>
					<!-- <?php echo $type->desc?> -->
				</div>
			<?php endforeach;?>
		</div>
	<?php endforeach;?>

</div>


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

// make tools draggable
	$("div.droppable_tool").draggable({ revert: true });

// make space droppable.
	$("#tool_droppable_wrapper").droppable({
		activeClass: 'ui-state-highlight',
		accept: 'div.droppable_tool',
		drop: function(event, ui) {
			var tool = $(ui.draggable).attr('rel');
			var type = $(ui.draggable).attr('title');

			$(document).trigger('show_submit.plusjade');
			$('.common_main_panel').hide().before('<div class="plusjade_ajax floatleft" style="width:600px;"><b>Adding Tool: May take a few seconds...</b></div>');
			$.post('/get/tool/create/<?php echo $page_id?>',
				{tool:tool, type:type},
				function(data){
					// contains toolname,method,tool_id, parent_id, instance.
					var newTool = $.evalJSON(data); // console.log(newTool);
					$().jade_inject_tool('add', newTool);	
					
					// load the next tool method.
					$.get('/get/edit_'+ newTool.toolname +'/'+ newTool.method +'/'+ newTool.parent_id, 
						function(data){$.facebox(data, false, 'facebox_base')}
					);			
				}
			);
		}
	});
	
});



</script>