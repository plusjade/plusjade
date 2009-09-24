
<div id="format_title_list">		
	<ul class="navigation">
		<?php
		foreach($format->format_items as $item)
			echo "<li><a href=\"#toggle_{$item->id}\" >{$item->title}</a></li>\n";
		?>
	</ul>
</div>
	
<div class="scroll">
	<div class="scrollContainer">
		<?php
		foreach($format->format_items as $item)
		{
			echo '<div class="format_item panel" id="toggle_' . $item->id . '" rel="'.$item->id.'">';
			echo $item->body;				
			echo "</div>\n";
		}
		?>
	</div>
</div>

<div style="clear:both; padding:5px; text-align:center;">
	<img class="scrollButtons right" src="/_assets/images/admin/next_arrow.png" alt="NEXT"/>
</div>


<div class="clearboth"></div>