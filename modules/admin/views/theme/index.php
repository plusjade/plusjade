

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
				<button id="load_theme" type="submit" class="jade_positive">Load Theme</button>
			</p>
			
			<button id="delete_theme" type="submit" class="jade_negative">Delete Theme</button>	
		</div>
		
		<div style="padding:8px; border:1px dashed #ccc">
			<h3>Create New Theme</h3>
			<input type="text" name="add_theme" maxlength="30">
			<br><br><button id="add_theme" type="submit" class="jade_positive">Add Theme</button>
		</div>
		<!-- TODO: enable theme uploading via zip packager -->
		
	</div>

	
	<div class="breadcrumb_wrapper">
		themes <span id="breadcrumb" rel=""> / <a href="/get/theme/contents/<?php echo $this->theme?>" rel="ROOT" class="get_folder"><?php echo $this->theme?></a></span>
	</div>	
	
	
	<div id="directory_window" class="common_main_panel full_height" rel="ROOT" style="height:350px; overflow:auto">
		<?php echo View::factory('theme/folder', array('files'=> $files, 'is_editor'=> $is_editor))?>
	</div>

</div>
<div class="clearboth"></div>




<script type="text/javascript">

	
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

	
	
	// delete a theme button
	$("#delete_theme").click(function(){
		theme = $("select[name='theme'] option:selected").text();		
		if(confirm('This cannot be undone. Delete this entire theme folder?')) {
			$.get('/get/theme/delete_theme/'+ theme,
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
	
// sanitize the new page name
	$("input[name='add_theme']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
		$(this).val(input);
	});
// add a theme button
	$("#add_theme").click(function(){
		theme = $("input[name='add_theme']").val();
		if(!theme){
			alert('specify a theme name');
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
				$.get('/get/theme/delete_browser/'+ path + ufile,
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
