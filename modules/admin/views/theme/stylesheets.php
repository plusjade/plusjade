

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
			if('global.css' == $file)
				echo "<option value=\"$file\" selected=\"selected\">$file</option>";
			else
				echo "<option value=\"$file\">$file</option>";
		?>
	</select>
	<p><button id="load_sheet" class="jade_positive" style="width:120px">Load</button></p>
	<button id="delete_sheet" class="jade_negative" style="width:120px"><span class="icon cross">&#160; &#160; </span>Delete</button>
	<br><br>
	
	<b>Press TAB</b> while in the textarea to update the tool view.
	
	<br><br>
	
	<h3>Tokens</h3>
	../images/ , %IMAGES%
	<br><small><input type="text" value="<?php echo $this->assets->theme_url('images')?>"></small>
	<br><br>%FILES%
	<br><small><input type="text" value="<?php echo $this->assets->assets_url()?>"></small>
	<br><small>(url to file directory)</small>
	
</div>

<div class="common_main_panel" style="margin:0;padding:0;width:78%">
	
	<div class="save_pane" style="display:none">
		<div class="contents">
			<span class="icon cross floatright">&#160; &#160;</span>
			
			<h2><b>Save File</b></h2>
			
			<div style="margin-bottom:10px">
				<h3>
					<button class="update_file jade_positive floatright">Update</button> As Update
				</h3>
				<select name="update_file" class="files_list">
					<?php foreach($css_files as $file) echo "<option value=\"$file\">$file</option>";?>
				</select>
			</div>
			
			<div>	
				<h3>
					<button class="new_file jade_positive floatright">Save as New</button> As New
				</h3>
				filename: <input type="text" name="new_file" class="auto_filename">.css
			</div>
		</div>
	</div>
	
	
	<ul class="generic_tabs ui-tabs_nav">
		<li><a href="#" class="update">Update</a></li>
		<li><a href="#" class="show_orig">Reset</a></li>
		<li><button id="show_save" class="jade_positive">Save as ...</button></li>
	</ul>
	<textarea id="edit_css" name="contents" style="height:300px"><?php echo $contents?></textarea>

</div>
	
<div id="stock_contents" style="display:none"><?php echo $contents?></div>
	


<script type="text/javascript">
/* ------------------ editor buttons  ------------------ */ 

// store the original
	var original = $('textarea#edit_css').val();

// revert original css into the textarea
	$('.show_orig').click(function(){
		$('textarea#edit_css').val(original);
		return false;
	});
// update the dom with current css
	$('a.update').click(function(){
		var value	= $('textarea#edit_css').val();
		var css		= '<style id="global-style" type="text/css">'+ value +'</style>';
		$('#global-style').replaceWith(css);
		return false;
	});	

// TAB in textarea to update
	$('textarea#edit_css').keydown(function(e){
		// 16 = SHIFT, 9 = tab
		if (e.keyCode == 9) {		
			var value	= $('textarea#edit_css').val();
			var css		= '<style id="global-style" type="text/css">'+ value +'</style>';
			$('#global-style').replaceWith(css);
			return false;
		}	
	});
	
	
/* ------------------ LOADING AND DELETING  ------------------ */ 

// Load file from select dropdown into textarea
	$("#load_sheet").click(function(){
		var value = $("select[name='files'] option:selected").text();		
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
		var file = $("select[name='files'] option:selected").text();		
		if(confirm('This cannot be undone. Delete stylesheet: '+ file))
			$.get('/get/theme/delete/<?php echo $this->theme?>:css:'+ file,
				function(data){
					$("select[name='files'] option:selected").remove();
					$('#show_response_beta').html(data);
				}
			);
	});	

/* ------------------ SAVE COMMANDS  ------------------ */ 	

// show save_pane
	$('#show_save').click(function(){
		$('.save_pane.helper').remove();
		$('.save_pane').clone().addClass('helper').show().prependTo('.common_main_panel');
		return false;
	});

// delegation for save_pane
	$('.common_main_panel').click($.delegate({
	  // update a file
		'button.update_file': function(){
			var file = $("div.save_pane.helper select[name='update_file'] option:selected").text();
			$('div.save_pane.helper .contents').html('Saving '+ file +'...');
			var contents = $('textarea#edit_css').val();
			
			$.post('/get/theme/save/css/'+ file, {contents: contents }, function(data){
				$('div.save_pane.helper .contents').html(data + ' saved!!');
				setTimeout('$("div.save_pane.helper").remove()', 1000);
			});
			return false;
		},	
		
	  // save as new
		'button.new_file': function(){
			var file = $("div.save_pane.helper input[name='new_file']").val() + '.css';	
			$('div.save_pane.helper .contents').html('Creating ...'+ file);
			var contents = $('textarea#edit_css').val();
			
			$.post('/get/theme/save/css/'+ file, {contents: contents }, function(data){
				$('select.files_list').append('<option value="'+ data +'">'+ data +'</option>');
				$('div.save_pane.helper .contents').html(data + ' saved!!');
				setTimeout('$("div.save_pane.helper").remove()', 1000);
			});
			return false;
		}	
	}));	
</script>