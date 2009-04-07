<?php
$theme_header = APPPATH."views/$theme_name/global/header.php";

// Cascade the File
// 1. user data -- 2. theme data -- 3. stock

if(file_exists("{$custom_include}global/header.php"))
	include_once("{$custom_include}global/header.php");
elseif(file_exists("$theme_header"))
	include_once("$theme_header");
else
	echo "\n" . 'THIS IS THE ROOT STOCK HEADER' . "\n";
