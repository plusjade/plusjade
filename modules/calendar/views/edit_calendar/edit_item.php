
<span class="on_close"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("edit_calendar/edit/$item->id", array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_item" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Calendar Event</div>
	</div>	

	
	<ul class="common_tabs_x ui-tabs-nav">
		<li><a href="#calendar_title" class="selected"><b>Date and Title</b></span></a><li>
		<li><a href="#calendar_desc"><b>Description</b></span></a><li>
	</ul>
	
	
	<div class="common_full_panel">
	
		<div id="calendar_title" class="toggle fieldsets">
			<b>Title</b>
			<br><input type="text" name="title" value="<?php echo $item->title?>" rel="text_req" style="width:300px">	
		</div>
		
		<div id="calendar_desc" class="toggle fieldsets">
			<textarea name="desc" class="render_html"><?php echo $item->desc?></textarea>
		</div>	
		
		
	</div>
	
</form>

<script type="text/javascript">

	$(".common_tabs_x li a").click(function(){
		$('.common_tabs_x li a').removeClass('active');
		var pane = $(this).attr('href');
		$('.common_full_panel div.toggle').hide();
		$('.common_full_panel div'+ pane).show();
		return false;
	});
	$('.common_tabs_x li a:first').click();
</script>

