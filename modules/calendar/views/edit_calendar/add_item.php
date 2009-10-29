<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_calendar/add/<?php echo $tool_id?>" method="POST" class="ajaxForm">

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Add New Event</button>
		<div id="common_title">Create New Calendar Entry</div>
	</div>
	
	<ul class="common_tabs_x ui-tabs-nav">
		<li><a href="#calendar_date" class="selected"><b>Date and Title</b></span></a><li>
		<li><a href="#calendar_desc"><b>Description</b></span></a><li>
	</ul>

	<div class="common_full_panel">
	
		<div id="calendar_date" class="toggle fieldsets">
			
			<div class="common_half_left">
				<b>Event Title</b>
				<br/><input type="text" name="title" rel="text_req" style="width:300px">
			</div>
			
			<div class="common_half_right">
				<b>Selected Date</b>
				<br/><input type="text" name="date" id="date_field" READONLY>
				<br/><br/>
				<div id="datepicker" style="width:200px"></div>
			</div>
		</div>
		
		<div id="calendar_desc" class="toggle fieldsets">
			<textarea name="desc" class="render_html"></textarea>
		</div>
		
		
	</div>
	
</form>	

<script type="text/javascript">
	$(".common_tabs_x li a").click(function(){
		$('.common_tabs_x li a').removeClass('active');
		var pane = $(this).attr('href');
		$('.common_full_panel div.toggle').hide();
		$('.common_full_panel div'+ pane).show();
		return false;
	});
	$('.common_tabs_x li a:first').click();
	
	$("#datepicker").datepicker({
		altField: "#date_field",
		changeMonth: true
		//changeYear: true
	});
</script>