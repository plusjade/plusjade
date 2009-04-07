<?php
$theme_data = APPPATH."views/$theme_name/global/footer.php";

// Cascade the File
// 1. user data -- 2. theme data -- 3. stock

if( file_exists("{$custom_include}global/footer.php") )
	include_once("{$custom_include}global/footer.php");
elseif( file_exists($theme_data) )
	include_once($theme_data);
else
	echo "\n" . '<!-- THIS IS THE ROOT STOCK FOOTER -->'. "\n";
