

	<?php
	$x=0;
	foreach($format->format_items as $item)
	{
		echo "<p><b>$item->title</b></p>";
		echo "$item->body";
	}	
	?>		




