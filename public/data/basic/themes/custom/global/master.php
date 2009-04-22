	<div id="header_wrapper">
	
		<div id="main_menu_wrapper">
			<?php View::factory("_global/menu")->render(true);?>
		</div>  
		
		<?php  View::factory("_global/header", array('load_custom' => TRUE))->render(true);?>
		<div class="clearboth"></div>
	</div> 

	<div id="body_wrapper">	
		
		<div id="primary_wrapper">		
			<div id="sidebar">
				<h3>Contact Me:</h3>
			</div>
			
			<div id="content">
				<?php 
				if(isset($admin_links))  echo '<div id="primary_client_links">'.$admin_links.'</div>';

				if(isset($top))  echo '<div id="top_wrapper">'.$top.'</div>';
				if(isset($primary)) echo $primary;
				if(isset($bottom))  echo '<div id="bottom_wrapper">'.$bottom.'</div>';		
				?>
			</div>
			
		</div>	  
		
		<div id="footer_wrapper">
			<?php View::factory("_global/footer", array('load_custom' => TRUE))->render(true);	?>
		</div>

	</div>