
<div id="common_tool_header" class="buttons">
	<div id="common_title">All Site Pages</div>
</div>

<div id="common_tool_info">
	Load pages for editing by clicking on the page name link.
	<br><b style="color:#ccc">Gray</b> links are accessible but not on the menu.
	<br><b style="color:red">Red</b> links are not publicly accessible.
</div>

<style type="text/css">
	#all_pages_list li{
	padding:5px;
	}
	#all_pages_list {
	}
</style>
<ul id="all_pages_list">
	<?php
	# setup the page list.
	foreach($pages as $page)
	{
		$class='';
		$page_name = $page->page_name;
		if($page->menu == 'no') $class = 'class="no_menu"';
		if($page->enable == 'no') $class = 'class="no_access"';	
		
		?>
		<li id="page_<?php echo $page->id?>" <?php echo $class?>>
			<?php if(array_key_exists($page_name, $protected_pages)) echo "<img src='".url::image_path('admin/shield.png')."' title='$protected_pages[$page_name]' alt='' >"?>
			<a href="<?php echo url::site($page_name)?>"><?php echo $page->label?> - <small><?php echo url::site($page->page_name)?></small></a>
			<a href="/get/page/delete/<?php echo $page->id?>" class="delete_page" id="<?php echo $page->id?>">delete</a>
		</li>		
		<?php
	}
	?>
</ul>

<script type="text/javascript">

</script>
