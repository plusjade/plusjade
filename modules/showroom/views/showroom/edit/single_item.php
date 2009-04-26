
<?php echo form::open_multipart("edit_showroom/edit/$item->id", array('class' => 'ajaxForm'))?>
	<input type="hidden" name="old_image" value="<?php echo $item->img?>">	
	<input type="hidden" name="old_category" value="<?php echo $item->cat_id?>">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_item" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/>  Save Changes
		</button>
		<div id="common_title">Edit Showroom item</div>
	</div>	
	
	<div id="container-1">
		<ul class="ui-tabs-nav" id="showroom_textarea_tab_list">
			<li><a href="#fragment-1"><b>Attributes</b></span></a><li>
			<li><a href="#fragment-2"><b>Introduction</b></span></a><li>
			<li><a href="#fragment-3"><b>Main Description</b></span></a><li>
		</ul>

		<div id="fragment-1">
		
			<div class="fieldsets" style="float:left; width:45%;">
				
				<b>Item Name</b>
				<br><input type="text" name="name" value="<?php echo $item->name?>" rel="text_req" maxlength="50" style="width:275px">
				<br>
				<br><b>URL</b>
				<br><input type="text" name="url" value="<?php echo $item->url?>" rel="text_req" maxlength="50" style="width:275px">
			</div>

			<div class="fieldsets" style="float:left;">	
				<b>Category</b>
				<br>
				<select name="category">
					<?php 
					foreach($categories as $category)
					{
						if ($item->cat_id == $category->id)
							echo '<option value="'.$category->id.'" selected="selected">'.$category->name.'</option>'."\n";
						else
							echo '<option value="'.$category->id.'">'.$category->name.'</option>'."\n";
					}
					?>
				</select>
				
				<br>
				<br><b>Image</b> (leave blank for no change)
				<br><input type="file" name="image">
				<br><br><img src="<?php echo $data_path.'/assets/images/showroom/'."$item->cat_id/sm_$item->img"?>" width="150px" height="150px" alt="">			
			</div>
	
		</div>
		
		<div id="fragment-2">
			<p><b>Short Introduction</b></p>
			<textarea name="intro" class="render_html"><?php echo $item->intro?></textarea>
		</div>
		
		<div id="fragment-3">
			<p><b>Extended Description</b></p>
			<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
		</div>				
	</div>
</form>

<script type="text/javascript">
	$("#container-1").tabs();
	$("input[name='name']").keyup(function(){
		input = $(this).val().replace(/\W/g, '_').toLowerCase();
		$("input[name='url']").val(input);
	});
	$("input[name='url']").keyup(function(){
		input = $(this).val().replace(/\W/g, '_');
		$(this).val(input);
	});
</script>
