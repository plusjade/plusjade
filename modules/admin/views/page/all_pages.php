
<style type="text/css">
#page_browser_wrapper .breadcrumb_wrapper{
	font-size:1.2em;
	font-weight:bold;
}
#page_browser_wrapper .breadcrumb_wrapper a{
}

#page_browser_wrapper #legend{
	float:left;
	width:200px;
	padding:5px;
}

#directory_window{
	height:350px;
	width:460px;
	float:right;
	padding:10px;
	overflow:auto;
	order:1px solid #99ccff;
	background:#e2effc;
}
#directory_window div.asset{
	margin:3px;
	overflow:auto;
}
.folder_bar, .page_icon, .page_bar{
	padding:4px;
}
#directory_window div.sub_folders{
	display:none;
}
.enabled{
	background:#fff;
	border:1px solid #99ccff;
}
.hidden{
	background:#f2f1f1;
	border:1px solid #ccc;
}
.disabled{
	background:#fbe2e2;
	border:1px solid pink;
}

.folder_bar{
	background:lightgreen;
	width:25px;
	float:left;
}
.page_bar{
	float:right;
}
.page_bar div{
	float:left;
	text-align:right;
	order:1px solid red;
	width:25px;
	min-height:10px;
	overflow:auto;
}
.page_icon{
	float:left;
	padding:3px;
}
</style>

<div id="page_browser_wrapper">

	<div id="common_tool_header" class="breadcrumb_wrapper">
		<a href="#" rel="ROOT" class="open_folder"><?php echo url::site()?></a><span id="breadcrumb" rel=""></span>
	</div>
	
	<div id="legend">
		<img src="<?php echo url::image_path('admin/page_add.png')?>" alt=""> <a href="/get/page/add" class="new_page">New Page</a>
		
		<br><br>
		
		<h3>Key</h3>
		<small>
			<img src="<?php echo url::image_path('admin/magnifier.png')?>" alt=""> Load page.
			<br><img src="<?php echo url::image_path('admin/cog_edit.png')?>" alt=""> Edit page settings.
			<br><img src="<?php echo url::image_path('admin/folder_add.png')?>" alt=""> Create folder from page name
			<br><img src="<?php echo url::image_path('admin/delete.png')?>" alt=""> Delete page.	
			<br>
			<br><b style="color:#ccc">Gray:</b> accessible but not in menu.
			<br><b style="color:red">Red:</b> not publicly accessible.
		</small>
	</div>
	
	<div id="directory_window" rel="ROOT">
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
		'img.delete_page': function(e){
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
		
		'img.img_facebox': function(e){
			$.parent = $(e.target).parent('a');
			url = $.parent.attr('href');

			$.facebox(function(){
					$.get(url, function(data){
						$.facebox(data, false, 'facebox_2');
					});
			}, false, 'facebox_2');
			return false;
		},
		
		'img.folderize': function(e){
			folder_path = $(e.target).attr('rel');
			id = $(e.target).attr('id');
			klass = folder_path.replace(/\//g,'_');
			html = '<div class="folder_bar"><a href="/'+ folder_path +'" rel="'+ folder_path +'" class="open_folder" ><img src="<?php echo url::image_path('admin/folder.png')?>" rel="'+ folder_path +'" class="open_folder" alt=""></a></div>';
			
			
			$('#page_wrapper_'+id).prepend(html);
			
			container = '<div class="'+ klass +' sub_folders"></div>';
			$('#directory_window').prepend(container);
			$(e.target).remove();
			return false;
		}
		
	}));

</script>
