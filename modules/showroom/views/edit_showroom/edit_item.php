
<span class="on_close two">close-2</span>


<?php echo form::open_multipart("edit_showroom/edit/$item->id", array('class' => 'custom_ajaxForm'))?>
	<input type="hidden" name="images" value="<?php echo $item->images?>">
	<input type="hidden" name="category_id" value="<?php echo $category_id?>">
	<input type="hidden" name="body" value="offline">
	<input type="hidden" name="old_category" value="<?php echo $item->showroom_cat_id?>">	
		
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive" accesskey="enter">Save Changes</button>
		<div id="common_title">Edit Showroom item</div>
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
			<br><input type="text" name="name" value="<?php echo $item->name?>" rel="text_req" maxlength="50" style="width:275px">
			<br>
			<br><b>URL</b>
			<br><input type="text" name="url" value="<?php echo $item->url?>" class="auto_filename" rel="text_req" maxlength="50" style="width:275px">
			
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
				<?php foreach($images as $image):?>
					<div class="album_images">
						<span class="handle"><b>edit</b> <em>drag</em></span>
						<img src="<?php echo "$img_path/$image->thumb"?>" alt="<?php echo $image->path?>" title="<?php echo $image->caption?>">
					</div>
				<?php endforeach;?>
			</div>
			
			
		</div>
		
		<div id="intro" class="toggle" style="display:none">
			<textarea name="intro" class="render_html"><?php echo $item->intro?></textarea>
		</div>
		
		<!-- 
		<div id="desc" class="toggle" style="display:none">
			<p><b>Extended Description</b></p>
			<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
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
  // highlight the category the item currently belongs to.
	$('#category_wrapper a#cat_<?php echo $category_id?>').addClass('active');

	
// custom ajax form
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData){
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
