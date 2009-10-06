
<?php echo form::open("page/add_builder?system_tool=$system_tool_id", array('class' => 'custom_ajaxForm') );?>	

	<div id="common_tool_header" class="buttons">
		<button type="submit" id="add_page_submit" name="add_page" class="jade_positive">Add Page Builder</button>
		<div id="common_title">Add New Page Builder</div>
	</div>	
		
	<div id="new_page_url">
		Your new page URL:
		<br><b><?php echo url::site()?><span id="link_example"><?php echo strtolower($toolname)?></span></b>
	</div>	
	
	<div class="common_left_panel">		

	</div>
	
	<div class="common_main_panel fieldsets big">

		<b>Page Label</b>
		<br><input id="" type="text" name="label" value="<?php echo $toolname?>" rel="text_req" class="send_input" maxlength="50" style="width:330px">
		<br><br>
		<b>Page Link</b>
		<br><input type="text" name="page_name" value="<?php echo strtolower($toolname)?>" class="auto_filename receive_input" maxlength="50" style="width:330px">
		<div id="page_exists" class="aligncenter error_msg"></div>
	
		<p style="line-height:1.6em">
			<b>Add to Main Menu?</b> <input type="checkbox" name="menu" value="yes" CHECKED> Yes!
		</p>

		<br><br>
		<b>**</b>Page builders need full control of the url
		<br>therefore they must exist in the main directory.
		
	</div>
</form>	

<script type="text/javascript">

	var filter = [<?php echo $filter?>];
	
// custom ajax form, validates inputs and unique page_names	
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			var sent_page = $("input[name='page_name']").val();				
			var filter_duplicates = filter.in_array(sent_page);
			
			if(filter_duplicates) {
				$('#page_exists').html('Page name already exists');
				$("input[name='page_name']").addClass('input_error');
				return false;
			}
			$('.facebox .show_submit').show();
		},
		success: function(data) {
			$('.facebox .show_submit').hide();
			$.facebox.close('facebox_2');
			var directory = 'ROOT';
			var path_for_css = directory.replace(/\//g,'_');
			$('div.'+path_for_css).append(data);
			$('#show_response_beta').html(data);				
		}
	});
</script>