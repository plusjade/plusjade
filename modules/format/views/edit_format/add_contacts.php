
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/add/<?php echo $parent_id?>" method="POST" class="ajaxForm">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_images" class="jade_positive">Add Contact</button>
		<div id="common_title">Add New Contact</div>
	</div>	
	
	<div class="common_left_panel">	
		<ul id="common_view_toggle" class="ui-tabs-nav">
			<li><a href="#contact_view" class="selected"><b>Views</b></span></a><li>
			<li><a href="#contact_body"><b>Body</b></span></a><li>
		</ul>
	</div>

	
	<div class="common_main_panel">
	
		<div id="contact_view" class="toggle fieldsets">

			<b>Contact Type</b>
			<select name="type">
				<option>hours</option>
				<option>address</option>
				<option>phone</option>
				<option>newsletter</option>
				<option>email</option>
				<option>map</option>
			</select>
			
			<br><br>
			
			<b>Name</b>
			<br><input type="text" name="title" style="width:400px">
			
		</div>

		<div id="contact_body" class="toggle fieldsets">
			<textarea name="body" class="render_html"></textarea>
		</div>
		
	</div>
	
</form>

<script type="text/javascript">

	$('div.toggle').hide();
	$('div#contact_view').show();

</script>