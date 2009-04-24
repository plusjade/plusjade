
<div id="showroom_wrapper_<?php echo $parent->id?>" class="showroom_wrapper <?php echo $parent->attributes?>">

	<div class="showroom_categories">
		<?php if(! empty($categories) ) echo $categories?>
	</div>
	
	<div class="showroom_items">
		<?php if(! empty($items) ) echo $items?>
		<?php if(! empty($item) ) echo $item?>
	</div>

</div>