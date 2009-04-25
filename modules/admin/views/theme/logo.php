

<?php echo form::open('theme/add_logo', array('enctype' => 'multipart/form-data', 'class' => 'ajaxForm') )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="upload_logo" class="jade_positive">
			<img src="/images/check.png" alt=""/> Upload Logo
		</button>
		<div id="common_title">Configure Logo</div>
	</div>

	<div id="common_tool_info">
		<b>Add Logo</b> <input type="file" name="image" rel="text_req">
		<p>Make this my new logo? <input type="checkbox" name="enable"> YES!</p>
	</div>
	
</form>

<?php
if(count($saved_banners) > 0)
{
	echo form::open('theme/change_logo', array( 'class' => 'ajaxForm' ));
	foreach($saved_banners as $key => $image)
	{
		if($image == $_SESSION['banner'])
		{
			echo '<label FOR="radio_'.$key.'"><img src="'."/data/$this->site_name/assets/images/banners/$image".'" id="selected_banner"></label><br>'."\n";
			echo '<input type="radio" name="banner" value="'.$image.'" id="radio_'.$key.'" CHECKED> Select<br>'."\n";
		}
		else
		{
			echo '<label FOR="radio_'.$key.'"><img src="'."/data/$this->site_name/assets/images/banners/$image".'"></label><br>'."\n";
			echo '<input type="radio" name="banner" value="'.$image.'" id="radio_'.$key.'" > <b class="jade_red">Select</b><br>'."\n";
		}
	}
	?>
	<div class="buttons">
		<button type="submit" name="change_logo" class="jade_positive">
			<img src="/images/admin/check.png" alt=""/> Change Logo
		</button>
		<button style="display:none" type="submit" name="delete_logo" class="jade_negative">
			<img src="/images/admin/cross.png" alt=""/> Delete Logo
		</button>		
	</div>
	<?php
	echo '</form>';
}
else
{
	echo 'Use the form above to upload your logo';
}
?>