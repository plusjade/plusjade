
<span id="on_close"><?php echo $js_rel_command?></span>


<?php 
$select = array('no' => '', 'yes' => '');
if( $item->enable == 'no') $select['no'] = 'SELECTED';
	
echo form::open("edit_contact/edit/$item->id", array('class' => 'ajaxForm'));	
?>
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_contact" class="jade_positive">Save & Exit</button>
		<div id="common_title">Edit <?php echo $item->type?></div>
	</div>	
	
	<div class="common_left_panel fieldsets">				
		<b>Display name</b>
		<br><input type="text" name="display_name" rel="text_req" value="<?php echo $item->display_name?>">
		<br>
		<br>
		<b>Enabled</b> 
		<br><select name="enable">
			<option value="yes" <?php echo $select['yes']?>>yes</option>
			<option value="no" <?php echo $select['no']?>>no</option>
		</select>
				
		<?php // clean this up later
		if('address' == $item->type OR 'hours' == $item->type)
		{
			?>
			<br><br>
			<h3>Insert Templates</h3>
			<a href="#" class="insert_html" rel="hours_html">Hours HTML</a>
			<br><br>
			<a href="#" class="insert_html" rel="address_html">Address HTML</a>
			<?php
		}
		?>
		
		<div id="hours_html" style="display:none">
			<span style="font-style: italic;">Call support</span>
			<br/>
			<br/>
			<div style="margin-left: 40px;">
			Monday - Sat: 8am - 8pm
			<br/>
			Sunday: Closed
			<br/>
			<br/>
			</div>
			<span style="font-style: italic;">Email support</span>
			<br/>
			<br/>
			<div style="margin-left: 40px;">Daily response time: within one hour </div>
		</div>
		<div id="address_html" style="display:none">
			<i>+Jade Super Web Services</i>
			<br>123 W. Sample Blvd.
			<br>Alhambra, Ca 91803
		</div>		
		
	</div>
	
	<div class="common_main_panel fieldsets">
		<?php
		
		switch ($item->type)
		{
			case 'phone':
				?>
				<b>Phone Number</b>	
				<br><input type="text" name="value" value="<?php echo $item->value?>"  rel="text_req" style="width:80%">
				<?php	
				break;
			case 'email':
				?>
				<b>Valid email address</b>	
				<br><input type="text" name="value" value="<?php echo $item->value?>"  rel="email_req" style="width:80%">
				<?php
				break;
			case 'address':
				?>
				<textarea name="value" class="render_html"><?php echo $item->value?></textarea>
				<?php			
				break;
			case 'hours':
				?>
				<textarea name="value" class="render_html"><?php echo $item->value?></textarea>
				<?php	
				break;
			case 'aim':
				?>
				<b>AIM screename</b>	
				<br><input type="text" name="value" value="<?php echo $item->value?>"  rel="text_req" style="width:80%">
				<?php
				break;
			case 'skype':
				?>
				<b>Valid Skype Phone Number</b>	
				<br><input type="text" name="value" value="<?php echo $item->value?>"  rel="text_req" style="width:80%">
				<?php
				break;
			case 'map':
				?>
				<b>Link to location on Google Maps:</b>	
				<br><input type="text" name="value" value="<?php echo $item->value?>"  rel="text_req" style="width:80%">
				<?php		
				break;
			case 'newsletter':
				?>
				<b>???</b>	
				<br><input type="text" name="value" value="<?php echo $item->value?>"  rel="text_req" style="width:80%">
				<?php
				break;	
		}
		?>
	</div>
</form>

<script type="text/javascript">

</script>