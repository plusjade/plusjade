
<?php echo form::open('admin', array('class' => 'ajaxForm', 'rel' => $js_rel_command))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Save Changes</button>
		<div id="common_title">Sitewide Settings</div>
	</div>

	<div class="common_left_panel">

	</div>
	
	<div class="common_main_panel fieldsets">	
	
		<b>Custom domain name</b>
		<br>http://www.<input type="text" name="custom_domain" value="<?php echo $custom_domain?>" style="width:400px">
	
		<br><br>
		
			<b>Homepage</b>
			<select name="homepage">
				<?php
				foreach($pages as $page)
					if($this->homepage == $page->page_name)
						echo "<option selected=\"selected\">$page->page_name</option>";
					else
						echo "<option>$page->page_name</option>";
				?>
			</select>
			
		<br><br>
		
		<b>Set Timezone</b>
		<select>
			<option>timezone list</option>
		</select>
		
		<br><br>
		Toggle Site building Wizard. 
		<br> The site wizard walks you through all the basic controls 
		from start to finish for getting your site up.
		It's enabled when users first create the site. You can bring it back,
		at any time by clicking this option!
<?php
	/*
	echo '<pre>';
	foreach(DateTimeZone::listAbbreviations() as $value)
		foreach($value as $value)
			print_r($value);
	echo '</pre>';
	*/
	echo strftime("%Y-%m-%d %H:%M:%S");
?>	
	</div>
	
</form>
	
	
	
	
	
	