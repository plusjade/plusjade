
<div id="common_tool_header">
	<div id="common_title">All Site Tools</div>
</div>

<div id="common_tool_info">
	<b style="color:blue">Blue Tools</b> = belong to a local page.
	<br><b style="color:orange">Orange Tools</b> = belong to all pages.
	<br><b style="color:red">Red Tools</b> = orphans.
	<br>Delete the tool if you no longer need it, or place the tool on another page!
</div>
<div style="height:350px; overflow:auto">
<table id="all_tools_table">
<?php 
	$names = array('');
	$start = 1;
	foreach($tools as $key => $tool)
	{
		$names[$start] = $tool->name;
		$last = $start-1;
		
		# Filter orphans
		$page_name = $tool->page_name;
		$class = 'safe';

		if ( '10' > $tool->page_id )
		{
			$page_name = 'GLOBAL';
			$class = 'global';		
		}
		elseif( empty($tool->page_name) )
		{
			$page_name = 'none';
			$class = 'orphan';
		}
		
		if($tool->name != $names[$last])
		{
			?>
			<tr class="table_header">
				<th colspan="3"><?php echo $tool->name?></th>
			</tr>
			<?php
		}
				
		?>	
			<tr class="row_wrapper <?php echo $class?>">
				<td class="row_guid">Guid:<?php echo $tool->guid?></td>
				<td class="row_page">Page <b><?php echo $page_name?></b></td>
				<td class="row_change"><a href="/get/tool/move/<?php echo $tool->guid?>" rel="facebox" id="blah">move</a></td>
				</td>
				<td class="row_delete"><a href="/get/tool/delete/<?php echo $tool->guid?>" rel="facebox" id="2" class="jade_delete_tool">delete!</a></td>
			</tr>
		
		<?php		
		++$start;
		$once = true;
	}
?>
		</table>

</div>		
		<div class="clearboth" style="height:30px"></div>