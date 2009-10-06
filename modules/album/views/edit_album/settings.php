
<style type="text/css">

#json_params_wrapper{
	width:400px;
	text-align:right;
	line-height:2.6em;
}
</style>

<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_album/settings/<?php echo $album->id?>" method="POST" class="custom_ajaxForm">	
	<input type="hidden" name="params" value="<?php echo $album->params?>">		
			
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_settings" class="jade_positive" accesskey="enter">Save Settings</button>
		<div id="common_title">Edit Album Settings</div>
	</div>	
		
	<div class="common_full_panel">
	
		<div class="common_half_left">
			<b>Album Name</b>
			<br/><input type="text" name="name" value="<?php echo $album->name?>">
			
			<br/><br/>
			
			<b>Album View</b> 
			<select name="view">
				<option>lightbox</option>
				<?php
					if('gallery' == $album->view)
						echo '<option selected="selected">gallery</option>';
					else
						echo '<option>gallery</option>';
				?>
			</select>
		
			<br/><br/>
			
			<b>Toggle Elements</b><br/>
			<input type="text" name="toggle" value="<?php echo $album->toggle?>" />
		</div>
		
		
		<div id="json_params_wrapper" class="common_half_right" style="height:400px;overflow:auto;">
			<div style="float:left;font-weight:bold">Gallery Settings</div>
			<?php
			foreach($params as $key => $value)
				echo "$key <input type='text' name='$key' id='$key' value='$value' /> <br/>";
		
			?>
		</div>
		
	</div>

</form>



<script type="text/javascript">

// Save the form
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData, form){
			if(! $("input", form[0]).jade_validate()) return false;	
			$(document).trigger('show_submit.plusjade');
		
			// do we need to jsonize the parameters?
			var blah = 'yes';
			if(blah == 'yes'){
				var params = new Object();
				$('#json_params_wrapper input').each(function(i){
					var name = $(this).attr('name');
					params[name] = $(this).val();
				});
				// assign to first input; params to our json string.
				formData[0].value = $.toJSON(params);
				//console.log(params);
			}
		},
		success: function(data) {
			$(document).trigger('server_response.plusjade', data);	
		}
	});
	
</script>


