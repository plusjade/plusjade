
<div id="header_wrapper">
	<?php echo View::factory("_global/header")?> 
	
	<div id="main_menu_wrapper">
		<?php echo View::factory("_global/menu")?> 
	</div>
	<div class="clearboth"></div>
</div>

<div id="body_wrapper">
	
	<?php if( isset($primary) ) echo $primary ?>
	
	<p class="more"><a href="#">read more</a></p>
</div>

<div id="footer_wrapper">
	<?php echo View::factory("_global/footer")?> 
</div>

