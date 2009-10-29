

<div id="common_tool_header">
	<div style="float:right;width:250px;">
		<select id="page_builder_select" style="float:left">
			<?php foreach($page_builders as $tool):?>
				<option value="<?php echo $tool->id?>"><?php echo $tool->name?></option>
			<?php endforeach;?>	
		</select>
		 <button id="add_page_builder" class="jade_positive">Add Page Builder</button>
	</div>
	<div id="common_title">Website Pages Browser</div>
</div>
	
<div id="page_browser_wrapper">
	
	<div class="common_left_panel">
		<h3 class="aligncenter">Add Blank Page</h3>
		<img src="/_assets/images/admin/page_add.gif" alt="" class="new_page_drop">
		<br/>
		<i>Drag</i> +page icon into the desired directory window.
		<br/><br/>
		<h4>Key</h4>
		<small style="line-height:1.7em">
			<b>White:</b> Public - On Menu.
			<br/><b style="color:#ccc">Gray:</b> Public - Not in menu.
			<br/><b style="color:red">Red:</b> Private - No public access.
		</small>
		<p>
			Page Count: <?php echo $page_count?>
		</p>
	</div>
	
	<div class="breadcrumb_wrapper" style="width:590px;float:right;">
		<a href="#" rel="ROOT" class="open_folder"><?php echo trim(url::site(), '/')?></a><span id="breadcrumb" rel=""></span>
	</div>
	<div id="directory_window" class="common_main_panel" rel="ROOT">
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
