
<div id="theme_css_wrapper">

	<div style="padding:10px 0 10px 0;">
		<div style="float:right;">
			files: <select name="files" class="files_list">
				<?php
				foreach($css_files as $file)
					if('global.sass' == $file)
						echo "<option value=\"$file\" selected=\"selected\">$file</option>";
					else
						echo "<option value=\"$file\">$file</option>";
				?>
			</select>
			<button class="load_theme_file jade_positive" rel="css"  style="width:90px">Load</button>
			<button class="delete_theme_file jade_negative" rel="css"  style="width:90px"><span class="icon cross">&#160; &#160; </span>Delete</button>	
		</div>	

		<button class="show_theme_save jade_positive" rel="css" >Save as ...</button>
	</div>

	<div class="common_full_panel" style="border:0;clear:both; margin:0;padding:0">
			
		<div class="save_pane" style="display:none">
			<div class="contents">
				<span class="icon cross floatright">&#160; &#160;</span>		
				<h2><b>Save File</b></h2>		
				<div style="margin-bottom:10px">
					<h3><button class="update_theme_file jade_positive floatright" rel="css" >Update</button> As Update</h3>
					<select name="update_file" class="files_list">
						<?php foreach($css_files as $file) echo "<option value=\"$file\">$file</option>";?>
					</select>
				</div>
				
				<div>	
					<h3><button class="new_theme_file jade_positive floatright" rel="css" >Save as New</button> As New</h3>
					filename: <input type="text" name="new_file" class="auto_filename">.sass
				</div>
			</div>
		</div>
		
		Theme &#8594; <em><?php echo ucfirst($this->theme)?></em> : Stylesheet &#8594; <em><span class="current_file">global.css</span></em>
		<br/><textarea id="edit_css" name="contents" style="height:270px"><?php echo $contents?></textarea>

	</div>
</div>
		

<script type="text/javascript">	

</script>