<div id="header_wrapper">

	<?php echo View::factory("_global/header")?>
	<div class="clearboth"></div>
	
</div> 

<div id="body_wrapper">
	
	<div id="main_menu_wrapper">
	
		<?php echo View::factory("_global/menu")?>
		
	</div>  

	<div id="content_wrapper">
	
		<?php if( isset($primary) ) echo $primary ?>
		
	</div>

	<div id="footer_wrapper">
	
		<?php echo View::factory("_global/footer")?>
		
	</div>
  
</div>