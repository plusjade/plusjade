<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
<head>
	<?php
	if(!empty($title)) echo "<title>$title</title>\n\t";
	if(!empty($meta_tags)) echo $meta_tags;
	if(!empty($load_css)) echo $load_css;
	if(!empty($load_js)) echo $load_js;
	?>
</head>

<body>
	<?php
	if(!empty($admin_panel)) echo $admin_panel;
	if(!empty($error)) echo $error;
	
	if(!empty($output)) echo $output;
	?>
	<script type="text/javascript"> 
	  //<![CDATA[
		$(document).ready(function(){				
			<?php if(!empty($javascript)) echo $javascript?>
			<?php if(!empty($public_javascript)) echo $public_javascript?>
		});
	  //]]>
	</script>
	<?php
	if(!empty($end_body)) echo $end_body;
		
	#<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>	
	?>
</body>
</html>
<!-- <3panda -->