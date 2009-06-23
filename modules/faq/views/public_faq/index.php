


<h2 class="faq_header"><?php echo $parent->title?></h2> 

<dl class="faq_list">
	<?php
	$x=0;
	foreach($items as $item)
	{
		$url_question = preg_replace("(\W)", '_', $item->question);
		$url_question = strtolower($url_question);			
		$url_question = trim($url_question, '_');
		?>
		<span id="faq_item_<?php echo $item->id?>" class="faq_item">
			<dt class="minus" rel="<?php echo $item->id?>">
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



