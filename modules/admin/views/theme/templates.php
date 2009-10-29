
<div id="theme_templates_wrapper">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="show_theme_save jade_positive" rel="templates">Save Template As..</button>
		<div id="common_title">Theme &#8594; <u><?php echo ucfirst($this->theme)?></u> : Template &#8594; <u><span class="current_file">master.html</span></u></div>
	</div>

	<div style="text-align:right;padding-bottom:5px;">
		Templates: <select name="files" class="files_list">
			<?php
			foreach($templates as $template)
				if('master.html' == $template)
					echo "<option value=\"$template\" selected=\"selected\">$template</option>";
				else
					echo "<option value=\"$template\">$template</option>";
			?>
		</select>
		 <button class="load_theme_file jade_positive" rel="templates">Load</button>
		 <button class="load_theme_file jade_negative" rel="templates" style="width:90px"><span class="icon cross">&#160; &#160; </span>Delete</button>	
		 
	</div>

	<div class="common_full_panel" style="border:0">
		<div class="save_pane" style="display:none">
			<div class="contents">
				<span class="icon cross floatright">&#160; &#160;</span>		
				<h2><b>Save File</b></h2>		
				<div style="margin-bottom:10px">
					<h3><button class="update_theme_file jade_positive floatright" rel="templates">Update</button> As Update</h3>
					<select name="update_file" class="files_list">
						<?php foreach($templates as $template) echo "<option value=\"$template\">$template</option>";?>
					</select>
				</div>		
				<div>	
					<h3><button class="new_theme_file jade_positive floatright" rel="templates">Save as New</button> As New</h3>
					filename: <input type="text" name="new_file" class="auto_filename" rel="text_req">.html
				</div>
			</div>
		</div>

		<textarea id="edit_html" class="full_height" style="height:300px"><?php echo $contents?></textarea>
	</div>
</div>