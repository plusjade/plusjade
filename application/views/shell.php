<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php
	#$this->profiler = new Profiler;
	if(! empty($title) ) echo "<title>$title</title>\n\t";
	if(! empty($meta_tags) ) echo $meta_tags;
	if(! empty($load_css) ) echo $load_css;
	if(! empty($load_js) ) echo $load_js;
	?>
</head>

<body>
	<?php
	if(! empty($admin_panel) ) echo $admin_panel;
	
	# Required for all controllers passing with primary...	
	if(! empty($output) )
		echo $output;
	else
		echo $primary;
	
	?>
	<script type="text/javascript"> 
	  //<![CDATA[
		$(document).ready(function(){				
			<?php if(! empty($javascript) ) echo $javascript?>
			<?php if(! empty($readyJS) ) echo $readyJS?>
		});
	  //]]>
	</script> 
	<?php
	if(! empty($end_body) ) echo $end_body;
		
	#<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>	
	?>	
</body>
</html>
<!-- <3panda -->