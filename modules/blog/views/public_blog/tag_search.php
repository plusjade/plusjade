
<div class="archive_wrapper">
	<div>Viewing posts tagged "startup"</div>
	<?php
	$old_year = false;
	$old_month = false;
	foreach($items as $item)
	{
		if($old_year != $item->year)
		{
			echo "<h1>$item->year</h1>";
			$old_year = $item->year;
		}
		if($old_month != $item->month)
		{
			echo "<div class='clearboth'></div><h2>$item->month</h2>";
			$old_month = $item->month;
		}
			
		echo '<div class="archive_post"><span>'. $item->day . ':</span> <a href="'.url::site("blog/entry/$item->url").'"> '. $item->title .'</a></div>';
	}
	?>
</div>