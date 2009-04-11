<?php

if(empty($_SESSION['banner']))
	echo '<div id="text_logo">'.$site_name.'</div>';
else
	echo "<img src=\"$data_path/assets/images/banners/{$_SESSION['banner']}\" id=\"header_banner\">";
