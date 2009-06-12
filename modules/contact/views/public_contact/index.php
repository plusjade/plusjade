

<?php
foreach($contacts as $contact)
{
	?>
	<div id="contact_item_<?php echo $contact->id?>" class="contact_item" rel="<?php echo $contact->id?>">	
		
		<div class="contact_icon">
			<img src="/assets/images/contact/<?php echo $contact->type; ?>_icon.png" alt="icon">
		</div>	
		
		<div class="contact_view">
			<div class="contact_name"><?php echo $contact->display_name?></div> 
			<?php 
			echo View::factory(
				"public_contact/type_views/$contact->type",
				array('contact' => $contact)
			);
			?>
		</div>
	</div>

	<?php
}
?> 	

