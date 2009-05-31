<style type="text/css">
	.each_link_wrapper{
		padding:5px;
	}
	.tier{
		margin:5px auto;
		width:400px;
		padding:5px;
		text-align:right;
		order:1px solid #ccc;
	}
	.hide{
		display:none;
	}
</style>

<form action="/get/edit_navigation/add/<?php echo $tool_id?>" method="POST" class="custom_ajaxForm" id="add_links_form" style="min-height:300px;">	
	<input type="hidden" name="local_parent" value="<?php echo $local_parent?>">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">
			<img src="<? echo url::image_path('check.png')?>" alt=""/> Add element
		</button>
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
			<span id="page"  class="hide">Page:
				<select name="data" disabled="disabled">
					<?php
					foreach ($pages as $page)
						echo '<option>', $page->page_name ,'</option>';
					?>
				</select>
			</span>
			<span id="url" class="hide">http://<input type="text" name="data" disabled="disabled" rel="text_req" style="width:250px"></span>
			<span id="email" class="hide">mailto:<input type="text" name="data" disabled="disabled" rel="text_req" style="width:250px"></span>
		</div>
		
	</div>
</form>

<script type="text/javascript">
	$(".facebox .toggle_type").each(function(){
		$(this).change(function(){
			var span = "#" + $(this).val();

			// Disable all @ start
			$(".hide").hide();
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
	var options = {
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
			
			text = $("input[name='item']").val();
		},
		success: function(data) {
			$simpleTreeCollection.get(0).addNode(data, text);
			$.facebox('Element added!', "status_reload", "facebox_2");
			setTimeout('$.facebox.close("facebox_2")', 500);			
		}					
	};
	$(".custom_ajaxForm").ajaxForm(options);
	



</script>