

<div id="common_tool_header">
	<div id="common_title">Site Themes Manager</div>
</div>

<div id="files_browser_wrapper" class="theme_files">	
	<div class="common_left_panel">
		<div class="theme_index_block">
			<span class="icon add_page">&#160; &#160; </span> <a href="/get/theme/add_files/<?php echo $this->theme?>" rel="facebox" id="upload_files">Upload Files</a>
		</div>
		
		<div class="theme_index_block">
			<h3>Your Themes</h3>
			<select name="theme">
				<?php
				foreach($themes as $theme)
					if($this->theme == $theme)
						echo "<option selected=\"selected\">$theme</option>";
					else
						echo "<option>$theme</option>";
				?>
			</select>
			
			<br/><br/>
			<button id="load_theme" type="submit" class="jade_positive" style="width:140px">Load Theme</button>
			<br/><br/>
			<button id="activate_theme" type="submit" class="jade_positive" style="width:140px">Activate Theme</button>	
			<br/><br/>
			<button id="delete_theme" type="submit" class="jade_negative" style="width:140px">Delete Theme</button>		
		</div>
		
		<div class="theme_index_block">
			<h3>Create New Theme</h3>
			<input type="text" name="add_theme" class="auto_filename" maxlength="30" style="width:140px">
			<br><br><button id="add_theme" type="submit" class="jade_positive" style="width:140px">Add Theme</button>
		</div>
	</div>

	<div class="breadcrumb_wrapper" style="float:left; width:590px;">
		themes <span id="breadcrumb" rel="" class="theme"> / <a href="/get/theme/contents/<?php echo $this->theme?>" rel="ROOT" class="get_folder"><?php if('safe_mode' != $this->theme) echo $this->theme?></a></span>
	</div>	
	
	<div id="directory_window" class="common_main_panel full_height" rel="ROOT" style="height:350px; overflow:auto">
		<?php
			if(empty($files))
				echo 'Cannot edit safe-mode theme files. Load another theme.';
			else
				echo View::factory('theme/folder', array('files'=> $files))
		?>
	</div>

</div>
<div class="clearboth"></div>
