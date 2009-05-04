
<div class="calendar_events_wrapper">

	<h1 class="aligncenter">Events for <?php echo $date?></h1>

	<?php 
	foreach($events as $event)
	{
		?>
		<div class="calendar_item" rel="<?php echo $event->id?>">
			<h2 class="center"><?php echo $event->title?></h2>
			<br>
			Description:
			<p><?php echo $event->desc?></p>
		</div>
		<?php
	}
	?>
</div>