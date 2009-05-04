

<div class="blog_post_wrapper">

	<div class="post_title">
		<div class="post_created"><?php echo $item->created?></div>
		<a href="/blog/entry/<?php echo $item->url?>"><?php echo $item->title?></a>
		
	</div>
	
	<div class="post_body">
		<?php echo $item->body?>
	
		<br><br>
		<div class="post_comments">5 comments - Category: News - Tags: startup, basketball</div>
	</div>
		
		
	<?php echo $comments?>

</div>