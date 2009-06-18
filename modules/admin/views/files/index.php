
<style type="text/css">
.file_asset, .folder_asset{
	height:110px;
	width:110px;
	float:left;
	margin:3px;
	background:#fff;
	border:1px solid #eee;
	font-size:0.9em;
	text-align:center;
	
}
.folder_asset img{
	cursor:pointer;
	cursor:cursor;
}
</style>

<div id="files_browser_wrapper">

	<div id="common_tool_header" class="breadcrumb_wrapper">
		<a href="/get/files/contents" rel="ROOT" class="get_folder">Assets</a><span id="breadcrumb" rel=""></span>
	</div>
	
	<div class="common_left_panel">
		<span class="icon add_page">&nbsp; &nbsp; </span> <a href="/get/files/add_files" class="add_asset">Add Files</a>
		<br><br>		
		<span class="icon add_folder">&nbsp; &nbsp; </span> <a href="/get/files/add_folder" class="add_asset">Add Folder</a>
		<br><br>
		
	</div>
	
	<div id="directory_window" class="common_main_panel" rel="ROOT">
		<?php echo View::factory('files/folder', array('files'=> $files))?>
	</div>

</div>
<div class="clearboth"></div>

<script type="text/javascript">

	// Remember to centralize this, its also in pages/all_pages view
	function strstr( haystack, needle, bool ) {
		// http://kevin.vanzonneveld.net
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   bugfixed by: Onno Marsman
		// *     example 1: strstr('Kevin van Zonneveld', 'van');
		// *     returns 1: 'van Zonneveld'
		// *     example 2: strstr('Kevin van Zonneveld', 'van', true);
		// *     returns 2: 'Kevin '
		var pos = 0;
		haystack += '';
		pos = haystack.indexOf( needle );
		if( pos == -1 ){
			return false;
		} else{
			if( bool ){
				return haystack.substr( 0, pos );
			} else{
				return haystack.slice( pos );
			}
		}
	}

	$('#files_browser_wrapper').click($.delegate({
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
					result_string = strstr(path, folder_array[i], true) + folder_array[i];
					folder_string += ' &#8594 <a href="/get/files/contents/'+ result_string +'" rel="'+ result_string +'" class="get_folder">'+ folder_array[i] +'</a>';
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
		'div.folder_asset span.cross': function(e){
			if(confirm('This cannot be undone. Delete folder and all inner contents?'))
			{
				$parent	= $(e.target).parent('div');
				path	= $parent.attr('rel');
				folder	= $parent.attr('id');
				$.get('/get/files/delete/'+ path,
					function(data){
						$('#directory_window #' + folder).remove();
						$('#show_response_beta').html(data);
					}
				);
			}
			return false;
		},
		'div.file_asset span.cross': function(e){
			if(confirm('This cannot be undone. Delete this file?'))
			{
				path	= $('#breadcrumb').attr('rel');
				file	= $(e.target).parent('div').attr('rel');
				ufile	= ((path)) ? ':' : '';
				ufile	+= file;
				$.get('/get/files/delete/'+ path + ufile,
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
