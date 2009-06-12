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

<form action="/get/edit_navigation/edit/<?php echo $item->id?>" method="POST" class="custom_ajaxForm" id="add_links_form">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">
			<img src="<? echo url::image_path('check.png')?>" alt=""/> Save Changes
		</button>
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
			<span id="page"  class="hide">Page:
				<select name="data" disabled="disabled">
					<?php
					foreach ($pages as $page)
					{
						if( $item->data == $page->page_name )
						{
							echo '<option selected="selected">', $page->page_name ,'</option>';
						}
						else
						{
							echo '<option>', $page->page_name ,'</option>';
						}
					}
					?>
				</select>
			</span>
			<span id="url" class="hide">http://<input type="text" name="data" value= "<?php echo $item->data?>" disabled="disabled" rel="text_req" style="width:250px"></span>
			<span id="email" class="hide">mailto:<input type="text" name="data" value= "<?php echo $item->data?>" disabled="disabled" rel="email_req" style="width:250px"></span>
		</div>
		
	</div>
	
</form>

<script type="text/javascript">
	$('span#<?php echo $item->type?> > :input').removeAttr("disabled");
	$('span#<?php echo $item->type?>').show();	
	
	$(".facebox .toggle_type").each(function(){
		$(this).change(function(){
			var span = "#" + $(this).val();
			
			// Disable to start over
			$(".hide").hide();
			$(".hide > :input").attr("disabled","disabled");
			
			// Enable selection
			$(span + " > :input").removeAttr("disabled");
			$(span).show();
		});
	});

	
	/*
	 * custom ajax form response needs to populate the nested li list.
	 */
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input:enabled").jade_validate() )
				return false;
			$('.facebox .show_submit').show();
		},
		success: function(data) {	
			text = $("input[name='item']").val();
			$('li span.active').html(text);
			
			$.facebox.close("facebox_2");
			$('.facebox .show_submit').hide();
			$('#show_response_beta').html(data);			
		}
	});
</script>