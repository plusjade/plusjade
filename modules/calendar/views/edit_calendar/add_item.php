

<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_calendar/add/<?php echo $tool_id?>" method="POST" class="ajaxForm">

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Add New Event</button>
		<div id="common_title">Create New Calendar Entry</div>
	</div>

	
	<div class="common_left_panel">	
		<ul id="common_view_toggle" class="ui-tabs-nav">
			<li><a href="#calendar_date" class="selected"><b>Date and Title</b></span></a><li>
			<li><a href="#calendar_desc"><b>Description</b></span></a><li>
		</ul>
	</div>
	
	
	
	<div class="common_main_panel">
	
		<div id="calendar_date" class="toggle fieldsets">
			<b>Event Title</b>
			<br><input type="text" name="title" rel="text_req" style="width:400px">
			<br><br>
			<b>Date</b>
			<div class="calendar_date_pane">
				<div id="datepicker" style="width:200px"></div>
			</div>
			
			<div class="calendar_date_pane">
				<b>Selected Date</b>
				<br><input type="text" name="date" id="date_field" READONLY>
			</div>
			
		</div>
		
		<div id="calendar_desc" class="toggle fieldsets">

			<textarea name="desc" class="render_html"></textarea>
		</div>
		
		
	</div>
	
</form>	

<script type="text/javascript">


	$('div.toggle').hide();
	$('div#calendar_date').show();
	
	
	$("#datepicker").datepicker({
		altField: "#date_field",
		changeMonth: true
		//changeYear: true
	});
	


</script>