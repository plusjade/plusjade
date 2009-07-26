
<div id="common_tool_header">
	<div id="common_title">All Site Tools</div>
</div>

<div class="common_left_panel">
	All tools on this site.
	<br><br>
	<h2>Key</h2>
	<b style="color:blue">Blue:</b> on local page.
	<br><b style="color:orange">Orange:</b> on all pages.
	<br><b style="color:red">Red:</b> Orphans.
	<br><br>
	Move orphans to an existing page or delete it to tidy up your site!
</div>

<div id="all_tools_wrapper" class="common_main_panel">

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
				<tr id="row_<?php echo $tool->guid?>" class="row_wrapper <?php echo $class?>">
					<td class="row_guid">Guid:<?php echo $tool->guid?></td>
					<td class="row_page"><a href="<?php echo url::site($page_name)?>"><?php echo $page_name?></a></td>
					<td class="row_delete"><a href="/get/tool/delete/<?php echo $tool->guid?>" class="jade_delete_tool" rel="<?php echo $tool->guid?>">delete!</a></td>
				</tr>
			
			<?php		
			++$start;
			$once = true;
		}
		?>
	</table>

</div>

<script type="text/javascript">
	$('.jade_delete_tool').click(function(){
		if(confirm('This cannot be undone. Delete this tool?')) {
			var guid = $(this).attr('rel');
			$.get($(this).attr('href'), function(data){
				alert('tr#row_'+ guid);
				$('tr#row_'+ guid).remove();
				$('#show_response_beta').html(data);
			});
		}
		return false;
	});
</script>