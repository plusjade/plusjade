
<style type="text/css">
	table th {background:#222; color:#fff; font-weight:normal; text-align:center;}
	table td {border:1px solid #ccc;}
	table td input {width:115px !important;}
</style>

<span class="on_close two">close-2</span>

<?php echo form::open_multipart("edit_showroom/bulk_add/$parent_id", array('class' => 'custom_ajaxForm'))?>
	<input type="hidden" name="category_id" value="">
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive" accesskey="enter">Add Items</button>
		<div id="common_title">Bulk Add Showroom Items</div>
	</div>	

	<div class="common_full_panel">
		<div class="common_half_left" style="width:620px; height:400px; overflow:auto; background:#fff">
			<table style="width:99%">
			<tr><th>#</th><th>name</th> <th>intro</th> <th>desc</th> <th>img</th></tr>
			
			<?php for($x=0 ; $x<=20 ; ++$x):?>
				<tr>
					<td><?php echo $x?></td>
					<td><input type="text" name="name[<?php echo $x?>]" rel="text_req" style="idth:275px"></td>
					<td><input type="text"  name="intro[<?php echo $x?>]"></td>
					<td><input type="text"  name="body[<?php echo $x?>]"></td>
					<td id="image_<?php echo $x?>" class="image_td"><input type="text" name="images[<?php echo $x?>]" value=""></td>
				</tr>
			<?php endfor;?>
			</table>			
		</div>
		
		<div class="common_half_right" style="width:110px;">
			<a href="#" class="test_images"> Test images</a>
			<br/><br/><b>Category</b>
			<div id="category_wrapper">
				<?php echo $categories?>
			</div>
		</div>
</form>

<script type="text/javascript">

  // setup category selection functionality
	$("#category_wrapper ul li a").click(function() {
		$('#category_wrapper ul li a').removeClass('active');
		$(this).addClass('active');
		return false;
	});
	$('#category_wrapper a:first').addClass('active');


  // checks to see if the images exist.
	$(".test_images").click(function() {
		var images = new Array();
		$('td.image_td input').each(function() {
			images.push($(this).val());
		});
		$.post('/get/edit_showroom/check_img', {'images[]':images}, function(data){
			var images = $.evalJSON(data);			
			$(images).each(function(i){
				if('good' == this)
					$('#image_' + i).css({background:'green'});
				else
					$('#image_' + i).css({background:'red'});
			});
		});
		
		return false;
	});	
	
	
	
// custom ajax form
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData, form){
			$(document).trigger('show_submit.plusjade');
			
			// assign first hidden input to selected category.
			formData[0].value = $('#category_wrapper ul li a.active').attr('rel');
		},
		success: function(data) {
			$(document).trigger('server_response.plusjade', data);	
		}
	});
</script>