<?php
$page_enable = array('yes' => '', 'no' => '');
$menu_enable = array('yes' => '', 'no' => '');

if($page->enable == 'no')
	$page_enable['no'] = 'SELECTED';

if($page->menu == 'no')
	$menu_enable['no'] = 'SELECTED';

	

echo form::open( "page/settings/$page->id", array('class' => 'ajaxForm') );
?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_page" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Settings
		</button>
		<div id="common_title">Page Settings - <?php echo $page->label?></div>
	</div>	
	
<?php
	if($page->page_name != 'home')
	{		
		?>

		<div class="fieldsets" style="float:left;width:65%;">
			<b>Label Name</b><br>
			<input type="text" name="label" value="<?php echo $page->label?>" rel="text_req" size="30" maxlength="50">
			<br><br>
			<b>Page Link</b><br>
			<?php echo url::site()?><input type="text" name="page_name" value="<?php echo $page->page_name?>" size="30" maxlength="50">		
		</div>
		
		<div class="fieldsets" style="float:right;width:30%;">
			<b>Page Access</b><br>
			<select name="enable" class="enabled_<?php echo $page->enable?>">
				<option value="yes" <?php echo $page_enable['yes']?>>Allow Access</option>
				<option value="no" <?php echo $page_enable['no']?>>No Access</option>
			</select> <span><img src="/images/admin/enabled_<?php echo $page->enable?>.png"></span>
			<br><br>
			<b>Menu Link</b><br>
			<select name="menu" class="enabled_<?php echo $page->menu?>">
				<option value="yes" <?php echo $menu_enable['yes']?>>Show in Menu</option>
				<option value="no" <?php echo $menu_enable['no']?>>Do Not Show</option>
			</select> <span><img src="/images/admin/enabled_<?php echo $page->menu?>.png"></span>

		</div>
		
		<?php
	}
	else
	{
		?>
		<div id="home_require_message">**Home page is required**</div> 
		<input type="hidden" name="page_name" value="home">
		<input type="hidden" name="enable" value="yes">		

		<div class="fieldsets" style="float:left;width:65%;">
			<b>Page Name</b> (in menu)<br>
			<input type="text" name="label" value="<?php echo $page->label?>" rel="text_req" size="30" maxlength="50">
			<br><br>
			<b>Link</b> <?php echo url::site()?><strong><?php echo $page->page_name?></strong>
		</div>
		
		<div class="fieldsets" style="float:right;width:30%;">
			<b>Menu Link</b><br>
			<select name="menu" class="enabled_<?php echo $page->menu?>">
				<option value="yes" <?php echo $menu_enable['yes']?>>Show in Menu</option>
				<option value="no" <?php echo $menu_enable['no']?>>Do Not Show</option>
			</select> <span><img src="/images/admin/enabled_<?php echo $page->menu?>.png"></span>
		</div>
		<?php
	}
	?>
	
	<div class="fieldsets clearboth">
		<b>Page Title</b><br>
		<input type="text" name="title" value="<?php echo $page->title?>" class="full_width">
	</div>
	
	<div class="fieldsets">
		<b>Meta description</b><br>
		<input type="text" name="meta" value="<?php echo $page->meta?>" class="full_width">
	</div>
	
</form>

<script type="text/javascript">
$("input[name='label']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
	$("input[name='page_name']").val(input);
});

$("input[name='page_name']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
	$(this).val(input);
});

$("select[name='enable'], select[name='menu']").change(function(){	
	value = $('option:selected',this).val();
	if('yes' == value)
		$(this).removeClass().addClass('enabled_yes').next('span').html('<img src="/images/admin/enabled_yes.png">');
	else
		$(this).removeClass().addClass('enabled_no').next('span').html('<img src="/images/admin/enabled_no.png">');
});


</script>