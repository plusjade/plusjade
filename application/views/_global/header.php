<?php
if( empty($_SESSION['banner']) )
{
	?>
	<div id="text_logo">
		<a href="<?php echo url::site()?>"><?php echo $site_name?></a>
	</div>
	<?php
}
else
{
	?>	
	<a href="<?php echo url::site()?>">
		<img src="<?php echo $data_path?>/assets/images/banners/<?php echo $_SESSION['banner']?>" id="header_banner">
	</a>
	<?php
}