
<div class="archive_wrapper">
	<?php
	$old_year = false;
	$old_month = false;
	foreach($blog_posts as $blog_post)
	{
		if($old_year != $blog_post->year)
		{
			echo "<h2>$blog_post->year</h2>";
			$old_year = $blog_post->year;
		}
		if($old_month != $blog_post->month)
		{
			echo "<div class='clearboth'></div><h2>$blog_post->month</h2>";
			$old_month = $blog_post->month;
		}
			
		echo '<div class="archive_post"><span>'. $blog_post->day . ':</span> <a href="'.url::site("$blog_page_name/entry/$blog_post->url").'" rel="blog_ajax"> '. $blog_post->title .'</a></div>';
	}
	?>
</div>