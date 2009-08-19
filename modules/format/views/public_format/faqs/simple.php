


<h2 class="faq_header"><?php echo $format->name?></h2> 

<dl class="faq_list">
	<?php
	$x=0;
	foreach($format->format_items as $item)
	{
		$url_question = valid::filter_php_url($item->title);
		?>
		<span id="format_item_<?php echo $item->id?>" class="format_item" rel="<?php echo $item->id?>">
			<dt class="minus">
				<?php echo ++$x?>. <a href="#<?php echo $url_question?>" class="toggle"><?php echo $item->title?></a>
			</dt>
			<dd id="<?php echo $url_question?>" class="faq_answer">
				<?php echo $item->body?>
			</dd>
		</span>
		<?php
	}	
	?>		
</dl>



