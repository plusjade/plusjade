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
	$user	= $pieces[1];
	$theme	= $pieces[2];		
}
else
{
	$user	= $_GET['u'];
	$theme	= $_GET['t'];
}
	
$user_css = "{$_SERVER[DOCUMENT_ROOT]}/data/{$user}/themes/{$theme}/global";	
$root_css = "{$_SERVER[DOCUMENT_ROOT]}/application/views/{$theme}/global";

# IMAGES: must have absolute paths to user directory
$user_images = "http://{$_SERVER['HTTP_HOST']}/data/{$user}/themes/{$theme}/global/images/";
$root_images = "http://{$_SERVER['HTTP_HOST']}/application/views/{$theme}/global/images/";

/* TODO: CHANGE THIS !!!! */ 
#-----------------------------------------
# load user custom global css if available
if (file_exists("$user_css/css_values.php"))
{
	$image_path = $user_images;
	include_once("{$user_css}/css_values.php");
}
else
{
	$image_path = $root_images;
	include_once("$root_css/css_values.php");	
}

# loop through the custom fields to set background vars
foreach($background as $key => $var)
{
	if(strstr($var, '.'))
		$background[$key] = "background:url('{$image_path}{$var}') repeat left 1px";
	else
		$background[$key] = "background:{$var}";
}		
	
# load custom global root css if available
# else load global root css from theme

if ( file_exists("$user_css/global.css") )
	echo readfile("$user_css/global.css");		
else
	echo readfile("$root_css/global.css");	


	
# load static_helpers.css
$static_helpers = "{$_SERVER[DOCUMENT_ROOT]}/application/views/_global/static_helpers.css";

echo readfile($static_helpers);

/* end of css/global.css */	