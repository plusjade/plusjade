
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_calendar/add/<?php echo $tool_id?>" method="POST" class="ajaxForm">

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Add Event</button>
		<div id="common_title">Create New Calendar Entry</div>
	</div>

	<div class="common_left_panel">
		<b>Date</b><input type="text" name="date" id="date_field" READONLY>
		<div id="datepicker"></div>			
	</div>
	
	
	<div class="common_main_panel fieldsets">
		<b>Event Title</b><input type="text" name="title" rel="text_req" style="width:350px">
		<br>
		<br><textarea name="desc" class="render_html"></textarea>		
	</div>
	
</form>	

<script type="text/javascript">
	$("#datepicker").datepicker({
		altField: "#date_field",
		changeMonth: true
		//changeYear: true
	});
</script>