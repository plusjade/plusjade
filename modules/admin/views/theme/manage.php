

<div id="common_tool_header" class="buttons">
	<div id="common_title">Current Theme: <?php echo ucfirst($this->theme)?></div>
</div>


<div class="common_left_panel">
	<b>Need help?</b>
	<p><a href="http://plusjade.pbwiki.com/">View our Theme Guide.</a></p>
	
	<h3>Theme Pages</h3>
	<ul>
		<?php
		foreach($theme_files as $dir => $file)
		{
			if(! is_array($file) )
			{
				?>
				<li>
					<a href="<?php echo url::site("get/theme/edit/$file")?>" rel="facebox" id="2"><?php echo $file?></a>
				</li>
				<?php
			}
		}		
		?>
	</ul>
</div>

<div class="common_main_panel">

</div>
