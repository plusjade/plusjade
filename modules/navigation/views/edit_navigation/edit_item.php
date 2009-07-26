<?php
	$data = $selected = array(
		'none'	=> '',
		'page'	=> '',
		'url'	=> '',
		'email'	=> '',
		'file'	=> '',
	);	
	$selected[$item->type] = 'selected="selected"';
	$data[$item->type] = $item->type;
?>

<span class="icon cross floatright">&#160; &#160; </span>

<form action="/get/edit_navigation/edit/<?php echo $item->id?>" method="POST" id="edit_form">	

	<div id="common_tool_header">
		<div id="common_title">Edit Navigation element</div>
	</div>	
	
	<div class="fieldsets">
		
		<div class="tier">
			Type:
			<select class="toggle_type" name="type" style="width:250px">
				<option value="none" <?php echo $selected['none']?>>Label (no link)</option>
				<option value="page" <?php echo $selected['page']?>>Link to +Jade Page</option>
				<option value="url" <?php echo $selected['url']?>>Link to external Page</option>
				<option value="email" <?php echo $selected['email']?>>Link to email address</option>
				<option value="file" <?php echo $selected['file']?>>Link to +Jade file</option>	
			</select>
			<br><img src="<?php echo url::image_path('admin/arrow_right_down.png')?>" alt="next">  
		</div>
		
		<div class="tier">
			Label <input type="text" name="item" value="<?php echo $item->display_name?>" rel="text_req" style="width:250px">
		</div>
		
		<div class="tier">		
			<span class="page hide">Page:
				<select name="data" disabled="disabled">
					<?php
					foreach ($pages as $page)
						if( $item->data == $page->page_name )
							echo '<option selected="selected">', $page->page_name ,'</option>';
						else
							echo '<option>', $page->page_name ,'</option>';
					?>
				</select>
			</span>
			<span class="url hide">http://<input type="text" name="data" value= "<?php echo $item->data?>" disabled="disabled" rel="text_req" style="width:250px"></span>
			<span class="email hide">mailto:<input type="text" name="data" value= "<?php echo $item->data?>" disabled="disabled" rel="email_req" style="width:250px"></span>
		</div>
		
	</div>
	
	<br>
	<button type="submit" class="jade_positive">Save Changes</button>
</form>

<script type="text/javascript">
	$('#edit_form span.<?php echo $item->type?> > :input').removeAttr("disabled");
	$('#edit_form span.<?php echo $item->type?>').show();	
	
	$(".facebox #edit_form .toggle_type").each(function(){
		$(this).change(function(){
			var span = "#edit_form span." + $(this).val();
			// Disable to start over
			$("#edit_form .hide").hide();
			$("#edit_form .hide > :input").attr("disabled","disabled");
			// Enable selection
			$(span + " > :input").removeAttr("disabled");
			$(span).show();
		});
	});

	/*
	 * custom ajax form response needs to populate the nested li list.
	 */
	$("#edit_form").ajaxForm({
		beforeSubmit: function(){
			if(! $("#edit_form input:enabled").jade_validate() ) return false;
			$('.facebox .show_submit').show();
		},
		success: function(data) {	
			var text = $("#edit_form input[name='item']").val();
			$('li span.active').html(text);
			$('#edit_wrapper').hide();
			$('.facebox .show_submit').hide();
			$('#show_response_beta').html(data);			
		}
	});
</script>