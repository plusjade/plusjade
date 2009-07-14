
<span class="icon cross floatright">&#160; &#160; </span>

<form action="/get/edit_navigation/add/<?php echo $tool_id?>" method="POST" class="custom_ajaxForm" id="add_links_form">	
	<input type="hidden" name="local_parent" value="<?php echo $local_parent?>">

	<div id="common_tool_header">
		<div id="common_title">Add element to Navigation</div>
	</div>	
	
	<div id="common_tool_info">
		Choose which kind of element you wish to add.
	</div>
	
	<div class="fieldsets">
		
		<div class="tier">
			Type:
			<select class="toggle_type" name="type" style="width:250px">
				<option value="none">Label (no link)</option>
				<option value="page">Link to +Jade Page</option>
				<option value="url">Link to external Page</option>
				<option value="email">Link to email address</option>
				<option value="file">Link to +Jade file</option>	
			</select>
		</div>
		
		<div class="tier">
			Label <input type="text" name="item" rel="text_req" style="width:250px">
		</div>
		
		<div class="tier">		
			<span id="page" style="display:none">Page:
				<select name="data" disabled="disabled">
					<?php
					foreach ($pages as $page)
						echo '<option>', $page->page_name ,'</option>';
					?>
				</select>
			</span>
			<span id="url" style="display:none">http://<input type="text" name="data" disabled="disabled" rel="text_req" style="width:250px"></span>
			<span id="email" style="display:none">mailto:<input type="text" name="data" disabled="disabled" rel="email_req" style="width:250px"></span>
		</div>
		
	</div>
	<br>
	<button type="submit" class="jade_positive">Create Element</button>
</form>

<script type="text/javascript">	
	$(".facebox .toggle_type").each(function(){
		$(this).change(function(){
			var span = "#" + $(this).val();

			// Disable all @ start
			$(".tier span").hide();
			$(".hide > :input").attr("disabled","disabled");
			
			// Enable single input
			$(span + " > :input").removeAttr("disabled");
			$(span).show();
		});
	});
</script>