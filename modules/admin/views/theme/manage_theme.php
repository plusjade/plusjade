<?php
$file_array = array();		
$page_element = array(
	'master.html'	=> 'Master',
	'global.css'	=> 'CSS'
);
?>
<div id="common_tool_header" class="buttons">
	<div id="common_title">Current Theme: <?php echo ucwords($this->theme)?></div>
</div>


<div class="common_left_panel">
	<b>Need help?</b>
	<br><a href="http://plusjade.pbwiki.com/">View our Theme Guide.</a>
	
	<h3>Theme Pages</h3>
	<ul>
		<?php
		foreach($theme_files as $dir => $file)
			if(! is_array($file) )
				echo '<li><a href="'. url::site("get/theme/edit/$file") .'" rel="facebox" id="2">'.$file.'</a></li>';
		?>
	</ul>
</div>

<div class="common_main_panel">

</div>
