
<div class="email_form_wrapper">
	<?php echo form::open("contact/email_form", array('class'=> 'public_ajaxForm'))?>
		<input type="hidden" name="sendto" value="<?php echo $email?>">
		<table>
		<tr><th colspan="2">Email Us Directly with this Handy Form.</th></tr>
		<tr>
			<td>
				<div class="name_div">
					<b>Your Name</b> <small>- required</small><br>
					<input type="text" name="name" rel="text_req">
				</div>
				
				<div class="email_div">
					<b>Your Email</b> <small>- required (so we can get back to you)</small><br>
					<input type="text" name="email" rel="email_req">
				</div>
				
				<div class="phone_div">
					<b>Your Phone #</b><br>
					<input type="text" name="phone">
				</div>
			</td>

			<td>	
				<div>
					<b>Message</b> <small>- required</small><br>
					<textarea name="message" class="email_form_message"></textarea>
				</div>
			</td>
		</tr>
		
		<tr><td colspan="2" class="buttons">
			<button type="submit" name="submit">Send Email</button>	
		</td></tr>
		</table>

		<div class="aligncenter">
			Want to use your own email client?
			<br><?php echo "<a href=\"mailto:$email\">$email</a>"?>
		</div>
	
	</form>
	

	
</div>