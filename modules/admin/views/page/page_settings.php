<?php
$page_enable = array('yes' => '', 'no' => '');
$menu_enable = array('yes' => '', 'no' => '');

if($page->enable == 'yes') 
	$page_enable['yes'] = 'SELECTED';
else 
	$page_enable['no'] = 'SELECTED';

if($menu->enable == 'yes') 
	$menu_enable['yes'] = 'SELECTED';
else 
	$menu_enable['no'] = 'SELECTED';

echo form::open( "page/settings/$page->id", array('class' => 'ajaxForm') );
?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_page" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Settings
		</button>
		<div id="common_title">Page Settings - <?php echo $menu->display_name?></div>
	</div>	
	
	<div class="fieldsets">
		<b>Page Title</b><br>
		<input type="text" name="title" value="<?php echo $page->title?>" class="full_width">
	</div>
	
	<div class="fieldsets">
		<b>Meta description</b><br>
		<input type="text" name="meta" value="<?php echo $page->meta?>" class="full_width">
	</div>
<?php
	if($menu->page_name != 'home')
	{		
		?>
		<input type="hidden" name="id" value="<?php echo  $menu->id ?>">
		<input type="hidden" name="page_id" value="<?php echo  $menu->page_id?>">
		<input type="hidden" name="old_page_name" value="<?php echo  $menu->page_name?>">
	
		<div class="fieldsets">
			<b>Page Name</b> (in menu)<br>
			<input type="text" name="display_name" value="<?php echo $menu->display_name?>" rel="text_req" size="30" maxlength="50">
		</div>
		
		<div class="fieldsets">
			<b>Page Link</b><br>
			<?php echo url::site()?><input type="text" name="page_name" value="<?php echo $menu->page_name?>" size="30" maxlength="50">		
		</div>
		
		<div class="fieldsets">
			<b>Page Access</b><br>
			<select name="page_enable">
				<option value="yes" <?php echo $page_enable['yes']?>>Allow Access</option>
				<option value="no" <?php echo $page_enable['no']?>>No Access</option>
			</select>
			
			<br><br>
			
			<b>Menu Link</b><br>
			<select name="menu_enable">
				<option value="yes" <?php echo $menu_enable['yes']?>>Show in Menu</option>
				<option value="no" <?php echo $menu_enable['no']?>>Do Not Show</option>
			</select>

		</div>
		
		<?php
	}
	else
	{
		?>
		<input type="hidden" name="id" value="<?php echo  $menu->id?>">
		<input type="hidden" name="page_name" value="home">
		<input type="hidden" name="page_enable" value="yes">		

		<div class="fieldsets">
			<b>Page Name</b> (in menu)<br>
			<input type="text" name="display_name" value="<?php echo $menu->display_name?>" rel="text_req" size="30" maxlength="50">
		</div>
		
		<div class="fieldsets">
			<b>Link</b> <?php echo url::site("$menu->page_name")?>	
		</div>
		
		<div class="fieldsets">
			<b>Menu Link</b><br>
			<select name="menu_enable">
				<option value="yes" <?php echo $menu_enable['yes']?>>Show in Menu</option>
				<option value="no" <?php echo $menu_enable['no']?>>Do Not Show</option>
			</select>
		</div>
		
		<div id="home_require_message">**Home page is required**</div> 

		<?php
	}
	?>	
</form>