
<span class="contact_name"><?php echo $contact->display_name?></span> 

<div class="contact_value">
	<a href="#email_form" class="inline_form"><?php echo $contact->value?></a> <small>(displays a form)</small>
</div>

<?php echo View::factory('contact/email_form', array('email'=> $contact->value) )?>