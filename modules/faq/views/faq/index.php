
<div id="faq_wrapper_<?php echo $parent->id?>" class="faq_wrapper">
	
	<div class="faq_title"><?php echo $parent->title?></div> 

	<dl class="faq_list">
		<?php
		$x=0;
		foreach($items as $item)
		{
			$url_question = preg_replace("(\W)", '_', $item->question);
			$url_question = strtolower($url_question);			
			$url_question = trim($url_question, '_');
			
			echo '<dt class="faq_item" rel="'.$item->id.'">' . ++$x . '. <span class="minus"><img src="/images/public/minus.png" alt=""></span> <a href="#'.$url_question.'" class="toggle">' . $item->question . '</a></dt>' . "\n";	
			echo '<dd id="'.$url_question.'" class="faq_answer">' . $item->answer . '</dd>' . "\n";
		}	
		?>		
	</dl>

</div>
