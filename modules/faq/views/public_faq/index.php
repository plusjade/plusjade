


<h2 class="faq_header"><?php echo $faq->title?></h2> 

<dl class="faq_list">
	<?php
	$x=0;
	foreach($faq->faq_items as $item)
	{
		$url_question = valid::filter_php_url($item->question);
		?>
		<span id="faq_item_<?php echo $item->id?>" class="faq_item" rel="<?php echo $item->id?>">
			<dt class="minus">
				<?php echo ++$x?>. <a href="#<?php echo $url_question?>" class="toggle"><?php echo $item->question?></a>
			</dt>
			<dd id="<?php echo $url_question?>" class="faq_answer">
				<?php echo $item->answer?>
			</dd>
		</span>
		<?php
	}	
	?>		
</dl>



