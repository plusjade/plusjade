
<span class="on_close"><?php echo $js_rel_command?></span>
<?php
$views = array( 'list', 'gallery');
echo form::open("edit_showroom/settings/$showroom->id", array('class' => 'ajaxForm'));
?>
	<div  id="common_tool_header" class="buttons">
		<button type="submit" name="edit_showroom" class="jade_positive">Save Settings</button>
		<div id="common_title">Showroom Settings.</div>
	</div>	
			
	<div class="common_full_panel fieldsets">
		<b>showroom Name</b>
		<br/><input type="text" name="name" value="<?php echo $showroom->name?>">
	
		<br/><br/>
		<b>showroom View Style</b>
		<br/><select name="view">
			<?php 
				foreach($views as $view)
					if($view == $showroom->view)
						echo '<option SELECTED>'.$view.'</option>';
					else
						echo '<option>'.$view.'</option>';
			?>
		</select>
		
		<br/><br/>
		
		<b>View Params</b>
		<br/><input type="text" name="params" value="<?php echo $showroom->params?>">

		<br/><br/>
		<b>attributes</b>
		<br/><input type="text" name="attributes" value="<?php echo $showroom->attributes?>">
			
	</div>
		
</form>
