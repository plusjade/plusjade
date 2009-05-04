<?php
header("Content-type: text/css");
header("Pragma: public");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
#header('Expires: ' . date('r',time() + 864000));
#header("Cache-Control: public");

/* @import url("http:#yui.yahooapis.com/2.6.0/build/reset-fonts-grids/reset-fonts-grids.css");  */

$site_name	= $this->site_name;
$theme		= $this->theme;
(array) $unique_tools;
(array) $all_tools;

ob_start();

foreach($unique_tools as $tool)
{
	$user_images	= url::site() . "data/$site_name/themes/$theme/modules/$tool/";	
	$user_css		= DATAPATH . "$site_name/themes/$theme/modules/$tool/stock.css";	
	$theme_tool_css	= APPPATH . "views/$theme/$tool/stock.css";
	$stock_tool_css	= MODPATH . "$tool/views/public_$tool/stock.css";
	$admin_css		= MODPATH . "$tool/views/edit_$tool/admin.css";

	#  Load user custom css if available.
	if ( file_exists($user_css) )
		readfile("$user_css");
	else
	{	
		# Load root css for tool.
		if ( file_exists($theme_tool_css) )
			readfile($theme_tool_css);
		elseif( file_exists($stock_tool_css) )
			readfile($stock_tool_css);
	}

	# Load admin backend css if logged in
	# This might hurt the cache if pages don't change though ??
	# Disable for now ...
	if( $this->client->logged_in() )
	{
		if ( file_exists($admin_css) )
		{
			echo "\n /* --- BACKEND - $tool --- */ \n";
			readfile($admin_css);
		}
	}		
}

/* 
 * Load custom uniqe tool css if exists
 * array $all_tools = "tools_list_id.tool_id"
 *
 */
foreach($all_tools as $tool)
{
	$pieces		= explode('.', $tool);
	$name		= $pieces['0'];
	$tool_id	= $pieces['1'];
	$css_path	= DATAPATH . "$site_name/tools_css/$name/$tool_id.css";	

	if ( file_exists($css_path) )
		readfile($css_path);
}
	# This is wrong FIX IT
	$image_path = "THIS_IS_WRONG/application/views/$theme/global/images";
	
	$contents =  ob_get_clean();
	$keys = '%PATH%';
	$replacements = $image_path;
	
	echo str_replace($keys, $replacements , $contents);
	
/*  end of tool.css*/
