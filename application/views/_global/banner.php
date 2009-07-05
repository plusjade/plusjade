<span id="BANNER">
	<?php
	if( empty($this->banner) )
	{
		?>
		<div id="text_logo">
			<a href="<?php echo url::site()?>"><?php echo $this->site_name?></a>
		</div>
		<?php
	}
	else
	{
		?>	
		<a href="<?php echo url::site()?>">
			<img src="<?php echo Assets::url_path_direct("banners/$this->banner")?>" id="header_banner" alt="<?php echo $this->banner?>">
		</a>
		<?php
	}
	?>
</span>