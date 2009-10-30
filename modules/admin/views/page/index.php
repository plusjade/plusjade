
<style type="text/css">
ul.page_list{list-style:none;}
ul.page_list li{ display:inline; margin-right:15px;}
</style>

<div id="common_tool_header">
	<div style="float:right;">
		 <button id="add_page_builder" class="jade_positive">Add Page Builder</button>
		 
		<select id="page_builder_select">
			<?php foreach($page_builders as $tool):?>
				<option value="<?php echo $tool->id?>"><?php echo $tool->name?></option>
			<?php endforeach;?>	
		</select>	
	</div>
	<div id="common_title">Website Pages Browser</div>
</div>
	

<div class="">	
	<ul class="page_list">
		<li>Pages: <?php echo $page_count?></li>
		<li><img src="/_assets/images/admin/page_add.gif" width="20px" height="20px" alt="" class="new_page_drop"> Add Page</li>
		<li><span class="icon edit_page">&nbsp; &nbsp; </span> <a href="#" class="rename_selected">Rename</a></li>
		<li><span class="icon move">&nbsp; &nbsp; </span> <a href="#" class="move_selected">Move</a></li>			
		<li><span class="icon cross">&nbsp; &nbsp; </span> <a href="#" class="delete_selected">Delete</a></li>	
		<li><span class="icon add_folder">&nbsp; &nbsp; </span> <a href="#" class="delete_selected">Make Folder</a></li>		
	</ul>
</div>
<div id="page_browser_wrapper">	
	<div class="breadcrumb_wrapper">
		<a href="#" rel="ROOT" class="open_folder"><?php echo trim(url::site(), '/')?></a><span id="breadcrumb" rel=""></span>
	</div>
	<div id="directory_window" class="common_full_panel" rel="ROOT" style="overflow:auto">
		<?php echo $files_structure?>
	</div>

</div>

<script type="text/javascript">
	$('div.ROOT').show();
	$(".new_page_drop").draggable({revert: 'invalid', helper: 'clone'});
	$("#directory_window").droppable({
		activeClass: 'ui-state-highlight',
		drop: function(event, ui) {
			$.facebox(function(){
				path = $('#breadcrumb').attr('rel');
				$.get('/get/page/add', {directory: path}, 
					function(data){$.facebox(data, false, 'facebox_2')}
				);
			}, false, 'facebox_2');
		}
	});	
  // click away hides file options	
	$('#page_browser_wrapper:not(.file_options)').click(function(){
		$('#page_browser_wrapper ul.option_list').hide();
	});
</script>
