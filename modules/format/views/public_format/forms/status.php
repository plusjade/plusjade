

<?php echo $output?>

<?php if($success):?>
	<div class="form_status_box success">
		Your form has been submitted successfuly. 
		<br/>Thank you ! =)
	</div>
<?php else:?>
	<div class="form_status_box error">
		There was a problem sending the form =( 
	</div>
<?php endif;?>