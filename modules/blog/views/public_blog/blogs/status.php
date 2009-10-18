

<?php if(isset($output)) echo $output?>

<?php if($success):?>
	<div class="jade_form_status_box box_positive">
		Your Comment has been submitted successfuly. 
		<br/>Thank you! =)
	</div>
<?php else:?>
	<div class="jade_form_status_box box_negative">
		There was a problem submitting your comment. =( 
		<br/>Please try again later. Sorry!
	</div>
<?php endif;?>