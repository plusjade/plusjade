<div id="header">
    <a href="<?php echo url::site();?>">	  
		<?php
		if(empty($_SESSION['banner']))
			echo '<div id="text_logo" style="float:left;font-weight:bold;font-size:3em;">'.$_SESSION['site_name'].'</div>';
		else
			echo "<img src=\"{$data_path}/images/banners/{$_SESSION['banner']}\" id=\"header_banner\">";
		?>
	</a>
	<div id="header_address">
		<?php
		echo "<b>CALL US: 626-555-5555</b>";
		echo "<p>Company Name<br>";
		echo "Company Address</p>";		
		?>		
    </div>
</div>