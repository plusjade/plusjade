

<div id="common_tool_header" class="buttons">
	<button type="submit" id="show_save" class="jade_positive">Save Template As..</button>
	<div id="common_title">Theme &#8594; <u><?php echo ucfirst($this->theme)?></u> : Template &#8594; <u><span class="current_file">master.html</span></u></div>
</div>

<div class="common_left_panel">
	<h3>Available Templates</h3>
	<select name="files" class="files_list">
		<?php
		foreach($templates as $template)
			if('master.html' == $template)
				echo "<option value=\"$template\" selected=\"selected\">$template</option>";
			else
				echo "<option value=\"$template\">$template</option>";
		?>
	</select>
	<p><button id="load_file" class="jade_positive">Load</button></p>
	
	<h3>Tokens</h3>
	%FILES%
	<br><small>(url to file directory)</small>
</div>

<div class="common_main_panel">


	<div class="save_pane" style="display:none">
		<div class="contents">
			<span class="icon cross floatright">&#160; &#160;</span>
			
			<h2><b>Save File</b></h2>
			
			<div style="margin-bottom:10px">
				<h3><button class="update_file jade_positive floatright">Update</button> As Update</h3>
				<select name="update_file" class="files_list">
					<?php foreach($templates as $template) echo "<option value=\"$template\">$template</option>";?>
				</select>
			</div>
			
			<div>	
				<h3><button class="new_file jade_positive floatright">Save as New</button> As New</h3>
				filename: <input type="text" name="new_file" class="auto_filename" rel="text_req">.html
			</div>
		</div>
	</div>

	<textarea id="edit_html" class="full_height" style="height:300px"><?php echo $contents?></textarea>
</div>
	

<script type="text/javascript">

/* ------------------ LOADING AND DELETING(not shown)  ------------------ */ 

// Load file from select dropdown into textarea
	$("#load_file").click(function(){
		var value = $("select[name='files'] option:selected").text();		
		$('textarea#edit_html').val('Loading file...');
		$.get('/get/theme/load/templates/'+ value +'?v=39840',
			function(data){
				$('textarea#edit_html').val(data);
				// set file as selected
				$("div.save_pane select[name='update_file'] option").removeAttr('selected');
				
				$('.current_file').html(value);
				$("div.save_pane select[name='update_file'] option[value='"+ value +"']").attr({selected:'selected'});
			}
		)
		return false;
	});


/* ------------------ SAVE COMMANDS  ------------------ */ 	
	
// show the save pane
	$('#show_save').click(function(){
		$('.save_pane.helper').remove();
		$('.save_pane').clone().addClass('helper').show().prependTo('.common_main_panel');
		return false;
	});
	
// delegation for save_pane
	$('.common_main_panel').click($.delegate({

	  // save as update
		'button.update_file': function(){
			var file = $("div.save_pane.helper select[name='update_file'] option:selected").text();
			$('div.save_pane.helper .contents').html('Saving '+ file +'...');
			var contents = $('textarea#edit_html').val();
			$.post('/get/theme/save/templates/'+ file, {contents: contents }, function(data){
				$('button#show_save').removeAttr('disabled');
				$('div.save_pane.helper .contents').html(data + ' saved!!');
				setTimeout('$("div.save_pane.helper").remove()', 1000);
			});
			
			return false;
		},	
		
	  // save as new
		'button.new_file': function(){
			if(! $("div.save_pane.helper input[name='new_file']").jade_validate()) return false;
			var file = $("div.save_pane.helper input[name='new_file']").val() + '.html';	
			$('div.save_pane.helper .contents').html('Creating ... '+ file);
			var contents = $('textarea#edit_html').val();
			
			$('.facebox .show_submit').show();
			$.post('/get/theme/save/templates/'+ file, {contents: contents }, function(data){
				$('select.files_list').append('<option value="'+ data +'">'+ data +'</option>');
				$("div.save_pane.helper").remove();
				$('button#show_save').removeAttr('disabled');			
				$('.facebox .show_submit').hide();
			});
			return false;
		}	
	}));	
</script>