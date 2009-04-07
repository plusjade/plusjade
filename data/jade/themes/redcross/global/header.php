<div id="header">

	<div id="header_address">
		<b>626-433-3534</b>
		<br><a href="/get/auth">My Dashboard</a>		
    </div>
	
    <a href="<?php echo url::site();?>">	  
		<?php
		if(empty($_SESSION['banner']))
			echo '<div id="text_logo">'.$site_name.'</div>';
		else
			echo "<img src=\"$data_path/assets/images/banners/{$_SESSION['banner']}\" id=\"header_banner\">";
		?>
	</a>

	<div id="headline_wrapper"> 
		<span id="headline">
			Create your website.
			<br>Get down to <b>business</b>.
		</span>
	</div>
	

		

	
</div>