
<?php echo form::open_multipart("edit_showroom/edit/$item->id", array('class' => 'ajaxForm'))?>
	<input type="hidden" name="old_image" value="<?php echo $item->image?>">	
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_item" class="jade_positive" accesskey="enter">
			<img src="/images/check.png" alt=""/>  Save Changes
		</button>
		<div id="common_title">Edit Showroom item</div>
	</div>	
	
	<div class="fieldsets">
		<div id="edit_showroom_textarea">
			<div id="container-1">
				<ul class="ui-tabs-nav" id="showroom_textarea_tab_list">
					<li><a href="#fragment-1"><b>Item Intro</b></span></a><li>
					<li><a href="#fragment-2"><b>Item Body</b></span></a><li>
				</ul>
			
				<div id="fragment-1">
					<textarea name="intro" class="render_html"><?php echo $item->intro?></textarea>
				</div>
				
				<div id="fragment-2">
					<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
				</div>				
			</div>
		</div>

		<div id="edit_showroom_left">	
			<b>Item Name</b><br>
			<input type="text" name="name" value="<?php echo $item->name?>" rel="text_req" size="30" maxlength="50">
			<p>
				<b>Item Price</b><br>
				<input type="text" name="price" value="<?php echo $item->price?>" size="10" maxlength="5">
			</p>
			<b>Image</b> (leave blank for no change)<br>
			<input type="file" name="image">
			<br><br><img src="<?php echo $data_path.'/assets/images/showroom/'.$item->image?>" width="150px" height="150px" alt="">				
		</div>
	</div>
</form>


