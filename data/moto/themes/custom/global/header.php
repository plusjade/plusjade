
<a href="<?php echo url::site();?>">	  
	<?php
	if(empty($_SESSION['banner']))
		echo '<div id="text_logo" style="float:left;font-weight:bold;font-size:3em;">'.$_SESSION['site_name'].'</div>';
	else
		echo "<img src=\"{$data_path}/assets/images/banners/{$_SESSION['banner']}\" id=\"header_banner\">";
	?>
</a>

<div id="header_address">
	
	<?php
	echo "<b>CALL:</b>";
	echo "<p><a href=\"/contact\"></a></p>";		
	?>	
San Gabriel Valley Motorcycle Maintenance, Repair, and Parts.	
</div>



