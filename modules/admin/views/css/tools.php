<?php
header("Content-type: text/css");
header("Pragma: public");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
#header('Expires: ' . date('r',time() + 864000));
#header("Cache-Control: public");

/* @import url("http:#yui.yahooapis.com/2.6.0/build/reset-fonts-grids/reset-fonts-grids.css");  */

$user	= $this->site_name;
$theme	= $this->theme;
(array) $all_tools;
(array) $generic_tools;

foreach($generic_tools as $tool)
{
	$user_css		= DOCROOT . "/data/$user/themes/$theme/modules/$tool/css.php";	
	$user_images	= url::site() . "data/$user/themes/$theme/modules/$tool/";	

	$theme_tool_css	= DOCROOT . "/application/views/$theme/$tool/css.php";
	$stock_tool_css	= DOCROOT . "/modules/$tool/views/$tool/css.php";
	$stock_backend	= DOCROOT . "/modules/$tool/views/$tool/edit/css.php";

	#  Load user custom css if available.
	if (file_exists($user_css))
		include_once("$user_css");
	else
	{	
		# Load root css for tool.
		if ( file_exists($theme_tool_css) )
			include_once($theme_tool_css);
		elseif( file_exists($stock_tool_css) )
			include_once($stock_tool_css);
	}

	# Load backend css if logged in
	# This might hurt the cache if pages don't change though ??
	# Disable for now ...
	#if($logged_in)
	#{
		if (file_exists($stock_backend))
		{
			echo "\n /* --- BACKEND - $tool --- */ \n";
			include_once($stock_backend);
		}
	#}
		
}

# Load custom uniqe tool css if exists
foreach($all_tools as $tool)
{
	$tool_ids	= explode('.', $tool);
	$name_id	= $tool_ids['0'];
	$tool_id	= $tool_ids['1'];
	$name 		= $tools_list[$name_id];
	
	
	$user_css	= DOCROOT . "/data/$user/tools_css/$name/$tool_id.css";	
	//$user_images	= url::site() . "data/$user/themes/$theme/modules/$tool/";	
	
	
	#  Load user custom css if available.
	if ( file_exists($user_css) )
		include_once("$user_css");

}

/*  end of tool.css*/
