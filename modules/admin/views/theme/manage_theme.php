<?php
$file_array = array();		
$page_element = array(
	'master.html'	=> 'Master',
	'global.css'	=> 'CSS'
);
/*
# Check the cascades to find which file is in use.
foreach($page_element as $file => $page)
{
	if( in_array($file, $custom_flat) )
		$file_array[$page] = 'custom';
	elseif( in_array($file, $theme_flat) )
		$file_array[$page] = 'theme';
	else
		$file_array[$page] = 'root';
}

# Make links	
function _link($type, $page)
{
	return '<a href="'.url::site("get/theme/$type/$page").'" rel="facebox" id="blah">' . $type .' custom '.$page.'</a>';	
}
*/
?>
<div id="common_tool_header" class="buttons" style="width:750px">
	<a href="/get/theme/change" rel="facebox" class="jade_positive">Change Theme</a>	
	<div id="common_title">Current Theme: <?php echo ucwords($theme_name)?></div>
</div>

<div id="common_tool_info">
		<b>Need help?</b> <a href="http://plusjade.pbwiki.com/">View our Theme Guide.</a>
</div>

<div id="theme_global_wrapper">

<?php
	echo '<pre>';
	print_r($theme_files);
	echo '</pre>';
/*
	foreach($theme_files as $files)
	{
	
	}
*/
?>


</div>

<div id="theme_layout_wrapper">


	
</div>			

<div class="clearboth"></div>

