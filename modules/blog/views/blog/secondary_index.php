	<h3>Knowledge Base</h3>
	<div class="align_right">
	  <input type="text" size="30" name="" value="">
	 <input type="submit" value="Search">
	</div>
	<div id="sample_faq_forum">
	<?php 
		for($x=0 ; $x < 10 ; $x++){
			if($x%2 == 0)
				echo "<div></div>\n";
			else
				echo "<div style=\"background:#eee;\"></div>\n";
		}
/*  end of secondary_main.php */
	