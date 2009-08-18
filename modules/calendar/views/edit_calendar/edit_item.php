
<span class="on_close"><?php echo $js_rel_command?></span>

<?php echo form::open_multipart("edit_calendar/edit/$item->id", array('class' => 'ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_item" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Calendar Event</div>
	</div>	

	<div class="common_left_panel">	
		<ul id="common_view_toggle" class="ui-tabs-nav">
			<li><a href="#calendar_title" class="selected"><b>Title</b></span></a><li>
			<li><a href="#calendar_desc"><b>Description</b></span></a><li>
		</ul>
	</div>
	
	
	<div class="common_main_panel">
	
		<div id="calendar_title" class="toggle fieldsets">
			<b>Title</b>
			<br><input type="text" name="title" value="<?php echo $item->title?>" rel="text_req" style="width:400px">	
		</div>
		
		<div id="calendar_desc" class="toggle fieldsets">
			<textarea name="desc" class="render_html"><?php echo $item->desc?></textarea>
		</div>	
		
		
	</div>
	
</form>

<script type="text/javascript">

	$('div.toggle').hide();
	$('div#calendar_title').show();
</script>

