
<span class="contact_name"><?php echo $contact->display_name?></span> 

<div class="contact_value newsletter">
	

<!-- CHANGE THIS FOR LIVE campaign-monitor -->
	<form action="http://plusjade.createsend.com/t/r/s/ydkjuy/" id="newsletter_form" method="post">
		
		<label for="name">Name:</label> <input name="cm-name" id="name" type="text" rel="text_req"> 	
		 <label for="ydkjuy-ydkjuy">Email:</label> <input name="cm-ydkjuy-ydkjuy" id="ydkjuy-ydkjuy" type="text" rel="text_req">
		
		<div class="buttons">
			<button type="submit" name="submit" class="jade_positive">
				Join Us!
			</button>
		</div>
		
	</form>

<!-- CHANGE THIS FOR LIVE mailchimp -->
<!--
<div id="mc_embed_signup">

	<form action="http://highlandersolutions.list-manage.com/subscribe/post?u=5dfbf40d237e21a2e29cebcfc&amp;id=73f103e383" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
	
		<div>
			<label for="mce-EMAIL">Email Address:</label>
			<input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL">
		</div>
		<div>
			<label for="mce-FNAME">First Name:</label>
			<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
		</div>
		
		<div><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn"></div>

	</form>

</div>
-->
<!--mc_embed_signup-->
		
	<?php echo $contact->value?>

	
</div>

