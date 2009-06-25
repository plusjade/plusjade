

<div id="common_tool_header" class="buttons">
	<div id="common_title">Current Theme: <?php echo ucfirst($this->theme)?></div>
</div>


<div class="common_left_panel">
	<b>Need help?</b>
	<br><a href="http://plusjade.pbwiki.com/">View our Theme Guide.</a>
	
	
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

		<ul class="generic_tabs ui-tabs_nav" style="margin-bottom:0">
			<li><a href="#" class="update">Update</a></li>
			<li><a href="#" class="show_orig">Reset</a></li>
			<li><a href="#" class="show_stock">Show Stock</a></li>
		</ul>
		<textarea id="edit_css" name="contents" class="blah" style="height:200px"><?php echo $contents?></textarea>
	</div>
	
	<div id="stock_contents" style="display:none"><?php echo $contents?></div>
	

<script type="text/javascript">

	original = $('textarea#edit_css').val();
	
	$('.show_stock').click(function(){
		contents = $('#stock_contents').html();
		$('textarea#edit_css').val(contents);
		return false;
	});
	$('.show_orig').click(function(){
		$('textarea#edit_css').val(original);
		return false;
	});
	
	$('a.update').click(function(){
		value	= $('textarea#edit_css').val();
		css		= '<style id="global-style" type="text/css">'+ value +'</style>';
		$('#global-style').replaceWith(css);
		return false;
	});	
	
</script>





