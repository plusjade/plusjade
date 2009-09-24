
<style type="text/css">
	.body {padding:10px;border:1px dashed #ccc;}
	.each_review{margin-bottom:10px;}
</style>

<?php foreach($review->review_items as $item):?>
	
	<div class="each_review">
		<b>Rating</b> <?php echo $item->rating?>
		<div class="body">
			<?php echo $item->body?>
		</div>
		- <i><?php echo $item->name?></i>
	</div>
<?php endforeach;?>


<a href="/<?php echo $page_name?>?action=add">Add a new Review</a>