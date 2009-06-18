
<div id="page_browser_wrapper">

	<div id="common_tool_header" class="breadcrumb_wrapper">
		<a href="#" rel="ROOT" class="open_folder"><?php echo url::site()?></a><span id="breadcrumb" rel=""></span>
	</div>
	
	<div class="common_left_panel">
		<span class="icon add_page">&nbsp; &nbsp; </span> <a href="/get/page/add" class="new_page">New Page</a>
		<br>
		<br>		
		<h3>Key</h3>
		<small style="line-height:1.7em">
			<span class="icon magnify">&nbsp; &nbsp; </span> Load page.
			<br><span class="icon cog">&nbsp; &nbsp; </span> Edit page settings.
			<br><span class="icon add_folder">&nbsp; &nbsp; </span> Create sub-directory
			<br><span class="icon cross">&nbsp; &nbsp; </span> Delete page.	
			<br><span class="icon shield">&nbsp; &nbsp; </span> Contains Page Builder	
			<br>
			<br><b style="color:#ccc">Gray:</b> accessible but not in menu.
			<br><b style="color:red">Red:</b> not publicly accessible.
		</small>
	</div>
	
	<div id="directory_window" class="common_main_panel" rel="ROOT">
		<?php echo $files_structure?>
	</div>

</div>

<script type="text/javascript">
	$('div.ROOT').show();

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
	
	// assign click delegation
	$('#page_browser_wrapper').click($.delegate({
		
		'.open_folder': function(e){
			path = $(e.target).attr('rel');
			klass = path.replace(/\//g,'_');
			
			$('div.sub_folders').hide();
			$('#directory_window').attr('rel',klass);		
			$('div.'+klass).show();
			
			// add the breadcrumb
			if('ROOT' == path){
				folder_string = '';
				path = '';
			}
			else{
				var folder_array = path.split('/');
				el_count = folder_array.length;
				var folder_string = '';				
				for (i=0; i < el_count; i++){
					result_string = strstr(path, folder_array[i], true) + folder_array[i];
					folder_string += ' &#8594 <a href="/'+ result_string +'" rel="'+ result_string +'" class="open_folder">'+ folder_array[i] +'</a>';
				}
			}
			$('#breadcrumb').attr('rel',path).html(folder_string);
			return false;
		},
		
		'a.new_page': function(e){
			$.facebox(function(){
				path = $('#breadcrumb').attr('rel');
				
				$.get(e.target.href,
				{
					directory: path
				}, 
				function(data){
					$.facebox(data, false, 'facebox_2');
				});
			}, false, 'facebox_2');
			return false;
		},
		
		// make img click execute as its parent alink
		'span.delete_page': function(e){
			if (confirm("This cannot be undone! Delete this page?")) {
				$.parent = $(e.target).parent('a');
				id = $.parent.attr('id');
				url = $.parent.attr('href');
				
				$.get(url, function(){
					klass = $('#page_wrapper_'+id).parent().attr('rel');
					// remove from container
					$('#page_wrapper_'+ id).remove();
				});
			}
			return false;
		},
		
		'span.icon_facebox': function(e){
			$.parent = $(e.target).parent('a');
			url = $.parent.attr('href');

			$.facebox(function(){
					$.get(url, function(data){
						$.facebox(data, false, 'facebox_2');
					});
			}, false, 'facebox_2');
			return false;
		},
		
		'span.folderize': function(e){
			folder_path = $(e.target).attr('rel');
			id = $(e.target).attr('id');
			klass = folder_path.replace(/\//g,'_');
			html = '<div class="folder_bar"><a href="/'+ folder_path +'" rel="'+ folder_path +'" class="open_folder" ><span class="icon add_folder open_folder" rel="'+ folder_path +'"> &nbsp; &nbsp; </span></a></div>';
			
			
			$('#page_wrapper_'+id).prepend(html);
			
			container = '<div class="'+ klass +' sub_folders"></div>';
			$('#directory_window').prepend(container);
			$(e.target).remove();
			return false;
		}
		
	}));

</script>
