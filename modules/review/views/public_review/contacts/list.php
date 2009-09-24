

<?php foreach($format->format_items as $item):?>
	<div id="contact_item_<?php echo $item->id?>" class="contact_item" rel="<?php echo $item->id?>">	
		
		<div class="contact_icon <?php echo $item->type?>">
			&#160;
		</div>	
		
		<div class="contact_view">
			<div class="contact_name"><?php echo $item->title?></div> 
			<?php 
			echo View::factory(
				"public_format/contacts/types/$item->type",
				array('item' => $item)
			);
			?>
		</div>
	</div>
<?php endforeach;?> 	




