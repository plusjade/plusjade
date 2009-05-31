<?php
	$data = $selected = array(
		'none'	=> '',
		'page'	=> '',
		'url'	=> '',
		'email'	=> '',
		'file'	=> '',
	);	
	$selected[$item->type] = 'selected="selected"';
	
	$data[$item->type] = $item->type;
?>

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

<form action="/get/edit_navigation/edit/<?php echo $item->id?>" method="POST" class="custom_ajaxForm" id="add_links_form" style="min-height:300px;">	

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">
			<img src="<? echo url::image_path('check.png')?>" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit Navigation element</div>
	</div>	
	
	<div id="common_tool_info">
		=D
	</div>
	
	<div class="fieldsets">
		
		<div class="tier">
			Type:
			<select class="toggle_type" name="type" style="width:250px">
				<option value="none" <?php echo $selected['none']?>>Label (no link)</option>
				<option value="page" <?php echo $selected['page']?>>Link to +Jade Page</option>
				<option value="url" <?php echo $selected['url']?>>Link to external Page</option>
				<option value="email" <?php echo $selected['email']?>>Link to email address</option>
				<option value="file" <?php echo $selected['file']?>>Link to +Jade file</option>	
			</select>
			<br><img src="<?php echo url::image_path('admin/arrow_right_down.png')?>" alt="next">  
		</div>
		
		<div class="tier">
			Label <input type="text" name="item" value="<?php echo $item->display_name?>" rel="text_req" style="width:250px">
		</div>
		
		<div class="tier">		
			<span id="page"  class="hide">Page:
				<select name="data" disabled="disabled">
					<?php
					foreach ($pages as $page)
					{
						if( $item->data == $page->page_name )
						{
							echo '<option selected="selected">', $page->page_name ,'</option>';
						}
						else
						{
							echo '<option>', $page->page_name ,'</option>';
						}
					}
					?>
				</select>
			</span>
			<span id="url" class="hide">http://<input type="text" name="data" value= "<?php echo $data['url']?>" disabled="disabled" rel="text_req" style="width:250px"></span>
			<span id="email" class="hide">mailto:<input type="text" name="data" value= "<?php echo $data['email']?>" disabled="disabled" rel="text_req" style="width:250px"></span>
		</div>
		
	</div>
	
</form>

<script type="text/javascript">

	$('span#<?php echo $item->type?> > :input').removeAttr("disabled");
	$('span#<?php echo $item->type?>').show();	
	
	$(".facebox .toggle_type").each(function(){
		$(this).change(function(){
			var span = "#" + $(this).val();
			
			// Disable to start over
			$(".hide").hide();
			$(".hide > :input").attr("disabled","disabled");
			
			// Enable selection
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
			$('li span.active').html(text);
			
			//$simpleTreeCollection.get(0).addNode(data, text);
			$.facebox('Changes Saved!', "status_reload", "facebox_2");
			setTimeout('$.facebox.close("facebox_2")', 500);			
		}					
	};
	$(".custom_ajaxForm").ajaxForm(options);
	

	
	
	

</script>