
<?php extract($vars)?>
<?php $type = ''?>
<div id="page_wrapper_<?php echo $id?>" class="<?php echo $visibility?> asset">
	<?php
	if(TRUE == $is_folder)
	{
		$type = 'folder';
		?>
		<div class="folder_bar">
			<a href="/<?php echo $full_path?>" class="open_folder" rel="<?php echo $full_path?>">
				<span class="icon folder open_folder" rel="<?php echo $full_path?>">&#160; &#160;</span>
			</a>
		</div>
		<?php 
	}
	?>
	<div class="page_bar">
	
		<div>
			<?php
			if(TRUE == $is_protected)
			{		
				?>
				<span class="icon shield" title="<?php echo $page_builder?>">&#160; &#160; </span>
				<?php
			}
			?>
		</div>
		
		<div>
			<a href="<?php echo url::site($full_path)?>" class="" title="Go to Page: <?php echo url::site($full_path)?>">
				<span class="icon magnify">&#160; &#160; </span>
			</a>
		</div>
		
		<div>
			<a href="/get/page/settings/<?php echo $id?>" title="Page Settings">
				<span class="icon cog icon_facebox"> &#160; &#160; </span>
			</a>
		</div>
		
		<div>
			<?php
			if(FALSE == $is_folder AND FALSE == $is_protected)
			{		
				?>
				<span class="icon add_folder folderize" id="<?php echo $id?>" rel="<?php echo $full_path?>"> &#160; &#160; </span>
				<?php
			}
			?>
		</div>
		
		<div>
			<a href="/get/page/delete/<?php echo $id?>" id="<?php echo $id?>" title="Delete Page">
				<span class="icon cross delete_page" rel="<?php echo $type?>">&#160; &#160; </span>
			</a>
		</div>
		
	</div>

	<div class="page_icon">
		<span class="icon page">&#160; &#160; </span>
		<?php echo $filename?>
	</div>
	
</div>

