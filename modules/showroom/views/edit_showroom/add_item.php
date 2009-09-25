

<span class="on_close two">close-2</span>

<?php echo form::open_multipart("edit_showroom/add_item/$parent_id", array('class' => 'custom_ajaxForm'))?>
	<input type="hidden" name="images" value="">
	<input type="hidden" name="category_id" value="">
	<input type="hidden" name="body" value="offline">
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive" accesskey="enter">Add Item</button>
		<div id="common_title">Add New Showroom Item</div>
	</div>	

	<ul class="common_tabs_x ui-tabs-nav">
		<li><a href="#params"><b>Attributes</b></span></a><li>
		<li><a href="#images"><b>Images</b></span></a><li>
		<li><a href="#intro"><b>Introduction</b></span></a><li>
		<li><a href="#desc"><b>Main Description</b></span></a><li>
	</ul>
	
	<div class="common_full_panel">
	
		<div id="params" class="toggle fieldsets">
			<b>Item Name</b>
			<br/><input type="text" name="name" class="send_input" rel="text_req"  maxlength="50" style="width:275px">
			<br/><br/>
			<b>URL</b>
			<br/><input type="text" name="url" class="auto_filename receive_input" rel="text_req" maxlength="50" style="width:275px">
			
			<br/><br/>
			<b>Category</b>
			<div id="category_wrapper">
				<?php echo $categories?>
			</div>
			
		</div>

		<div id="images" class="toggle" style="display:none">	
			
			
			<div class="common_left_panel aligncenter">
				<a href="#" class="get_file_browser images" rel="albums" title="Add images">&#160; &#160;</a>
				
				<div id="image_trash"></div>
				<div><b>Drag images to Trash</b></div>
				<br/><a href="#" id="remove_images">Remove Selected images</a>
			</div>

			<div id="sortable_images_wrapper" class="common_main_panel" style="height:350px; overflow:auto">	

			</div>
			
			
		</div>
		
		<div id="intro" class="toggle" style="display:none">
			<textarea name="intro" class="render_html"></textarea>
		</div>
		
		<!-- 
		<div id="desc" class="toggle" style="display:none">
			<p><b>Extended Description</b></p>
			<textarea name="body" class="render_html"></textarea>
		</div>	
		-->
	</div>		
		

</form>

<script type="text/javascript">


  // setup common tabs functionality
	$(".common_tabs_x li a").click(function(){
		$('.common_tabs_x li a').removeClass('active');
		var pane = $(this).attr('href');
		$('.common_full_panel div.toggle').hide();
		$('.common_full_panel div'+ pane).show();
		return false;
	});
	$('.common_tabs_x li a:first').click();

// Load image album user interface functions.
<?php include Kohana::find_file('views', 'javascripts/image_album_ux', FALSE, 'js');?>
		

  // setup category selection functionality
	$("#category_wrapper ul li a").click(function(){
		$('#category_wrapper ul li a').removeClass('active');
		$(this).addClass('active');
		return false;
	});
	$('#category_wrapper a:first').addClass('active');
	
// custom ajax form
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData, form){
			if(! $("input", form[0]).jade_validate()) return false;	
			$('.facebox .show_submit').show();
			// JSONize image selections
			var data = new Array();
			$('#sortable_images_wrapper img').each(function(){
				var img = new Object();
				img.path = $(this).attr('alt');
				img.caption = $(this).attr('title');
				data.push(img);
			});
			// assign to first input, image variable.
			formData[0].value = $.toJSON(data);
			// assign second input to selected category.
			formData[1].value = $('#category_wrapper ul li a.active').attr('rel');
		},
		success: function(data) {
			$(document).trigger('server_response.plusjade', data);	
		}
	});
</script>