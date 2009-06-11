<?php
$page_enable = array('yes' => '', 'no' => '');
$menu_enable = array('yes' => '', 'no' => '');

if($page->enable == 'no')
	$page_enable['no'] = 'SELECTED';

if($page->menu == 'no')
	$menu_enable['no'] = 'SELECTED';

echo form::open( "page/settings/$page->id", array('class' => 'custom_ajaxForm') );
?>
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_page" class="jade_positive">
			<img src="<?php echo url::image_path("check.png")?>" alt=""/> Save Settings
		</button>
		<div id="common_title">Page Settings - <?php echo $page->label?></div>
	</div>	
	<input type="hidden" name="old_page_name" value="<?php echo $filename?>">
	<input type="hidden" name="directory" value="<?php echo $directory?>">
	<?php
	if($filename != 'home')
	{		
		?>
		<div class="common_left_panel fieldsets">
			<b>Page Access</b>
			<br><select name="enable" class="enabled_<?php echo $page->enable?>">
				<option value="yes" <?php echo $page_enable['yes']?>>Allow Access</option>
				<option value="no" <?php echo $page_enable['no']?>>No Access</option>
			</select> <span><img src="<?php echo url::image_path("admin/enabled_$page->enable.png")?>" alt=""></span>
			<br>
			<br>
			<b>Menu Link</b>
			<br><select name="menu" class="enabled_<?php echo $page->menu?>">
				<option value="yes" <?php echo $menu_enable['yes']?>>Show in Menu</option>
				<option value="no" <?php echo $menu_enable['no']?>>Do Not Show</option>
			</select> <span><img src="<?php echo url::image_path("admin/enabled_$page->menu.png")?>" alt=""></span>

		</div>
		
		<div class="common_main_panel fieldsets">
			<div style="text-align:center;padding:5px;">
				Page URL: <?php echo url::site(),$directory?><span></span>
			</div>
			<b>Label Name</b><br>
			<input type="text" name="label" value="<?php echo $page->label?>" rel="text_req" size="30" maxlength="50">
			<br><br>
			<b>Page Link</b>
			<?php if($is_protected) echo '<span style="color:red">(protected)</span>'?>
			<br><input type="text" name="page_name" value="<?php echo $filename?>" size="30" maxlength="50">		
			
			<div id="page_exists" class="aligncenter error_msg"></div>	
		<?php
	}
	else
	{
		?>
		<input type="hidden" name="page_name" value="home">
		<input type="hidden" name="enable" value="yes">		

		<div class="common_left_panel fieldsets">
			<b>Menu Link</b>
			<br>
			<select name="menu" class="enabled_<?php echo $page->menu?>">
				<option value="yes" <?php echo $menu_enable['yes']?>>Show in Menu</option>
				<option value="no" <?php echo $menu_enable['no']?>>Do Not Show</option>
			</select> 
			<span><img src="<?php echo url::image_path("admin/enabled_$page->menu.png")?>"></span>
		</div>
		
		<div class="common_main_panel fieldsets">
		
			<div id="home_require_message">**Home page is required**</div> 
			<p><?php echo url::site()?><strong><?php echo $filename?></strong></p>
			
			<b>Page Name</b> (in menu)
			<br><input type="text" name="label" value="<?php echo $page->label?>" rel="text_req" size="30" maxlength="50">
		<?php
	}
	?>
			<p>
			<b>Page Title</b>
			<br><input type="text" name="title" value="<?php echo $page->title?>" style="width:500px">
			</p>
			<b>Meta description</b>
			<br><input type="text" name="meta" value="<?php echo $page->meta?>" style="width:500px">
	</div>	
</form>

<script type="text/javascript">
	/* custom validation to check for unique page_names */
	Array.prototype.in_array = function(p_val) {
		for(var i = 0, l = this.length; i < l; i++) {
			if(this[i] == p_val) {
				return true;
			}
		}
		return false;
	}
	var v_array = [<?php echo $page_filter_js?>];

	var options = {
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			sent_page = $("input[name='page_name']").val();			
			if(v_array.in_array(sent_page)) {	
				$('#page_exists').html('Page name already exists');
				$("input[name='page_name']").addClass('input_error');
				return false;
			}
			$('.facebox .show_submit').show();
			$('#show_response_beta').html('waiting for response...');			
		},
		success: function(data) {
			// If the page name changes consider a notification or redirect logic?
			$.facebox.close();
			$('.facebox .show_submit').hide();
			$('#show_response_beta').html(data);					
		}					
	};
	$(".custom_ajaxForm").ajaxForm(options);
		
	
	
	$("input[name='label']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
		$("input[name='page_name']").val(input);
	});

	$("input[name='page_name']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
		$(this).val(input);
	});

	// visibility select dropdowns
	$("select[name='enable'], select[name='menu']").change(function(){	
		value = $('option:selected',this).val();
		if('yes' == value)
			$(this).removeClass().addClass('enabled_yes').next('span').html('<img src="<?php echo url::image_path('admin/enabled_yes.png')?>">');
		else
			$(this).removeClass().addClass('enabled_no').next('span').html('<img src="<?php echo url::image_path('admin/enabled_no.png')?>">');
	});
</script>