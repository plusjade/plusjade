
<form action="/get/edit_navigation/add/<?php echo $tool_id?>" method="POST" class="custom_ajaxForm" id="add_links_form">	
	<input type="hidden" name="local_parent" value="<?php echo $local_parent?>">

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Create Element</button>
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
			<br><img src="<?php echo url::image_path('admin/arrow_right_down.png')?>" alt="next">  
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

	/* 
	 * custom ajax form response needs to populate the nested li list.
	 *
	 */		
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(){					
			if(! $(".custom_ajaxForm input:enabled").jade_validate() )
				return false;
			/*	
			type = $("select[name='type'] > option:selected").val();			
			
			if('none' == type)
				text = $("input[name='item']").val();
			else
				text = $("[name='data']:enabled").val();
			*/
			$('.facebox .show_submit').show();
			text = $("input[name='item']").val();	
		},
		success: function(data) {
			// TODO: This does not work in chrome and safari
			$simpleTreeCollection.get(0).addNode(data, text);
			$.facebox.close("facebox_2");
			$('.facebox .show_submit').hide();
			$('#show_response_beta').html(data);	
		}
	});
	



</script>