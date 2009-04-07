<div class="slide_panel_wrapper" id="slide_panel_wrapper">    
	
	<div id="slide_panel_links">		
		<ul class="navigation">
			<?php
			foreach($slide_panels as $key => $slide_panel)
				echo "<li><a href=\"#toggle_{$key}\" >{$slide_panel->title}</a></li>\n";
			?>
		</ul>
	</div>
		
	<div class="scroll">
		<div class="scrollContainer">
			<?php
			foreach($slide_panels as $key => $slide_panel)
			{
				echo '<div class="slide_panel_item panel" id="toggle_' . $key . '" rel="'.$slide_panel->id.'">';
				echo $slide_panel->body;				
				echo "</div>\n";
			}
			?>
		</div>
	</div>
	
	<div style="padding:5px; text-align:center;">
		<img class="scrollButtons right" src="/images/next_arrow.gif" alt="NEXT"/>
	</div>
</div>

<div class="clearboth"></div>