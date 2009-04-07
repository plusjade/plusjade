<?php
//$vitals = $query_vitals->row();

if(!empty($error))
	echo $error;

if(!empty($success))
{
	echo '<ul>';
	foreach($success as $item => $value)
		echo '<li> '.$item.': '.$value.'</li>';
		
	echo '</ul>';	
}


?>
<div id="picker" style="float: right;"></div>
 				
<?php 
$destination = url::site('admin/submit');

print form::open($destination, array('enctype' => 'multipart/form-data'));


	
/*

echo '<select name="theme">';
$enabled_themes = array('round', 'redcross', 'blah');
foreach($enabled_themes as $themes)
{
	if($vitals->theme == $themes)
		echo '<option SELECTED>'.$themes.'</option>';
	else
		echo '<option>'.$themes.'</option>';
}
echo '</select>';
*/

?>		
<h3>GLobal CSS</h3>

<?php 
	foreach($background as $key => $value)
	{
		$color_box = ''; $image_box = ''; $checked = '';
		if (strpos($value, '.',1))
		{
			$image_box = 'highlight'; 
			$checked = 'CHECKED';
		}
		else
			$color_box = 'highlight';
?>
	<div class="custom_boxes">
		<b><?php echo $key ;?>:</b><br>
		
		
		<div id="color_box" class="<?php echo $color_box ?>">	
			Color: <input type="text" name="<?php echo $key?>[0]" value="<?php echo $value?>" class="colorwell"><br>
		</div>
			
		<div id="image_box" class="<?php echo $image_box ?>">
			image: <input type="checkbox" name="<?php echo $key?>[1]" <?php echo $checked?>>
			<select name="<?php echo $key?>[2]">
			<?php
				foreach($saved_backgrounds as $image)
				{
					if($value == $image)
						echo "<option selected=\"selected\">{$image}</option>\n";
					else
						echo "<option>{$image}</option>\n";
				}
			?>
			</select>
		</div>
	</div>
<?php

		unset($color_box); unset($image_box); unset($checked);
	}

	foreach($saved_backgrounds as $key => $image)
	{
		echo '<img src="'."$data_path/themes/$theme_name/global/images/$image".'"> '.$image.'<br>'."\n";
	}

?>

<p>
	<input type="submit" value="update" name="submit_theme">	
</p>
</form>	
  