
<div id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $page_id?>">
		<img src="/images/check.png" alt=""/> Save Tool Order
	</button>
	<div id="common_title">Sort Tools</div>
</div>	

<div id="page_tools">
		<?php		
		$position		= 1;
		$containers_array = array('','','','','','');

function _create_element($guid, $name, $position, $scope)
{
	return '<li id="'. $guid .'" class="' . $scope . '" rel="' . $scope . '"><div>'
			. '<span class="change_page"><a href="#" class="scope_local">This Page</a> ... <a href="#" class="scope_global">Globalize</a> ... <a href="/get/tool/move/' . $guid . '" rel="facebox">change page</a></span>'
			. $position . '. ' . $name . '</div></li>';	
}		
		# Loop through tools on this page
		foreach($tools as $tool)
		{	
			$index = $tool->container;
			$scope = 'local';
			# if this is a global tool...
			if('5' >= $tool->page_id )
			{
				$index = $tool->page_id;
				$scope = 'global';
			}	
			$containers_array[$index] .= _create_element($tool->guid, $tool->name, ++$position, $scope);		
		}

		# display the output
		
		$counter = 100;
		foreach($containers as $key => $name)
		{
			echo $name;
			echo '<ul id="'.$key.'" class="sortable ui-tabs-nav">'."\n";
			echo $containers_array[$key];
			echo '</ul>';
		}
		
		/*
		foreach($statics as $key => $name)
		{
			echo $name;
			echo '<ul id="'.$key.'" class="sortable ui-tabs-nav">'."\n";
			echo $static_tools[$key];
			echo '</ul>';
		}
		*/
	?>
	</ul>
</div>

