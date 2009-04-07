<?php echo form::open_multipart("edit_showroom/add/$tool_id", array( 'class' => 'ajaxForm' ) )?>
	<div class="fieldsets">
		<div id="edit_showroom_textarea">
			<div class="right">
				<input type="submit" name="add_item" value="Add item">
			</div>

			<div id="container-1">
				<ul class="ui-tabs-nav" id="showroom_textarea_tab_list">
					<li><a href="#fragment-1"><b>Item Intro</b></span></a><li>
					<li><a href="#fragment-2"><b>Item Body</b></span></a><li>
				</ul>
			
				<div id="fragment-1" class="ui-tabs-hide">
					<textarea name="intro"></textarea>
				</div>
				
				<div id="fragment-2" class="ui-tabs-hide">
					<textarea name="body"></textarea>
				</div>						
			</div>

		</div>
		
		<div id="edit_showroom_left">
			<h3>Add New Item</h3>			
			<b>Item Name</b><br>
			<input type="text" name="name" value="" maxlength="50">
			<p>
				<b>Item Price</b><br>
				<input type="text" name="price" value="" maxlength="5">
			</p>		
			<b>Image</b><br>
			<input type="file" name="image" value>
		</div>
	</div>
</form>