

<div class="calendar_wrapper">

	<div id="load_calender_div" class="phpajaxcalendar_wrapper">
		<?php echo $calendar?>
	</div>
	

	<div id="loadImage" class="aligncenter" style="display:none">
		<img src="/images/facebox/loading.gif"><br>loading...
	</div>

	<div id="calendar_event_details">
		<?php if(! empty($events) )echo $events?>
	</div>

</div>