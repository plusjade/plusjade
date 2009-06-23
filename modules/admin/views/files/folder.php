

<?php
$url_path = Assets::url_path_direct();
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
	
	if('file' == $type)
	{
		$id			= str_replace('.', '_', $name);
		$jw_path	= str_replace(':', '/', $path);
		$url		= "$url_path/$jw_path";
		
		$display_name = ('10' < strlen($name)) ? substr($name, 0, 10).'...' : $name;
		
		$ext = strtolower(substr(strrchr($name, "."), 1));
		
		$img = ((array_key_exists($ext, $image_types))) ?
			"<img src=\"$url\" width=\"75\" height=\"75\" alt=\"\">"
			: "<img src=\"/_assets/images/admin/file.jpg\" width=\"75\" height=\"75\" alt=\"\">";
		
		?>
		<div id="<?php echo $id?>" class="file_asset" rel="<?php echo $name?>">
			<span class="icon cross">&nbsp; &nbsp; </span> 
			<a href="<?php echo $url?>" class="place_file">Place</a>		
			<?php echo $img?>
			<div title="<?php echo $name?>"><?php echo $display_name?></div>
		</div>
		<?php
	}
	else
	{
		$delete = ('tools' == $name AND 'tools' == $path) ? '' : '<span class="icon cross">&nbsp; &nbsp; </span> ';
		?>
		<div id="<?php echo $name?>" class="folder_asset" rel="<?php echo $path?>">
			<?php echo $delete?>
			<br><img src="/_assets/images/admin/folder.jpg" href="/get/files/contents/<?php echo $path?>" class="get_folder" rel="<?php echo $path?>"  alt="">
			<br><a href="/get/files/contents/<?php echo $path?>" class="get_folder" rel="<?php echo $path?>"><?php echo $name?></a>
		</div>
		<?php
	}
}
?>