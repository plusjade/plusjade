<?php
	$email = ( empty($contact->value) ) ? '(specify an email)' : $contact->value;
?>
<span class="contact_name"><?php echo $contact->display_name?></span> 

<div class="contact_value">
	<a href="#email_form" class="inline_form"><?php echo $email?></a> <small>(displays a form)</small>
</div>

<?php echo View::factory('public_contact/email_form', array('email'=> $email) )?>