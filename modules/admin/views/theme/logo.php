
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
		$selected = (($image == $this->banner)) ? 'class="selected"' : '';
		?>
		<img src="<?php echo "$img_path/$image"?>" <?php echo $selected?> rel="<?php echo $image?>" alt="">
		<?php
		unset($selected);
	}
		?>
</div>


<script type="text/javascript">
	// add selected orange border.
	$('.common_main_panel').click($.delegate({
		'img' : function(e){
			$('.common_main_panel img').removeClass('selected');
			$(e.target).addClass('selected');
		}
	}));

	// upload a new image.
	$('.facebox .custom_ajaxForm1').ajaxForm({	
		beforeSubmit: function(){
			$('.facebox .show_submit').show();
		},			
		success: function(data) {
			var img = new Image();
			img.src = '<?php echo $img_path?>/'+ data;
			
			$('.facebox .show_submit').hide();
			$('.common_main_panel img').removeClass('selected');
			html ='<img src="<?php echo $img_path?>/'+ data +'" class="selected" rel="'+ data +'" alt="">';
			$('.common_main_panel').prepend(html);
			$('#show_response_beta').html(data);
		}
	});
	

/*
 * Change logo functionality
*/
	$('button[name="change_logo"]').click(function(){
		var image = $('.common_main_panel img.selected').attr('rel');
		if(!image) {
			alert('Select a banner first.');
			return false;
		}
		
		$('.facebox .show_submit').show();
		$.post('/get/theme/change_logo', {banner: image}, function(data){
			// the images are already loaded via this facebox so we dont need to load them again?
			$('#BANNER a').html('<img src="<?php echo $img_path?>/' + image +'" id="header_banner" alt="">');
			$.facebox.close();
			$('#show_response_beta').html(data);	
		});
		return false;
	});
	
// delete a logo
	$('button[name="delete_logo"]').click(function(){
		var image = $('.common_main_panel img.selected').attr('rel');
		if(!image) {
			alert('Select a banner to delete.');
			return false;
		}
		$('.facebox .show_submit').show();
		$.post('/get/theme/delete_logo', {banner: image}, function(data){
			$('.common_main_panel img.selected').remove();
			$('#show_response_beta').html(data);	
			$('.facebox .show_submit').hide();
		});
		return false;
	});
</script>
