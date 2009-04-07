 <div id="juicy_full_panel">
	<h2>Frequently Asked Questions.</h2>  
	<dl id="faq_container">
	  <?php
	  $x=1;
	  foreach($faqs as $faq)
	  {
		echo '<dt>' . $x . '. <a href="#">' . $faq->question . '</a></dt>' . "\n";	
		echo '<dd>' . $faq->answer . '</dd>' . "\n";
		++$x;
	  }	
	  ?>		
	</dl>
  </div>