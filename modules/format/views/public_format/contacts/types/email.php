<?php
	$email = (empty($item->body)) ? '(specify an email)' : $item->body;
?>

<div class="contact_value">
	<a href="#email_form" class="inline_form"><?php echo $email?></a> <small>(displays a form)</small>
</div>

<?php echo View::factory('public_format/contacts/email_form', array('email'=> $email) )?>