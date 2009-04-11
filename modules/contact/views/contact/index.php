<div id="contact_wrapper_<?php echo $parent_id?>"class="contact_wrapper">

	<table class="contact_table">
		<?php
		foreach($contacts as $contact)
		{
			?>
			<tr>
				<td class="contact_icon">
					<img src="/images/contact/<?php echo $contact->type; ?>_icon.png" alt="icon">
				</td>
				<td id="contact_item_<?php echo $contact->id?>" class="contact_item" rel="<?php echo $contact->id?>">
					<?php echo View::factory("contact/type_views/$contact->type", array('contact'	=> $contact) )?>
				</td>
			</tr>
			<?php
		}
		?> 	
	</table>
	
</div>