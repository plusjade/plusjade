<?php echo form::open_multipart("edit_showroom/add_item/$tool_id", array( 'class' => 'ajaxForm' ) )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_item" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/>  Add Item
		</button>
		<div id="common_title">Add New Showroom Item</div>
	</div>	


	<div id="tab_container">		
		
		<ul class="ui-tabs-nav" id="showroom_textarea_tab_list">
			<li><a href="#fragment-1"><b>Attributes</b></span></a><li>
			<li><a href="#fragment-2"><b>Introduction</b></span></a><li>
			<li><a href="#fragment-3"><b>Main Description</b></span></a><li>
		</ul>
		
		<div id="fragment-1">
		
			<div class="fieldsets" style="float:left; width:45%;">
				
				<b>Item Name</b>
				<br><input type="text" name="name" rel="text_req" maxlength="50" style="width:275px">
				<br>
				<br><b>URL</b>
				<br><input type="text" name="url" rel="text_req" maxlength="50" style="width:275px">
			</div>

			<div class="fieldsets" style="float:left;">	
				<b>Category</b>
				<br>
				<select name="category">
					<?php
					foreach($categories as $category)
					{
						echo '<option value="'.$category->id.'">'.$category->name.'</option>'."\n";
					}
					?>
				</select>
				
				<br>
				<br><b>Image</b>
				<br><input type="file" name="image">
			</div>
	
		</div>

		<div id="fragment-2" class="ui-tabs-hide">
			<p><b>Short Introduction</b></p>
			<textarea name="intro" class="render_html"></textarea>
		</div>
		
		<div id="fragment-3" class="ui-tabs-hide">
			<p><b>Extended Description</b></p>
			<textarea name="body" class="render_html"></textarea>
		</div>
		
		
		
	</div>

	
</form>

<script type="text/javascript">
	$("input[name='name']").keyup(function(){
		input = $(this).val().replace(/\W/g, '_').toLowerCase();
		$("input[name='url']").val(input);
	});
	$("input[name='url']").keyup(function(){
		input = $(this).val().replace(/\W/g, '_');
		$(this).val(input);
	});


</script>