<div id="header_wrapper">
	<?php	View::factory("_global/header")->render(true);	?>
	<div class="clearboth"></div>
</div> 

<div id="body_wrapper">
	
	<div id="main_menu_wrapper">
		<?php View::factory("_global/menu")->render(true);?>
	</div>  

	<div id="content_wrapper">
	<?php View::factory("_global/content")->set(array('primary' => $primary))->render(true);?>
	</div>

	<div id="footer_wrapper">
		<?php View::factory("_global/footer")->render(true);?>
	</div>
  
</div>