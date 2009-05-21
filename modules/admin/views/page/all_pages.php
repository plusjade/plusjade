<?php
	function show_files($node_array)
	{		
		$counter = 5;
		foreach($node_array as $key => $name)
		{
			if( is_array($name) )
			{
				$class = str_replace('/','_',$key, $count);
				if(0 < $count )
				{
					$last_node = strrchr($key, '/');
					$last_node = trim($last_node, '/');
				}
				else
					$last_node = $key;
					
				echo '<div class="asset">';
					echo '<img src="'. url::image_path('admin/folder.png') ."\" alt=''> ";
					echo '(' .count($name). ')<br>';
					echo '<a href="/'. $key .'" rel="'. $key .'" class="open_folder">'.$last_node.'</a>';
				echo '</div>';
				
				echo '<div class="'. $class .' sub_folders">';
					echo show_files($name);
				echo '</div>';
			}
			else
			{
				echo '<div class="asset">';
					echo '<img src="'.url::image_path('admin/page.png')."\" alt=''><br>$name";
				echo '</div>';
			}
			
			++$counter;
			if( 0 == $counter%5 )
				echo '<div class="clearboth"></div>';
		}
	}
?>
<style type="text/css">
#page_browser_wrapper .breadcrumb{
	padding:2px;
	font-size:1.2em;
}
#directory_window{
	padding:10px;
	margin:10px;
	border:1px solid #ccc;
	min-height:300px;
	overflow:auto;
	background:#eee;
}
#directory_window div.asset{
	width:90px;
	height:80px;
	border:1px solid #ccc;
	padding:5px;
	margin:3px;
	float:left;
	background:#fff;
}
#directory_window div.asset img{

}
	
#directory_window div.sub_folders{
	display:none;
	margin:10px;
	border:1px solid red;
}
#page_browser_wrapper .actions{
	text-align:right;
}
</style>
<div id="common_tool_header" class="buttons">
	<div id="common_title">All Site Pages</div>
</div>

<div id="common_tool_info">
	Load pages for editing by clicking on the page name link.
	<br><b style="color:#ccc">Gray</b> links are accessible but not on the menu.
	<br><b style="color:red">Red</b> links are not publicly accessible.
</div>


<div id="page_browser_wrapper">

	<div class="breadcrumb">
		<a href="#" rel="ROOT" class="open_folder"><?php echo url::site()?></a><span id="update_url" rel=""></span>
	</div>

	<div class="actions">
		<img src="<?php echo url::image_path('admin/page_add.png')?>" alt=""> <a href="get/page/add" class="new_page">Create Page</a>
		<img src="<?php echo url::image_path('admin/folder_add.png')?>" alt=""> <a href="get/page/add" class="new_page">Create Folder</a>
	</div>
	
	<div id="directory_window">

	</div>

	<div class="ROOT" style="display:none">
		<?php echo show_files($files_array);?>
	</div>	

</div>

<script type="text/javascript">
	$('#directory_window').html($('div.ROOT').html());

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
	
	$('#page_browser_wrapper').click($.delegate({
		'a.open_folder': function(e){
			path = $(e.target).attr('rel');
			klass = path.replace(/\//g,'_');
			$('#directory_window').html($('div.'+klass).html());		

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
			$('#update_url').attr('rel',path).html(folder_string);
			
			return false;
		},
		
		'a.new_page': function(e){
			$.facebox(function(){
				path = $('#update_url').attr('rel');
				
				$.get(e.target.href,
				{
					path_string: path
				}, 
				function(data){
					$.facebox(data, false, 'facebox_2');
				});
			}, false, 'facebox_2');
			return false;
		}
	}));
</script>
