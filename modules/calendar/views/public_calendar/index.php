

<div id="calendar_wrapper_<?php echo $tool_id?>" class="calendar_wrapper">

	<div id="load_calender_div" class="phpajaxcalendar_wrapper">
		<?php echo $calendar?>
	</div>
	

	<div id="calendar_event_details">
		<?php if(! empty($events) )echo $events?>
	</div>

</div>