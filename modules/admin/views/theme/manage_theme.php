<?php
$file_array = array();		
$page_element = array(
	'master.php'	=> 'Master',
	'header.php'	=> 'Header',
	'menu.php'		=> 'Menu',
	'footer.php'	=> 'Footer',
	'css.php'		=> 'CSS'
);

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
?>
<div id="common_tool_header" class="buttons" style="width:750px">
	<a href="/get/theme/change" rel="facebox" class="jade_positive">Change Theme</a>	
	<div id="common_title">Current Theme: <?php echo ucwords($theme_name)?></div>
</div>

<div id="common_tool_info">
		<b>Need help?</b> <a href="http://plusjade.pbwiki.com/">View our Theme Guide.</a>
</div>

<div id="theme_global_wrapper">

	<div id="master_block" class="<?php echo $file_array['Master']?>_file">
		
		<span>Master <small>(<?php echo $file_array['Master'] ?>)</small></span>
		<?php 
		if( 'custom' == $file_array['Master'] )
		{
			echo _link('edit', 'master');
			echo _link('delete', 'master');
		}
		else
			echo _link('create', 'master');
		?>
	</div>

	<div id="css_block" class="<?php echo $file_array['CSS']?>_file">
		
		<span>Global CSS <small>(<?php echo $file_array['CSS'] ?>)</small></span>
		<?php 
		if( 'custom' == $file_array['CSS'] )
		{
			echo _link('edit', 'css');
			echo _link('delete', 'css');
		}
		else
			echo _link('create', 'css');
		?>
	</div>
	
</div>

<div id="theme_layout_wrapper">

	<p id="page_layout"> Sample Page Layout</p>
	
	<div id="header_block" class="<?php echo $file_array['Header']?>_file">
		<span>Header <small>(<?php echo $file_array['Header'] ?>)</small></span>
		<?php 
		if( 'custom' == $file_array['Header'] )
		{
			echo _link('edit', 'header');
			echo _link('delete', 'header');
		}
		else
			echo _link('create', 'header');
		?>
	</div>

	<div id="menu_block" class="<?php echo $file_array['Menu']?>_file">
		<span>Menu <small>(<?php echo $file_array['Menu'] ?>)</small></span>
		<?php 
		if( 'custom' == $file_array['Menu'] )
		{
			echo _link('edit', 'menu');
			echo _link('delete', 'menu');
		}
		else
			echo _link('create', 'menu');
		?>
	</div>	
	
	<div id="content_block">
		<span>Content</span>
		<br>
		<br>(Tools get placed here)
	</div>	
	
	<div id="footer_block" class="<?php echo $file_array['Footer']?>_file">
		<span>Footer <small>(<?php echo $file_array['Footer'] ?>)</small></span>
		<?php 
		if( 'custom' == $file_array['Footer'] )
		{
			echo _link('edit', 'footer');
			echo _link('delete', 'footer');
		}
		else
			echo _link('create', 'footer');
		?>
	</div>	
	
	
</div>			

<div class="clearboth"></div>

<?php
/*
<h2>Customize Global Tool CSS</h2>

<b>Enabled Custom Tools:</b><br>
<div class="indent modules">
	<?php 
	# list module css files that exist in user data folder.
	foreach($data_modules as $name)
	{
		?>
		<div class="green_module">
			<span class="module_actions">
				<a href="/get/theme/edit/<?php echo $name?>/m#fragment-4">Edit</a>
				<a href="/get/theme/delete/<?php echo $name?>/m">Delete</a>
			</span>
			<?php echo $name ?>
		</div>
		<?php
	}
	?>	
</div>

<b>Tools Available:</b><br>
<div class="indent modules">
	<?php 
	foreach($tools as $module)
	{
		if(in_array($module->name, $data_modules))
			echo '<div class="gray_module">'.$module->name.'<small>(in use)</small></div>';
		else
		{
			?>
			<div class="blue_module">
				<span class="module_actions">
					<a href="/get/theme/create/<?php echo $module->name?>/m">Create</a>
				</span>
				<?php echo $module->name?>
			</div>	
			<?php		
		}
	}
	?>
</div>
*/
?>