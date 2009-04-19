<?php echo form::open_multipart("edit_showroom/add_item/$tool_id", array( 'class' => 'ajaxForm' ) )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_item" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/>  Add Item
		</button>
		<div id="common_title">Add New Showroom Item</div>
	</div>	


	<div class="fieldsets">
		
		<b>Item Name</b><br>
		<input type="text" name="name" value="" maxlength="50">

		<b>Category</b> 
		<select name="category">
			<?php
			foreach($categories as $category)
			{
				echo '<option value="'.$category->id.'">'.$category->name.'</option>'."\n";
			}
			?>
		</select>
		
		<b>Image</b> <input type="file" name="image" value>
		

		<div id="tab_container">		
			
			<ul class="ui-tabs-nav" id="showroom_textarea_tab_list">
				<li><a href="#fragment-1"><b>Item Intro</b></span></a><li>
				<li><a href="#fragment-2"><b>Item Body</b></span></a><li>
			</ul>
		
			<div id="fragment-1" class="ui-tabs-hide">
				<textarea name="intro" class="render_html"></textarea>
			</div>
			
			<div id="fragment-2" class="ui-tabs-hide">
				<textarea name="body" class="render_html"></textarea>
			</div>
			
		</div>


		
	</div>
	
	
</form>