
<div id="common_tool_header" class="buttons">
	<button type="submit" id="link_save_sort" class="jade_positive" rel="<?php echo $page_id?>">
		<img src="/images/check.png" alt=""/> Save Tool Order
	</button>
	<div id="common_title">Sort Tools</div>
</div>	

<div id="page_tools">
	<?php		
	function _create_element($guid, $name, $position, $scope)
	{
		$toggle = ('global' == $scope ) ? 'local': 'global' ;
		
		return '<li id="'. $guid .'" class="' . $scope . '" rel="' . $scope . '"><div>'
				. '<span class="change_page">
					<a href="#" class="toggle_scope" rel="'.$toggle.'">Make '.$toggle.'</a> 
					<a href="/get/tool/move/' . $guid . '" rel="facebox">change page</a>
				</span>' . $position . '. ' . $name . '</div></li>';	
	}
	$position			= 1;
	$containers_array	= array('','','','','','');
	
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
	foreach($containers as $key => $name)
	{
		echo $name, '<ul id="'.$key.'" class="sortable ui-tabs-nav">'."\n";
		echo $containers_array[$key], '</ul>';
	}
	?>
</div>
