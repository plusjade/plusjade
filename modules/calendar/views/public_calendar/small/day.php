
<div class="calendar_events_wrapper">

	<div class="calendar_event_title">Events for <?php echo $date?></div>

	<?php foreach($events as $event):?>
		<div id="calendar_item_<?php echo $event->id?>" class="calendar_item" rel="<?php echo $event->id?>">
			<h2><?php echo $event->title?></h2>
			
			<div class="item_description">
				Description:
				<p><?php echo $event->desc?></p>
			</div>
		</div>
	<?php endforeach;?>
</div>

<?php if($logged_in):?>
	<script type="text/javascript">
		$().add_toolkit_items("calendar");
	</script>
<?php endif;?>