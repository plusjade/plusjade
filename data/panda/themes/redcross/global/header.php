<div id="header">
    <a href="<?php echo url::site();?>">	  
		<?php
		if(empty($_SESSION['banner']))
			echo '<div id="text_logo" style="float:left;font-weight:bold;font-size:3em;">'.$site_name.'</div>';
		else
			echo "<img src=\"{$data_path}/assets/images/banners/{$_SESSION['banner']}\" id=\"header_banner\">";
		?>
	</a>
	<div id="header_address">
This is my beautiful site!<br>The PandaLand!		
    </div>
</div>