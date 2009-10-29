

<div id="common_tool_header">
	<div style="float:right;padding-top:10px;">
		Type: <select id="tool_list_wrapper">
			<?php foreach($system_tools as $system_tool):?>
				<option><?php echo $system_tool->name?></option>
			<?php endforeach;?>
		</select>
	</div>	
	<div id="common_title">All Site Tools</div>
</div>


<div id="tools_browser_wrapper">
	<div id="directory_window" class="common_full_panel">

			<?php 
			$names = array('');
			$start = 1;
			foreach($tools as $tool)
			{
				$names[$start] = $tool->system_tool->name;
				$last = $start-1;					
				$class = 'no_pages';
				if(0 != $tool->pages_tools->count())
					$class = (10 < $tool->pages_tools->current()->page_id)
						? 'has_pages'
						: 'global';

				if($tool->system_tool->name != $names[$last]):
					if(isset($once)) echo '</table></div>';
				?>
					<div id="window_<?php echo $tool->system_tool->name?>" class="window_tool">
					<table>
						<tr><th>Id</th> <th>Name</th> <th>Quick View</th> <th>Add To Page</th> <th>Delete</th> <th>Found Where?</th></tr>
				<?php endif;?>
						<tr id="icon_<?php echo $tool->id?>" class="<?php echo $class?>">
							<td><?php echo $tool->id?></td>
							<td width="200px" class="aligncenter"><input type="text" value="<?php echo $tool->name?>"> <span class="icon save" rel="<?php echo $tool->id?>" title="save name">&#160; &#160; </span></td>
							<td width="90px" class="aligncenter"><span class="icon magnify">&#160; &#160;</span> <a href="/get/tool/html/<?php echo "{$tool->system_tool->name}/$tool->parent_id"?>" class="show_view">View</a></td>
							<td width="90px" class="aligncenter"><span class="icon plus">&#160; &#160;</span> <a href="/get/tool/add?tool_id=<?php echo "$tool->id&page_id=$page_id"?>" class="to_page" rel="<?php echo "{$tool->system_tool->name}:$tool->parent_id:$tool->id"; ?>">Add</a></td>
							<td width="90px" class="aligncenter"><span class="icon cross">&#160; &#160;</span> <a href="/get/tool/delete/<?php echo $tool->id?>" class="jade_delete_tool" rel="<?php echo $tool->id?>">delete</a></td>
							<td class="alignright">
								<?php if('has_pages' == $class):?>
									(<?php echo $tool->pages->count()?>) Pages: 
									<select>
										<?php foreach($tool->pages as $page):?>
											<option><?php echo $page->page_name?></option>
										<?php endforeach;?>
									</select>
								<?php elseif('global' == $class):?>
									globalized
								<?php else:?>
									nowhere =(
								<?php endif;?>
							</td>
						</tr>
				<?php		
				++$start;
				$once = true;
			}
			unset($names);
			?>
			<br class="clearboth" /></div>
	</div>
	
	<span class="save_pane" style="width:760px;margin-left:20px; display:none">
		<div class="contents" style="height:280px;">
			<span class="icon cross floatright">&#160; &#160;</span>
			<h2 class="aligncenter">*For previewing purposes only*</h2>
			<div class="output_tool_html"></div>
		</div>
	</span>
	
</div>


<script type="text/javascript">
	// show the tool types
	$('#tool_list_wrapper').change(function(){		
		var pane = $("#tool_list_wrapper option:selected").val();
		$('div.window_tool').hide(); 
		$('#window_' + pane).show();
		return false;
	});
	$('#tool_list_wrapper option:first').change();	
</script>