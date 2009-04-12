<?php
header("Content-type: text/css");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");

/*
 * we need this because GET variables do not  
 * pass on live linode server. (no idea why)
 */
$user	= $_GET['u'];
$theme	= $_GET['t'];
	
if(! empty($_SERVER['PATH_INFO']) )
{
	$pieces	= explode('/', $_SERVER['PATH_INFO']);
	$user	= $pieces['1'];
	$theme	= $pieces['2'];		
}
$ROOT			= $_SERVER['DOCUMENT_ROOT'];
$user_css		= "$ROOT/data/$user/themes/$theme/global/global.css";	
$root_css		= "$ROOT/application/views/$theme/global/global.css";
$image_path		= "/data/$user/themes/$theme/global/images";
$static_helpers	= "$ROOT/application/views/_global/static_helpers.css";

ob_start();

# Load static_helpers.css first so they can be overwritten
readfile($static_helpers);

/*
 * load custom global root css if available
 * else load global root css from theme
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

/* end of css/global.css */	