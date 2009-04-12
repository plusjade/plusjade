
<div id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $page_id?>">
		<img src="/images/check.png" alt=""/> Save Tool Order
	</button>
	<div id="common_title">Sort Tools</div>
</div>	

<div id="page_tools">
		<?php
		$locations = array(
			'0'	=> 'Footer Static',
			'1'	=> 'Primary',
			'2'	=> 'Secondary',
			'3'	=> 'Secondary Static',
		);
		
		foreach($locations as $key => $name)
		{
			echo $name;
			echo '<ul id="'.$key.'" class="sortable ui-tabs-nav">'."\n";
			echo 'LIST ITEMS';
			echo '</ul>';
		}
		
		
		$position = 1;
		$holder = array('0');
		$once = TRUE;
		foreach($tools as $tool)
		{
			$holder[$position] = $tool->container;
			
			if ( TRUE == $once )
			{
				echo 'Container Footer';
				echo '<ul id="0" class="sortable ui-tabs-nav">'."\n";
			}
			
			if( $tool->container != $holder[$position-1])
			{
				echo '</ul>';
				echo 'Container '. $tool->container;
				echo '<ul id="' . $tool->container . '" class="sortable ui-tabs-nav">'."\n";
			}

			
			echo '<li id="'. $tool->guid .'">';
			echo '<div>';
			echo '<a href="/get/tool/move/' . $tool->guid . '" rel="facebox" class="change_page">change page</a>';
			echo ++$position.'. '.$tool->name;
			echo '</div>';
			echo '</li>'."\n";
			
			$once = FALSE;
		}
	?>
	</ul>
</div>

