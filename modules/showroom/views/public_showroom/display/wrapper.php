


<?php 
if(! empty($categories))
	echo "<div class=\"showroom_categories\">$categories</div>";	
?>

<div class="showroom_items">
	<?php if(! empty($items)) echo $items?>
	<?php if(! empty($item)) echo $item?>
</div>

