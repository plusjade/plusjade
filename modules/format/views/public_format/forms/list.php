


<h2 class="faq_header"><?php echo $format->name?></h2> 

<form class="format_form_list">
	<?php
	foreach($format->format_items as $item)
	{
		?>
		<p id="format_item_<?php echo $item->id?>" class="format_item" rel="<?php echo $item->id?>">
			<b><?php echo $item->title?></b><br/>
		<?php
		switch($item->type)
		{
			case 'input':
				?>
					<input type="text" name="<?php echo $item->title?>">
					<br/><?php echo $item->body?>
				<?php
				break;
				
			case 'textarea':
				?>
					<textarea name="<?php echo $item->title?>"></textarea>
					<br/><?php echo $item->body?>
				
				<?php
				break;
				
			case 'select':
				?>
					<textarea name="<?php echo $item->title?>"></textarea>
					<br/><?php echo $item->body?>
				
				<?php
				break;
				
			default:
			
				break;
		}
		echo '</p>';
	}	
	?>	
		<button type="submit">Submit Form</button>
</form>



