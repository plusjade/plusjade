
<?php 
$select = array('no' => '', 'yes' => '');
if( $item->enable == 'no') $select['no'] = 'SELECTED';		



$formatted_html = '
<span style="font-style: italic;">Call support</span>
<br>
<br>
<div style="margin-left: 40px;">
	Monday - Sat: 8am - 8pm
	<br>Sunday: Closed
</div>

<span style="font-style: italic;">Email support</span>
<span style="font-weight: bold;"></span>
<br>
<br>
<div style="margin-left: 40px;">
	Daily response time: within one hour
</div>
';
echo form::open("edit_contact/edit/$item->id", array('class' => 'ajaxForm', 'id' => $item->id));	
	?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_contact" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Changes
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
			<textarea id="contact_textarea" name="value" class="render_html" style="height:450px"><?php echo $item->value?></textarea>
		</div>
		<?php
	}
	?>
</form>

<script type="text/javascript">
	$(".facebox #place_address").click(function(){
		var html = $(".facebox #address_container").html();
		$(".facebox #contact_textarea").html(html);
		return false;
	});
</script>