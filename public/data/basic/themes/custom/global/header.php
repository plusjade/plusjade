<div id="header">
	<div id="header_address">
		<?php
		echo "<b>CALL: 444-4444</b>";	
		?>		
    </div>
    <a href="<?php echo url::site();?>">	  
		<?php
		if(empty($_SESSION['banner']))
			echo '<div id="text_logo" style="float:left;font-weight:bold;font-size:3em;">'.$_SESSION['site_name'].'</div>';
		else
			echo "<img src=\"{$data_path}/assets/images/banners/{$_SESSION['banner']}\" id=\"header_banner\">";
		?>
	</a>
</div>


