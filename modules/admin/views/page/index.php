

<div id="common_tool_header">
	<div id="common_title">Website Pages Browser</div>
</div>
	
<div id="page_browser_wrapper">
	
	<div class="common_left_panel">
		<img src="/_assets/images/admin/file.jpg" alt="" class="new_page_drop">
		<br>
		<h3>Add Blank Page &#8594;</h3>
		<i>Drag</i> +page icon into the desired directory window.
		
		<br><br>
		<h3>Add Page Builders &#8594;</h3>
		<ul class="page_builders">
			<?php
			foreach($page_builders as $tool)
				echo "<li><span class=\"icon shield\">&#160; &#160; </span><a href=\"/get/page/add_builder/$tool->id/$tool->name\" rel=\"facebox\" id=\"2\">$tool->name</a></li>";
			?>
		</ul>
		<br><br>
		<h4>Key</h4>
		<small style="line-height:1.7em">
			<b>White:</b> Public - On Menu.
			<br><b style="color:#ccc">Gray:</b> Public - Not in menu.
			<br><b style="color:red">Red:</b> Private - No public access.
		</small>
	</div>
	
	<div class="breadcrumb_wrapper" style="width:590px">
		<a href="#" rel="ROOT" class="open_folder"><?php echo trim(url::site(), '/')?></a><span id="breadcrumb" rel=""></span>
	</div>
	<div id="directory_window" class="common_main_panel" rel="ROOT">
		<?php echo $files_structure?>
	</div>

</div>

<script type="text/javascript">
	$('div.ROOT').show();

	
		$(".new_page_drop").draggable({
			revert: 'invalid',
			helper: 'clone'
		});
		$("#directory_window").droppable({
			activeClass: 'ui-state-highlight',
			drop: function(event, ui) {
				//ui.draggable.css('border','1px solid red');
				$.facebox(function(){
					path = $('#breadcrumb').attr('rel');
					$.get('get/page/add', {directory: path}, 
						function(data){$.facebox(data, false, 'facebox_2')}
					);
				}, false, 'facebox_2');
			}
		});

		
	
	// click away hides file options	
	$('#page_browser_wrapper:not(.file_options)').click(function(){
		$('#page_browser_wrapper ul.option_list').hide();
	});
	
	// assign click delegation
	$('#page_browser_wrapper').click($.delegate({
		// open file dropdown lists
		'img.file_options, span.icon.page': function(e){
			$('#page_browser_wrapper ul.option_list').hide();
			$(e.target).nextAll('ul').show();
		},
		
		// show a new folder directory
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
					result_string = $.strstr(path, folder_array[i], true) + folder_array[i];
					folder_string += ' / <a href="/'+ result_string +'" rel="'+ result_string +'" class="open_folder">'+ folder_array[i] +'</a>';
				}
			}
			$('#breadcrumb').attr('rel',path).html(folder_string);
			return false;
		},
		
		// open new page facebox
		'a.new_page': function(e){
			$.facebox(function(){
				path = $('#breadcrumb').attr('rel');
				$.get(e.target.href, {directory: path}, 
					function(data){$.facebox(data, false, 'facebox_2')}
				);
			}, false, 'facebox_2');
			return false;
		},
		
		// delete a page
		'a.delete_page': function(e){
			if('folder' == $(e.target).attr('rel'))
			{
				alert('A page must have no sub-pages before it can be deleted.');
				return false;
			}
			if (confirm("This cannot be undone! Delete this page?")) {
				$.parent = $(e.target).parent('a');
				id = $(e.target).attr('id');
				
				$.get(e.target.href, function(data){
					// remove from container
					$('#page_wrapper_'+ id).remove();
					$('#show_response_beta').html(data);	
				});
			}
			return false;
		},

		// turn a page into a folder path
		'a.folderize': function(e){
			folder_path = $(e.target).attr('rel');
			id = $(e.target).attr('id');
			filename = $(e.target).attr('title');
			klass = folder_path.replace(/\//g,'_');
			html = '<img src="/_assets/images/admin/folder.jpg" rel="'+ folder_path +'" class="open_folder"> <span class="icon page">&#160; &#160;</span> ';
			$('#page_wrapper_'+ id +' img').replaceWith(html);
			
			container = '<div class="'+ klass +' sub_folders"></div>';
			$('#directory_window').prepend(container);
			$(e.target).parent('li').remove();
			return false;
		}
		
	}));

</script>
