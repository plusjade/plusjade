<?php
$image_types = array(
	'jpg'	=> 'jpeg',
	'jpeg'	=> 'jpeg',
	'png'	=> 'png',
	'gif'	=> 'gif',
	'tiff'	=> 'tiff',
	'bmp'	=> 'bmp',
);
foreach ($files as $path => $data)
{
	$data = explode('|', $data);
	list($type, $name) = $data;
	
	# show files
	if('file' == $type)
	{
		$css_id			= str_replace('.', '_', $name);
		$url			= Assets::assets_url() .'/'. str_replace(':', '/', $path);
		$url_thumb		= str_replace($name, "_sm/$name", $url);
		$display_name	= ('10' < strlen($name)) ? substr($name, 0, 10).'...' : $name;
		$ext			= strtolower(substr(strrchr($name, "."), 1));
		
		# if image, show the thumbnail version
		$img = ((array_key_exists($ext, $image_types))) ?
			"<img src=\"$url_thumb\" class=\"place_file\" rel=\"$url\" width=\"75\" height=\"75\" alt=\"\">"
			: "<img src=\"/_assets/images/admin/file.jpg\" width=\"75\" height=\"75\" alt=\"\">";
		
		?>
		<div id="<?php echo $css_id?>" class="file_asset asset" rel="<?php echo $name?>">
			<?php echo $img?>
			<br><span title="<?php echo $name?>"><?php echo $display_name?></span>
			 <span class="icon cross">&nbsp; &nbsp; </span>
		</div>
		<?php
	}
	# show folders
	else
	{
		$delete = ('tools' == $name AND 'tools' == $path) ? '' : '<span class="icon cross">&nbsp; &nbsp; </span> ';
		?>
		<div id="<?php echo $name?>" class="folder_asset asset" rel="<?php echo $path?>">
			<img src="/_assets/images/admin/folder.jpg" href="/get/files/contents/<?php echo $path?>" class="get_folder" rel="<?php echo $path?>"  alt="">
			<br><a href="/get/files/contents/<?php echo $path?>" class="get_folder" rel="<?php echo $path?>"><?php echo $name?></a>
			<?php echo $delete?>
		</div>
		<?php
	}
}
?>