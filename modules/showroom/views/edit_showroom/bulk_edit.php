
<style type="text/css">
	table th {background:#222; color:#fff; font-weight:normal; text-align:center;}
	table td {border:1px solid #ccc;}
	table td input {width:115px !important;}
</style>

<span class="on_close two">close-2</span>

<?php echo form::open_multipart("edit_showroom/bulk_edit/$parent_id", array('class' => 'custom_ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive" accesskey="enter">Save Changes</button>
		<div id="common_title">Bulk Edit Showroom Items</div>
	</div>	

	<div class="common_full_panel">
		<div class="common_half_left" style="width:620px; height:400px; overflow:auto; background:#fff">
			
		</div>
		
		<div class="common_half_right" style="width:110px;">
			<br/><br/><b>Category</b>
			<div id="category_wrapper">
				<?php echo $categories?>
			</div>
		</div>
</form>

<script type="text/javascript">

  // setup category selection functionality
	$("#category_wrapper ul li a").click(function() {
		$('.common_half_left').html('Loading...');
		$('#category_wrapper ul li a').removeClass('active');
		$(this).addClass('active');
		var cat_id = $(this).attr('rel');
		$.get('/get/edit_showroom/data',{cat_id: cat_id}, function(data){
			$('.common_half_left').html(data);
		});
		return false;
	});
	$('#category_wrapper a:first').click();

	
// custom ajax form
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData, form){
			$(document).trigger('show_submit.plusjade');		
		},
		success: function(data) {
			$(document).trigger('server_response.plusjade', data);	
		}
	});
</script>