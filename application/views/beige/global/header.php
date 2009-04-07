<div id="header">
    <a href="<?php echo url::site();?>">	  
		<?php
		if(empty($_SESSION['banner']))
			echo '<div id="text_logo" style="font-weight:bold;font-size:3em;">'.$site_name.'</div>';
		else
			echo "<img src=\"{$data_path}/assets/images/banners/{$_SESSION['banner']}\" id=\"header_banner\">";
		?>
	</a>
</div>