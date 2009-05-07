	
<div class="blog_posts_wrapper">	
	<?php
	foreach($items as $item)
		echo View::factory('public_blog/single', array('item' => $item));
	?>	
</div>