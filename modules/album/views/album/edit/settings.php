
<?php
$views = array( 'cycle', 'galleria', 'lightbox' );


$galleria = array( 'left', 'right', 'bottom' );


echo form::open("edit_album/settings/$tool_id", array('id' => "$tool_id", 'class' => 'ajaxForm'));
	?>			
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_album" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Settings
		</button>
		<div id="common_title">Edit Settings</div>
	</div>	
	
	<input type="hidden" name="id" value="<?php echo $album->id?>">
	
	<div class="fieldsets">
		<b>ALbum Name</b><br>
		<input type="text" name="name" value="<?php echo $album->name?>">
	</div>
	
	<div class="fieldsets">
		<b>Album View Style</b><br>
		<select name="view">
			<?php 
				foreach($views as $view)
				{
					if($view == $album->view)
						echo '<option SELECTED>'.$view.'</option>';
					else
						echo '<option>'.$view.'</option>';
				}
			?>
		</select>
	</div>


	<div class="fieldsets">
		<b>blah</b><br>
		<select name="asdfa params">
			<?php 
				foreach($galleria as $option)
				{
					if($option == $album->params)
						echo '<option SELECTED>'.$option.'</option>';
					else
						echo '<option>'.$option.'</option>';
				}
			?>
		</select>
	</div>

	
	<div class="fieldsets">		
		<b>Params</b><br>
		<input type="text" name="params" value="<?php echo $album->params?>">
	
	</div>
		
</form>
<div id="output"></div>