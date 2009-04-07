
<h1 class="aligncenter">Events for <?php echo $date?></h1>

<?php 
	foreach($events as $event)
	{
		?>
		<div class="event_wrapper" style="padding:10px; border:1px solid #ccc; margin:10px">
			<h2 class="center"><?php echo $event->title?></h2>
		</div>
		<?php
	}
