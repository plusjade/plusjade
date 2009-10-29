<?php
$page_enable = array('yes' => '', 'no' => '');
$menu_enable = array('yes' => '', 'no' => '');
$page_enable['no'] = (('no' == $page->enable)) ?  'SELECTED' : '';
$menu_enable['no'] = (('no' == $page->menu)) ?  'SELECTED' : '';
$homepage_msg = '';
$readonly = '';
if($page->page_name == $this->homepage)
{
	$homepage_msg = '<p>This is your homepage.<br>Must be enabled.</p>';
	$page_enable['yes'] = 'SELECTED';
	$readonly = 'DISABLED';
}

$add_slash = (empty($directory)) ? '' : '/';

echo form::open( "page/settings/$page->id", array('class' => 'custom_ajaxForm') );
?>
	<span class="on_close">update_menu</span>
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_page" class="jade_positive">Save Settings</button>
		<div id="common_title">Page Settings - <?php echo $page->label?></div>
	</div>
	
	<div class="common_left_panel fieldsets">
		<?php echo $homepage_msg?>
		<b>Page Access</b>
		<br><select name="enable" class="enabled_<?php echo $page->enable?>" <?php echo $readonly?>>
			<option value="yes" <?php echo $page_enable['yes']?>>Allow Access</option>
			<option value="no" <?php echo $page_enable['no']?>>No Access</option>
		</select>
		<br>
		<br>
		<b>Menu Link</b>
		<br><select name="menu" class="enabled_<?php echo $page->menu?>">
			<option value="yes" <?php echo $menu_enable['yes']?>>Show in Menu</option>
			<option value="no" <?php echo $menu_enable['no']?>>Do Not Show</option>
		</select>

	</div>
	
	<div class="common_main_panel fieldsets">
	
		<div id="new_page_url">
			Link to this page
			<br>
			<strong><?php echo url::site(),$directory,$add_slash?><span id="link_example"><?php echo $filename?></span></strong>
		</div>
		<b>Label Name</b><br>
		<input type="text" name="label" value="<?php echo $page->label?>" rel="text_req" size="30" maxlength="50">
		<br><br>
		<b>Page Link</b>
		<?php if($is_protected) echo '<span style="color:red">(protected)</span>'?>
		<br><input type="text" name="page_name" value="<?php echo $filename?>" class="auto_filename" size="30" maxlength="50">		
		
		<div id="page_exists" class="aligncenter error_msg"></div>	

		<p>
			<b>Page Title</b>
			<br><input type="text" name="title" value="<?php echo $page->title?>" style="width:500px">
		</p>
		<b>Meta description</b>
		<br><input type="text" name="meta" value="<?php echo $page->meta?>" style="width:500px">
	
	
		<p></p>
		<b>Page Template</b>
		<br><select name="template">
			<?php
			foreach($templates as $name => $desc)
				if($name == $page->template)
					echo "<option selected=\"selected\">$name</option>";
				else
					echo "<option>$name</option>";
			?>
		</select>
		<div id="template_desc">
			<?php
			foreach($templates as $name => $desc)
				echo "<div class=\"$name\">$desc</div>";
			?>
		</div>
		
		<b>Publish Page</b>
		<input type="checkbox" name="publish" value="yes"> Yes
	</div>
	
	<input type="hidden" name="old_page_name" value="<?php echo $filename?>">
	<input type="hidden" name="directory" value="<?php echo $directory?>">
</form>

<script type="text/javascript">
	
// custom validation to check for unique page_names

	var v_array = [<?php echo $page_filter_js?>];

	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			var sent_page = $("input[name='page_name']").val();			
			if(v_array.in_array(sent_page)) {	
				$('#page_exists').html('Page name already exists');
				$("input[name='page_name']").addClass('input_error');
				return false;
			}
			$(document).trigger('show_submit.plusjade');		
		},
		success: function(data) {
			// If the page name changes consider a notification or redirect logic?
			$(document).trigger('server_response.plusjade', data);			
		}		
	});
		

// visibility select dropdowns
	$("select[name='enable'], select[name='menu']").change(function(){	
		value = $('option:selected',this).val();
		if('yes' == value)
			$(this).removeClass().addClass('enabled_yes');
		else
			$(this).removeClass().addClass('enabled_no');
	});
	
// template select dropdown
	var selected = $("select[name='template'] option:selected").text();
	$('#template_desc div').hide();
	$('#template_desc div.'+selected).show();
	
	$("select[name='template']").change(function(){
		$('#template_desc div').hide();
		value = $('option:selected',this).text();
		$('#template_desc div.'+value).show();
	});
</script>