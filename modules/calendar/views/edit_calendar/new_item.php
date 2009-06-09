
<form action="/get/edit_calendar/add/<?php echo $tool_id?>" method="POST" enctype="multipart/form-data" class="ajaxForm" rel="<?php echo $js_rel_command?>">

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_item" class="jade_positive">
			<img src="<?php echo url::image_path('check.png')?>" alt=""/> Add Event
		</button>
		<div id="common_title">Create New Calendar Entry</div>
	</div>

	<div class="fieldsets">
		<b>Event Title</b> <input type="text" name="title" rel="text_req" style="width:60%">
	</div>
	<div class="fieldsets">
		<div id="date_pane">
			<b>Date</b><input type="text" name="date" id="date_field" READONLY>
			<div id="datepicker"></div>	
		</div>
		
		<div id="desc_pane">
			<b>Description</b><br>
			<textarea name="desc" class="render_html"></textarea>
		</div>
	</div>
</form>	

<script type="text/javascript">
	$("#datepicker").datepicker({
		altField: "#date_field",
		changeMonth: true
		//changeYear: true
	});
</script>