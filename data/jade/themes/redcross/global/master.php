<div id="header_wrapper">
	<?php	echo View::factory("_global/header")?>

	<div id="main_menu_wrapper">
		<?php echo View::factory("_global/menu")?>
	</div>  
	
</div> 	

<div id="body_wrapper">
	
	<div id="content_wrapper">
		<?php if(! empty($primary) ) echo $primary?>
	</div>
  
</div>

<div id="footer_wrapper">
	<?php echo View::factory("_global/footer")?>
</div>