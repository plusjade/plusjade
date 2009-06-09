

<div class="faq_title"><?php echo $parent->title?></div> 

<dl class="faq_list">
	<?php
	$x=0;
	foreach($items as $item)
	{
		$url_question = preg_replace("(\W)", '_', $item->question);
		$url_question = strtolower($url_question);			
		$url_question = trim($url_question, '_');
		?>
		<span id="faq_item_<?php echo $item->id?>">
			<dt class="faq_item" rel="<?php echo $item->id?>">
				<?php echo ++$x?>. <span class="minus"><img src="/assets/images/public/minus.png" alt=""></span>
				<a href="#<?php echo $url_question?>" class="toggle"><?php echo $item->question?></a>
			</dt>
			<dd id="<?php echo $url_question?>" class="faq_answer">
				<?php echo $item->answer?>
			</dd>
		</span>
		<?php
	}	
	?>		
</dl>



