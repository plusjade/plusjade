

<?php if(isset($output)) echo $output?>

<?php if($success):?>
	<div class="jade_form_status_box box_positive">
		Your form has been submitted successfuly. 
		<br/>Thank you ! =)
	</div>
<?php else:?>
	<div class="jade_form_status_box box_negative">
		There was a problem sending the form =( 
	</div>
<?php endif;?>