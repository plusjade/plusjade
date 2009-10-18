	
<div class="blog_posts_wrapper">
	
	<?php
	if(isset($tag_search))
		echo "<div class='tag_search'>Tag search for '$tag' returned <b>$tag_search</b> results.</div>";
	
	foreach($blog_posts as $blog_post)
		echo View::factory('public_blog/blogs/single', array('blog_post' => $blog_post));
	?>	
</div>