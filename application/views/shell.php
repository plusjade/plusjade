<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php
	if(! empty($title) ) echo '<title>'.$title.'</title>'."\n\t";
	if(! empty($meta_tags) ) echo $meta_tags;
	if(! empty($load_css) ) echo $load_css;
	if(! empty($load_js) ) echo $load_js;
	?>
</head>

<body>
	<?php
	if(! empty($admin_panel) ) echo view::factory('admin/admin_panel');
	
	# Helper tools ...
	$header	= View::factory("_global/header");
	$menu	= View::factory("_global/menu");
	$tracker_path = DOCROOT."data/$site_name/tracker.html";	
	
	# Required for all controllers passing with primary...
	# TODO: update this ...
	if( empty($containers['1']) ) $containers['1'] = $primary;
	
	ob_start();
	
	if ( file_exists("$custom_include/master.html") )
		readfile("$custom_include/master.html");	
	else
		readfile(APPPATH."views/$theme_name/master.html");

	$master = ob_get_clean();

	$keys = array(
		'%HEADER%',
		'%MENU%',
	);
	$replacements = array(
		$header,
		$menu,
	);
	
	# 5 containers
	foreach($containers as $key => $content)
	{
		array_push($keys, "%CONTAINER_$key%");
		array_push($replacements, $content);
	}
	
	# TODO: Look into compression for this ...
	echo str_replace($keys, $replacements , $master);
			
	?>
	<script type="text/javascript"> 
	  //<![CDATA[
		$(document).ready(function(){				
			<?php if(! empty($readyJS) ) echo $readyJS?> 
		});	
			<?php if(! empty($rootJS) ) echo $rootJS?>  
	  //]]>
	</script> 
	<?php
	# It is bad to open 2 buffers, fix this
	ob_start();
	
	if ( file_exists("$tracker_path") )
		readfile("$tracker_path");
		
	echo ob_get_clean();
		
	#<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>	
	?>
	
</body>
</html>
<!-- panda<3! -->