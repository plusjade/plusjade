<div id="header_wrapper">

	<?php echo View::factory("_global/header")?> 
	
	<div id="main_menu_wrapper">
		<?php echo View::factory("_global/menu")?> 
	</div>

	<h1>&nbsp;</h1>
	
	<!--	
	  <form action="" method="post">
		<label>newsletter</label>
		<input name="" type="text" />
		<input name="" type="submit" class="button" value="Go" />
	  </form>
	 -->
 
</div>

<div id="body_wrapper">	

	<?php if( isset($primary) ) echo $primary ?>
</div>


<div id="footer_wrapper">

	<?php echo View::factory("_global/footer")?> 
	
</div>