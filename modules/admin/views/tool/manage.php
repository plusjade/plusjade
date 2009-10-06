

<div id="common_tool_header">
	<div id="common_title">All Site Tools <?php echo $page_id?></div>
</div>

<div id="tools_browser_wrapper">

	<div class="common_left_panel">
		<h2>Key</h2>
		<b style="color:blue">Blue:</b> on local page.
		<br><b style="color:orange">Orange:</b> on all pages.
		<br><b style="color:red">Red:</b> Orphans.
		
		<ul id="tool_list_wrapper" style="line-height:1.1em">
			<?php foreach($system_tools as $system_tool):?>
				<li><a href="#window_<?php echo $system_tool->name?>"><?php echo $system_tool->name?></a></li>
			<?php endforeach;?>
		</ul>
	</div>

	<div class="breadcrumb_wrapper" style="width:590px">
		<span id="breadcrumb" rel="">Album</span>
	</div>	
	<div id="directory_window" style="overflow:visible" class="common_main_panel">

			<?php 
			$names = array('');
			$start = 1;
			foreach($tools as $tool)
			{
				$names[$start] = $tool->system_tool->name;
				$last = $start-1;
				$class = (0 < $tool->pages->count())
					? 'safe'
					: 'orphan';
				
				if($tool->system_tool->name != $names[$last]):
				
					if(isset($once)) echo '<br class="clearboth" /></div>';
				?>
					<div id="window_<?php echo $tool->system_tool->name?>" class="window_tool">
						<div class="window_header">
							<?php echo $tool->system_tool->name?>
						</div>
				<?php endif;?>
						<div id="icon_<?php echo $tool->id?>" class="asset <?php echo $class?>">
							Pages: 
							<?php foreach($tool->pages as $page) :?>
								<br><a href="<?php echo url::site($page->page_name)?>"><?php echo $page->page_name?></a>
							<?php endforeach;?>
							
							<br><br>
							<a href="/get/tool/add?tool_id=<?php echo "$tool->id&page_id=$page_id"?>" class="to_page" rel="<?php echo "{$tool->system_tool->name}:$tool->parent_id:$tool->id"; ?>">Add to Page</a>
							<br>
							<a href="/get/tool/html/<?php echo "{$tool->system_tool->name}/$tool->parent_id"?>" class="show_view">quick view</a>
								
							<br>
							<a href="/get/tool/delete/<?php echo $tool->id?>" class="jade_delete_tool" rel="<?php echo $tool->id?>">delete!</a>
							
						</div>
				<?php		
				++$start;
				$once = true;
			}
			unset($names);
			?>
			<br class="clearboth" /></div>
	</div>
	
	<span class="save_pane" style="width:760px;margin-left:20px; display:none">
		<div class="contents" style="height:400px; ">
			<span class="icon cross floatright">&#160; &#160;</span>
			<h2 class="aligncenter">*For previewing purposes only*</h2>
			<div id="output_tool_html"></div>
		</div>
	</span>
	
</div>


<script type="text/javascript">
	
	$('#tool_list_wrapper a').click(function(){
		$('#tool_list_wrapper a').removeClass('active');
		var pane = $(this).addClass('active').attr('href');	
		$('div.window_tool').hide(); 
		$(pane).show();
		return false;
	});
	$('#tool_list_wrapper a:first').click();
	
	
	$('.jade_delete_tool').click(function(){
		if(confirm('This cannot be undone. Delete this tool?')) {
			var id = $(this).attr('rel');
			$.get($(this).attr('href'), function(data){
				$('#icon_'+ id).remove();
				$(document).trigger('server_response.plusjade', data);
			});
		}
		return false;
	});
	
	$('#tools_browser_wrapper a.show_view').click(function(){
		
		$('.save_pane')
			.clone()
			.prependTo('#tools_browser_wrapper')
			.addClass('helper')
			.show();

		var url = $(this).attr('href');
		$('.save_pane.helper #output_tool_html')
			.html('<div class="plusjade_ajax">Loading...</div>')
			.load(url);
		
		return false;
	});
	
	// add tool to the current page:
		$('a.to_page').click(function(){
			var args = $(this).attr('rel').split(':');
			var tool = {
				"toolname" : args[0],
				"parent_id" : args[1],
				"tool_id" : args[2],
			};		
			$.get(this.href, function(data){
				// expecting an insert_id from pages_tools table
				// if the result data is NOT numeric, display the error message
				/*
				if(!is_numeric(data)) {
					alert(data);
					return false;				
				}
				*/
				tool.instance = data;
				$().jade_inject_tool('add', tool);
			});
			return false;
		});
	
</script>