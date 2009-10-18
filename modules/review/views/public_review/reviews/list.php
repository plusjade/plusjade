

<div class="review_breakdown">
	<div class="review_total">
		Total Reviews: <?php echo $review->review_items->count()?>
	</div>
	<?php
		foreach($rating_counts as $item)
			echo "<div><b>$item->rating stars</b>: $item->count</div>";
			
	?>
</div>

<?php echo $add_handler?>

<?php foreach($review->review_items as $item):?>
	<div id="review_item_<?php echo $item->id?>" class="review_item" rel="<?php echo $item->id?>">
		<div class="review_rating">
			<b>Rating</b> <?php echo $item->rating?>/5
		</div>
		<div class="review_body">
			<?php echo $item->body?>
		</div>
		<div class="review_name">
			- <i><?php echo $item->name?></i>
		</div>
	</div>
<?php endforeach;?>


