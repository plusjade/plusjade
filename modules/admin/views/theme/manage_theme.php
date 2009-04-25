<?php
$file_array = array();		
$page_element = array(
	'master.html'	=> 'Master',
	'global.css'	=> 'CSS'
);
/*

# Make links	
function _link($type, $page)
{
	return '<a href="'.url::site("get/theme/$type/$page").'" rel="facebox" id="blah">' . $type .' custom '.$page.'</a>';	
}
*/
?>
<div id="common_tool_header" class="buttons" style="width:750px">
	<a href="/get/theme/change" rel="facebox" class="jade_positive">Change Theme</a>	
	<div id="common_title">Current Theme: <?php echo ucwords($this->theme)?></div>
</div>

<div id="common_tool_info">
		<b>Need help?</b> <a href="http://plusjade.pbwiki.com/">View our Theme Guide.</a>
</div>

<div id="theme_global_wrapper">

<?php

	foreach($theme_files as $dir => $file)
	{
		if(! is_array($file) )
		{
			echo '<a href="'.url::site("get/theme/edit/$file").'" rel="facebox" id="2">'.$file.'</a><br>';
		
		}
		else
		{	/*
			echo $dir.'<br><br>';
			foreach($file as $filename)
			{
				echo $filename.'<br>';
				//echo '<a href="'.url::site("get/theme/edit/$filename").'" rel="facebox" id="2">'.$filename.'</a><br>';
			}
			*/
		}
	}
	
	#echo '<pre>';print_r($theme_files);echo '</pre>';
?>


</div>

<div class="clearboth"></div>

