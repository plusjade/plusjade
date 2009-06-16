
<style type="text/css">
	.common_main_panel img{
		display:block;
		margin:5px;
		padding:5px;
		cursor:pointer;
		cursor:hand;
	}
	.common_main_panel img.selected{
		border:2px solid orange;
	}
</style>
<?php echo form::open('theme/add_logo', array('enctype' => 'multipart/form-data', 'class' => 'custom_ajaxForm1') )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="upload_logo" class="jade_positive">Upload Logo</button>
		<div id="common_title">Configure Logo</div>
	</div>

	<div id="common_tool_info" style="background:#eee;padding:5px">
		<b>Add Logo</b> <input type="file" name="image" rel="text_req">
		<p>Make this my new logo? <input type="checkbox" name="enable"> YES!</p>
	</div>
</form>


<div class="common_left_panel buttons" style="text-align:center">
	
	<button type="submit" name="change_logo" class="jade_positive">Change Logo</button>
	<br><br>
	<button type="submit" name="delete_logo" class="jade_negative">
		<span class="icon cross">&#160; &#160; </span> Delete Logo
	</button>
</div>

<div class="common_main_panel" style="height:300px">
<?php
	foreach($saved_banners as $key => $image)
	{
		if($image == $this->banner)
			$current_banner = $image;
		?>
		<img src="<?php echo "$img_path/$image"?>" id="selected_banner" rel="<?php echo $image?>" alt="">
		<?php
	}
		?>
</div>


<script type="text/javascript">

	$('.common_main_panel').click($.delegate({
		'img' : function(e){
			$('.common_main_panel img').removeClass('selected');
			$(e.target).addClass('selected');
		}
	
	}));

	$('.facebox .custom_ajaxForm1').ajaxForm({	
		beforeSubmit: function(){
			$('.facebox .show_submit').show();
		},			
		success: function(data) {
			var img = new Image();
			img.src = '<?php echo $img_path?>/'+ data;
			
			$('.facebox .show_submit').hide();
			html ='<img src="<?php echo $img_path?>/'+ data +'" id="selected_banner" rel="'+ data +'" alt="">';
			$('.common_main_panel').prepend(html);
			$('#show_response_beta').html(data);
		}
	});
	

	/*
		forget the ajaxForm , just post this using $.post()
	*/
	
	$('button[name="change_logo"]').click(function(){
		image = $('.common_main_panel img.selected').attr('rel');
		if(!image)
		{
			alert('Select a banner first.');
			return false;
		}
		
		$('.facebox .show_submit').show();
		$.post('/get/theme/change_logo', {banner: image}, function(data){
			// the images are already loaded via this facebox so we dont need to load them again?
			$('#jade_banner_link').html('<img src="<?php echo $img_path?>/' + image +'" alt="">');
			$.facebox.close();
			$('#show_response_beta').html(data);	
		});
		return false;
	});
</script>
