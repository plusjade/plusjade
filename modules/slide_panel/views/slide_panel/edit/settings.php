
<?php

$views = array( 'cycle', 'galleria', 'lightbox' );

echo form::open("edit_slide_panel/settings/$tool_id", array('id' => "$tool_id", 'class' => 'ajaxForm'));

	?>			
	<input type="hidden" name="id" value="<?php echo $slide_panel->id?>">

	<div class="fieldsets">
		<b>slide_panel Name</b><br>
		<input type="text" name="name" value="<?php #echo $slide_panel->name?>">
	</div>
	
	<div class="fieldsets">
		<b>slide_panel View Style</b><br>
		<select name="view">
			<?php 
				foreach($views as $view)
				{
					if($view == $slide_panel->view)
						echo '<option SELECTED>'.$view.'</option>';
					else
						echo '<option>'.$view.'</option>';
				}
			?>
		</select>
	</div>
	
	<div class="fieldsets">		
		<b>View Params</b><br>
		<input type="text" name="params" value="<?php echo $slide_panel->params?>">
	
	</div>
	
	<div class="fieldsets">
		<input type="submit" name="edit_slide_panel" value="Save">
	</div>
	
</form>
