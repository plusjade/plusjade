

<div id="navigation_wrapper_<?php echo $parent->id?>" class="<?php echo $parent->attributes?>">
	<?php if(! empty($parent->title) ) 
		echo '<h2>'.$parent->title.'</h2>';
	?>
	<?php echo $tree?>
</div>