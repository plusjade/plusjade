

<div id="common_tool_header" class="buttons">
	<div id="common_title">
		Theme &#8594; <u><?php echo ucfirst($this->theme)?></u> : Stylesheet &#8594; <u><span class="current_sheet">global.css</span></u>
	</div>
</div>


<div class="common_left_panel" style="width:18%">
	
	<h3>Available Stylesheets</h3>
	<select name="files" class="files_list">
		<?php
		foreach($css_files as $file)
			echo "<option value=\"$file\">$file</option>";
		?>
	</select>
	<p>
		<button id="load_sheet" class="jade_positive">Load</button>
	</p>
	<button id="delete_sheet" class="jade_negative"><span class="icon cross">&#160; &#160; </span>Delete</button>
</div>

<div class="common_main_panel" style="margin:0;padding:0;width:78%">
	
	<div class="save_pane" style="position:absolute; background:#fff; padding:10px; border:2px solid orange; width:350px; height:250px; display:none">
		<span class="icon cross" style="float:right">&#160; &#160;</span>
		
		<h3>Update</h3>
		File: <select name="update_file" class="files_list">
			<?php foreach($css_files as $file) echo "<option value=\"$file\">$file</option>";?>
		</select>	
		<br>
		<br>
		<button class="update_file jade_positive">Update Stylesheet</button>
		
		<p>OR</p>
		
		<h3>New File</h3>
		filename: <input type="text" name="new_file">.css
		<br>
		<br>
		<button class="new_file jade_positive">Save as New Stylesheet</button>
	</div>
	
	
	<ul class="generic_tabs ui-tabs_nav">
		<li><a href="#" class="update">Update</a></li>
		<li><a href="#" class="show_orig">Reset</a></li>
		<li><a href="#" class="show_stock">Show Stock</a></li>
		<li><button id="save_sheet" class="jade_positive">Save as ...</button></li>
	</ul>
	<textarea id="edit_css" name="contents" class="blah" style="height:300px"><?php echo $contents?></textarea>

</div>
	
<div id="stock_contents" style="display:none"><?php echo $contents?></div>
	


<script type="text/javascript">
	original = $('textarea#edit_css').val();
	
	$('.show_stock').click(function(){
		contents = $('#stock_contents').html();
		$('textarea#edit_css').val(contents);
		return false;
	});
	$('.show_orig').click(function(){
		$('textarea#edit_css').val(original);
		return false;
	});

	
	$('a.update').click(function(){
		value	= $('textarea#edit_css').val();
		css		= '<style id="global-style" type="text/css">'+ value +'</style>';
		$('#global-style').replaceWith(css);
		return false;
	});	

	
	$('#save_sheet').click(function(){
		$('.save_pane').clone().addClass('helper').show().prependTo('.common_main_panel');
		return false;
	});

	
	// delegation for save_pane
	$('.common_main_panel').click($.delegate({
		
		// close the save pane
		'div.save_pane .icon.cross':function(e){
			$('div.save_pane.helper').remove();
			return false;	
		},
		
		// update a file
		'button.update_file': function(){
			file = $("div.save_pane.helper select[name='update_file'] option:selected").text();
			$('div.save_pane.helper').html('<div class="loading">Saving '+ file +'...</div>');
			contents = $('textarea#edit_css').val();
			$.post('/get/theme/save/css/'+ file, {contents: contents }, function(data){
				$('div.save_pane.helper').html(data);
				setTimeout('$("div.save_pane.helper").remove()', 2000);
			});
		
			return false;
		},	
		
		// save the file as new
		'button.new_file': function(){
			file = $("div.save_pane.helper input[name='new_file']").val() + '.css';	
			$('div.save_pane.helper').html('Creating ...'+ file);
			contents = $('textarea#edit_css').val();
			$.post('/get/theme/save/css/'+ file, {contents: contents }, function(data){
				$('div.save_pane.helper').html(data);
				
				$('select.files_list').append('<option value="'+ file +'">'+ file +'</option>');
				
				setTimeout('$("div.save_pane.helper").remove()', 500);
			});
			
			return false;
		}	
		
	}));	
	
	// select dropdown for loading stylesheet files
	$("#load_sheet").click(function(){
		value = $("select[name='files'] option:selected").text();		
		$('textarea#edit_css').val('Loading file...');
		$.get('/get/theme/load/css/'+ value +'?v=39840',
			function(data){
				$('textarea#edit_css').val(data);
				// set file as selected
				$("div.save_pane select[name='update_file'] option").removeAttr('selected');
				
				$('.current_sheet').html(value);
				$("div.save_pane select[name='update_file'] option[value='"+ value +"']").attr({selected:'selected'});
			}
		);
	});

	// delete a stylesheet
	$("#delete_sheet").click(function(){
		file = $("select[name='files'] option:selected").text();		
		if(confirm('This cannot be undone. Delete stylesheet: '+ file))
			$.get('/get/theme/delete/css/'+ file,
				function(data){
					$("select[name='files'] option:selected").remove();
					$('#show_response_beta').html(data);
				}
			);
	});	
</script>





