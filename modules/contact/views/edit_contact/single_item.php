
<?php 
$select = array('no' => '', 'yes' => '');
if( $item->enable == 'no') $select['no'] = 'SELECTED';
	
echo form::open("edit_contact/edit/$item->id", array('class' => 'ajaxForm', 'id' => $item->id, 'rel' => $js_rel_command));	
?>
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_contact" class="jade_positive">
			<img src="<?php echo url::image_path('admin/check.png')?>" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit <?php echo $item->type?></div>
	</div>	
	
	<div class="fieldsets ">					
		<b>Display name:</b> <input type="text" name="display_name" rel="text_req" value="<?php echo $item->display_name?>">
		
		<b>Enabled:</b> 
		<select name="enable">
			<option value="yes" <?php echo $select['yes']?>>yes</option>
			<option value="no" <?php echo $select['no']?>>no</option>
		</select>	
	</div>
	
<?php
	if( 'map' == $item->type )
	{
		?>
			<div class="fieldsets">		
				<b>Link to location on Google Maps:</b><br>		
				<input type="text" name="value" value="<?php echo $item->value?>"  rel="text_req" style="width:80%">
			</div>
		<?php
	}
	else
	{
		?>
		<div class="fieldsets">		
			<b>Value</b><br>		
			<textarea name="value" class="render_html"><?php echo $item->value?></textarea>
		</div>
		<?php
	}
	?>
</form>

<script type="text/javascript">

</script>