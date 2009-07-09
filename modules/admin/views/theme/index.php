

<div id="common_tool_header">
	<div id="common_title">Theme Files Browser</div>
</div>

<div id="files_browser_wrapper">
	
	<div class="common_left_panel">

		<div style="padding:8px; border:1px dashed #ccc; margin-bottom:10px">
			<span class="icon add_page">&#160; &#160; </span> <a href="/get/theme/add_files/<?php echo $this->theme?>" rel="facebox" id="upload_files">Upload Files</a>
		</div>
		
		<div style="padding:8px; border:1px dashed #ccc; margin-bottom:10px">
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
			
			<p>
				<button id="load_theme" type="submit" class="jade_positive" style="width:140px">Load Theme</button>
			</p>
				<button id="activate_theme" type="submit" class="jade_positive" style="width:140px">Activate Theme</button>	
			<p>
				<button id="delete_theme" type="submit" class="jade_negative" style="width:140px">Delete Theme</button>	
			</p>
		</div>
		
		<div style="padding:8px; border:1px dashed #ccc">
			<h3>Create New Theme</h3>
			<input type="text" name="add_theme" class="auto_filename" maxlength="30" style="width:140px">
			<br><br><button id="add_theme" type="submit" class="jade_positive" style="width:140px">Add Theme</button>
		</div>
		<!-- TODO: enable theme uploading via zip packager -->
		
	</div>

	
	<div class="breadcrumb_wrapper">
		themes <span id="breadcrumb" rel=""> / <a href="/get/theme/contents/<?php echo $this->theme?>" rel="ROOT" class="get_folder"><?php if('safe_mode' != $this->theme) echo $this->theme?></a></span>
	</div>	
	
	
	<div id="directory_window" class="common_main_panel full_height" rel="ROOT" style="height:350px; overflow:auto">
		<?php
			if(empty($files))
				echo 'Cannot edit safe-mode theme files. Load another theme.';
			else
				echo View::factory('theme/folder', array('files'=> $files))?>
	</div>

</div>
<div class="clearboth"></div>




<script type="text/javascript">

/* ------------------ LOADING AND DELETING  ------------------ */ 
	
// load a theme button
	$("#load_theme").click(function(){
		theme = $("select[name='theme'] option:selected").text();		
		$('#directory_window').html('Loading file...');
		$.get('/get/theme/contents/'+ theme,
			function(data){
				$('#directory_window').html(data);
				link = ' / <a href="/get/theme/contents/'+ theme +'" class="get_folder">'+ theme +'</a>';
				$('#breadcrumb').html(link);
				$('#upload_files').attr('href','/get/theme/add_files/'+theme);
				
			}
		);
		return false;
	});

// activate a theme button
	$("#activate_theme").click(function(){
		var theme = $("select[name='theme'] option:selected").text();		
		if('<?php echo $this->theme?>' == theme) {
			alert('Theme already active.');
			return false;
		}
		if(confirm('Activate this theme: ' + theme + '?')) {	
			$('.facebox .show_submit').show();
			$.post('/get/theme/change', {theme: theme}, function(data){
				if('TRUE' == data)
					location.reload();
				else {
					alert(data);
					$.facebox.close();
				}
			});
		}
		return false;
	});
	
// delete a theme button
	$("#delete_theme").click(function(){
		theme = $("select[name='theme'] option:selected").text();
		if('<?php echo $this->theme?>' == theme) {
			alert('Cannot delete active theme.');
			return false;
		}	
		if(confirm('This cannot be undone. Delete this entire theme folder?')) {
			$.get('/get/theme/delete/'+ theme,
				function(data){
					$("select[name='theme'] option:selected").remove();	
					$('#directory_window').empty();
					$('#breadcrumb').empty();
					$('#upload_files').attr('href','/get/theme/add_files/<?php echo $this->theme?>');
				}
			);
		}
		return false;
	});


/* ------------------ ADDING A NEW THEME  ------------------ */ 

// add a theme button
	$("#add_theme").click(function(){
		theme = $("input[name='add_theme']").val();
		if(!theme || 'safe_mode' == theme){
			alert('specify a theme name other than "safe_mode"');
			return false;
		}
		$.post('/get/theme/add_theme', {theme : theme},
			function(data){
				$("select[name='theme']").append('<option>'+ data +'</option>');
				$('#show_response_beta').html(data);
			}
		);
		return false;
	});


/* ------------------ FILE BROWSING FUNCTIONS  ------------------ */ 
	
	$('#files_browser_wrapper').click($.delegate({
	
		// open a folder and load contents
		'a.get_folder, img.get_folder':function(e){
			$('#directory_window').html('<div lass="ajax_loading">Loading...</div>');
			url = $(e.target).attr('href');
			$('#directory_window').load(url);
			path = $(e.target).attr('rel');
			
			// add the breadcrumb
			if('ROOT' == path){
				folder_string = '';
				path = '';
			}
			else{

				var folder_array = path.split(':');
				var folder_string = '';	
				folder_count = folder_array.length;
				// This takes a string ex: one/two/three
				// and outputs all combinations of the nest.
				// ex: one, one/two, one/two/three.
				for (i=0; i < folder_count; i++){
					result_string = $.strstr(path, folder_array[i], true) + folder_array[i];
					folder_string += ' / <a href="/get/theme/contents/'+ result_string +'" rel="'+ result_string +'" class="get_folder">'+ folder_array[i] +'</a>';
				}
			}
			$('#breadcrumb').attr('rel', path).html(folder_string);			
		
			return false;
		},
		
		// add a file asset
		'a.add_asset': function(e){
			$.facebox(function(){
				path = $('#breadcrumb').attr('rel');
				
				$.get(e.target.href +'/'+ path,
				function(data){
					$.facebox(data, false, 'facebox_2');
				});
			}, false, 'facebox_2');
			return false;
		},
		

		// delete a file asset
		'div.file_asset span.cross': function(e){
			if(confirm('This cannot be undone. Delete this file?'))
			{
				path	= $('#breadcrumb').attr('rel');
				file	= $(e.target).parent('div').attr('rel');
				ufile	= ((path)) ? ':' : '';
				ufile	+= file;
				$.get('/get/theme/delete/'+ path + ufile,
					function(data){
						file = file.replace('.', '_')
						$('#directory_window #' + file).remove();
						$('#show_response_beta').html(data);
					}
				);
			}
			return false;
		}
	}));
</script>
