
<?php 
extract($vars);
$type = '';
$display_name	= ('10' < strlen($filename)) ? substr($filename, 0, 10).'...' : $filename;
?>
<div id="page_wrapper_<?php echo $id?>" class="<?php echo $visibility?> asset">
	
	<?php
	if(TRUE == $is_folder)
	{
		$type = 'folder';
		?>
			<img src="/_assets/images/admin/folder.png" rel="<?php echo $full_path?>" class="open_folder"> 
			
		<?php 
	}
	else
		echo "<img src=\"/_assets/images/admin/file.gif\" class=\"file_options\">";
	
	
	if(isset($type) AND 'folder' == $type)
		$icon = '<span class="icon page">&#160; &#160;</span>';
	else
		$icon = ((TRUE == $is_protected)) 
			? "<span class='icon shield' title='$page_builder'>&#160; &#160; </span>"
			: '';
	?>

	<div title="<?php echo $filename?>"><?php echo "$icon $display_name"?></div>
	
	<ul class="option_list">
	
		<?php if(TRUE == $is_protected):?>
			<li><span class="icon shield">&#160; &#160; </span> <?php echo $page_builder?></li>
		<?php endif;?>

		<li>
			<span class="icon magnify">&#160; &#160; </span> 
			<a href="<?php echo url::site($full_path)?>" class="" title="Go to Page: <?php echo url::site($full_path)?>">
			Go to page
			</a>
		</li>
		
		<li>
			<span class="icon cog"> &#160; &#160; </span> 
			<a href="/get/page/settings/<?php echo $id?>" rel="facebox" id="2">
				Settings
			</a>
		</li>
		
		<?php if(FALSE == $is_folder AND FALSE == $is_protected):?>
			<li>
				<span class="icon add_folder"> &#160; &#160; </span> 
				 <a href="#" class="folderize" id="<?php echo $id?>" rel="<?php echo $full_path?>" title="<?php echo $filename?>">Make folder</a>
			</li>
		<?php endif;?>
		
		<li>
			<span class="icon cross">&#160; &#160; </span> 
			<a href="/get/page/delete/<?php echo $id?>" id="<?php echo $id?>"  class="delete_page" rel="<?php echo $type?>">Delete</a>
		</li>
	</ul>
</div>

