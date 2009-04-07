<?php $value = '<a href="mailto:'.$contact->value.'">'.$contact->value.'</a>';?>

<div id="email_form_link">
	<a href="#email_form" class="inline_form">Send Email Via Form</a>
</div>
	
<span class="contact_name"><?php echo $contact->display_name?></span> 

<div class="contact_value">
	<?php echo $value?>
</div>

<?php echo View::factory('contact/email_form')?>