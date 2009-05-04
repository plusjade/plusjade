
<?php
$views = array( 'list', 'gallery');

echo form::open("edit_showroom/settings/$tool_id", array('id' => "$tool_id", 'class' => 'ajaxForm'));

?>
	<div  id="common_tool_header" class="buttons">
		<button type="submit" name="edit_showroom" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Settings
		</button>
		<strong><b>Showroom</b> settings.</strong>
	</div>	
			
	<div class="fieldsets">
		<b>showroom Name</b><br>
		<input type="text" name="name" value="<?php echo $showroom->name?>">
	</div>
	
	<div class="fieldsets">
		<b>showroom View Style</b><br>
		<select name="view">
			<?php 
				foreach($views as $view)
				{
					if($view == $showroom->view)
						echo '<option SELECTED>'.$view.'</option>';
					else
						echo '<option>'.$view.'</option>';
				}
			?>
		</select>
	</div>
	
	<div class="fieldsets">		
		<b>View Params</b><br>
		<input type="text" name="params" value="<?php echo $showroom->params?>">
	
	</div>
		
</form>
