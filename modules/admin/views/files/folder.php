
<?php
$class = ('albums' == $mode) ? 'to_album' : 'to_editor';
$tmb_size = 75;

foreach ($files as $path => $data)
{
	$data = explode('|', $data);
	list($type, $name) = $data;
	
	# show files
	if('file' == $type)
	{
		$css_id			= str_replace('.', '_', $name);
		$path			= str_replace(':', '/', $path);
		$url			= $this->assets->assets_url() .'/'. $path ;
		$url_thumb 		= image::thumb($url);
		$display_name	= ('10' < strlen($name)) ? substr($name, 0, 10).'...' : $name;
		$ext			= strtolower(strrchr($name, "."));
		
		# if image show thumbnail
		$img = ((array_key_exists($ext, $image_types))) ?
			"<img src='$url_thumb' class='$class image_file' rel='$url' title='$name' width='$tmb_size' height='$tmb_size' alt='$path'>"
			: "<img src='/_assets/images/admin/file.gif' title='$name' width='$tmb_size' height='$tmb_size' alt=''>";
		
		?>
		<div id="<?php echo $css_id?>" class="file_asset asset" rel="<?php echo $path?>">
			<span class="icon info">&#160; &#160;</span>
			<?php echo $img?>
			<span title="<?php echo $name?>"><?php echo $display_name?></span>
		</div>
		<?php
	}
	else
	{	
		# show folders
		?>
		<div id="<?php echo $name?>" class="folder_asset asset" rel="<?php echo $path?>">
			<img src="/_assets/images/admin/folder.png" rel="<?php echo $path?>" title="<?php echo $name?>" width="<?php echo $tmb_size?>px" height="<?php echo $tmb_size?>px" alt="">
			<span><a href="/get/files/contents?dir=<?php echo "$path&mode=$mode"?>" class="get_folder" rel="<?php echo $path?>"><?php echo $name?></a></span>
		</div>
		<?php
	}
}
?>

<script type="text/javascript">
	$('#files_browser_wrapper').selectable({ filter: 'img', delay: 20});
</script>



