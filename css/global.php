<?php
header("Content-type: text/css");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");

$user			= $_GET['u'];
$theme			= $_GET['t'];	
$ROOT			= $_SERVER['DOCUMENT_ROOT'];
$user_css		= "$ROOT/data/$user/themes/$theme/global.css";	
$root_css		= "$ROOT/application/views/$theme/global.css";
$image_path		= "/data/$user/themes/$theme/images";
$static_helpers	= "$ROOT/application/views/_global/static_helpers.css";

ob_start();

# Load static_helpers.css first so they can be overwritten
readfile($static_helpers);

/*
 * Load custom global root css if available
 * else: fallback to theme folder
 */
if ( file_exists($user_css) )
{
	readfile($user_css);
}	
elseif( file_exists($root_css) )
{
	readfile($root_css);
}
else
	die();

$global	= ob_get_clean();

echo str_replace('%PATH%', $image_path , $global);
	
die();

/* end of css/global.php */	