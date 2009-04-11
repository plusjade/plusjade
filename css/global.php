<?php
header("Content-type: text/css");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");

# we need this because for an unknown reason, the GET variables do not
# pass on our live linode server.
if(!empty($_SERVER['PATH_INFO']))
{
	$pieces	= explode('/', $_SERVER['PATH_INFO']);
	$user	= $pieces['1'];
	$theme	= $pieces['2'];		
}
else
{
	$user	= $_GET['u'];
	$theme	= $_GET['t'];
}

$user_css = "{$_SERVER[DOCUMENT_ROOT]}/data/{$user}/themes/{$theme}/global";	
$root_css = "{$_SERVER[DOCUMENT_ROOT]}/application/views/{$theme}/global";

# IMAGES: must have absolute paths to user directory
$user_images = "/data/{$user}/themes/$theme/global/images";
$root_images = "/application/views/$theme/global/images";


# load custom global root css if available
# else load global root css from theme
#ob_start();

if ( file_exists("$user_css/global.css") )
{
	$global = file_get_contents("$user_css/global.css");
}	
elseif( file_exists("$root_css/global.css") )
{
	$global = file_get_contents("$root_css/global.css");
}
else
	die();

	# !NOTE: Decide whether to clone all assets into client data folder
	$keys = '%PATH%';
	$replacements = $root_images;
	
	echo str_replace($keys, $replacements , $global);
	
	
# load static_helpers.css
$static_helpers = "{$_SERVER[DOCUMENT_ROOT]}/application/views/_global/static_helpers.css";
ob_start();
	readfile($static_helpers);
echo ob_get_clean();

die();
/* end of css/global.css */	