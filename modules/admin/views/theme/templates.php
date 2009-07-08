
<?php echo form::open('theme/templates', array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" id="save_sheet" class="jade_positive">Save Changes</button>
		<div id="common_title">Templates for Theme: <?php echo $this->theme;?></div>
	</div>

	<div class="common_left_panel">
	<ul>
	<?php 
	foreach($templates as $template)
	{
		echo "<li><a href=\"#\" class=\"load_template\" rel=\"$template\">$template</a></li>";
	}
	?>
	</ul>
	</div>
	
	<div class="common_main_panel">
		<div class="save_pane" style="position:absolute; background:#fff; padding:10px; border:2px solid orange; width:350px; height:250px; display:none">
			<span class="icon cross" style="float:right">&#160; &#160;</span>
			<span class="replacer">
				<h3>Update</h3>
				File: <select name="update_file" class="files_list">
					<?php foreach($templates as $template) echo "<option value=\"$template\">$template</option>";?>
				</select>	
				<br>
				<br>
				<button class="update_file jade_positive">Update Stylesheet</button>
				
				<p>OR</p>
				
				<h3>New File</h3>
				filename: <input type="text" name="new_file">.html
				<br>
				<br>
				<button class="new_file jade_positive">Save as New Stylesheet</button>
				
			</span>
		</div>
		<textarea id="edit_html" style="width:100%;height:300px"></textarea>
	</div>
</form>

<script type="text/javascript">
	// list of availabe templates.
	$(".load_template").click(function(){
		value = $(this).attr('rel');
		$('textarea#edit_html').val('Loading file...');
		$.get('/get/theme/load/templates/'+ value +'?v=39840',
			function(data){
				$('textarea#edit_html').val(data);
				$('.current_sheet').html(value);
			}
		);
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
		
		// save as update
		'button.update_file': function(){
			file = $("div.save_pane.helper select[name='update_file'] option:selected").text();
			$('div.save_pane.helper span.replacer').html('<div class="loading">Saving '+ file +'...</div>');
			contents = $('textarea#edit_html').val();
			$.post('/get/theme/save/templates/'+ file, {contents: contents }, function(data){
				$('div.save_pane.helper span.replacer').html(data);
			});
			
			return false;
		},	
		
		// save the file as new
		'button.new_file': function(){
			file = $("div.save_pane.helper input[name='new_file']").val() + '.html';	
			$('div.save_pane.helper span.replacer').html('Creating ...'+ file);
			contents = $('textarea#edit_html').val();
			$.post('/get/theme/save/templates/'+ file, {contents: contents }, function(data){
				$('div.save_pane.helper span.replacer').html(data);
				$('select.files_list').append('<option value="'+ file +'">'+ file +'</option>');
			});
			
			return false;
		}	
		
	}));	
	
</script>