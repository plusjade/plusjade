

<div id="common_tool_header">
	<div id="common_title">All Site Tools</div>
</div>

<div id="tools_browser_wrapper">

	<div class="common_left_panel">
		All tools on this site.
		<br><br>
		<h2>Key</h2>
		<b style="color:blue">Blue:</b> on local page.
		<br><b style="color:orange">Orange:</b> on all pages.
		<br><b style="color:red">Red:</b> Orphans.
		<br><br>
		Move orphans to an existing page or delete it to tidy up your site!
		
		<ul id="tool_list_wrapper" style="line-height:1.6em">
			<?php foreach($system_tools as $system_tool):?>
				<li><a href="#window_<?php echo $system_tool->name?>"><?php echo $system_tool->name?></a></li>
			<?php endforeach;?>
		</ul>
	</div>

	<div class="breadcrumb_wrapper" style="width:590px">
		<span id="breadcrumb" rel="">Album</span>
	</div>	
	<div id="directory_window" class="common_main_panel">

			<?php 
			$names = array('');
			$start = 1;
			foreach($tools as $key => $tool)
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
							Guid:<?php echo $tool->id?>
							<br>Tool id: <?php echo $tool->tool_id?>
							<br>Pages: 
							
							<?php foreach($tool->pages as $page) :?>
								<br><a href="<?php echo url::site($page->page_name)?>"><?php echo $page->page_name?></a>
							<?php endforeach;?>
							
							<p>
								<a href="/get/tool/html/<?php echo "{$tool->system_tool->name}/$tool->tool_id"?>" class="show_view">View</a>
								
								<br><br><a href="/get/tool/delete/<?php echo $tool->id?>" class="jade_delete_tool" rel="<?php echo $tool->id?>">delete!</a>
							</p>
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
	
	
</script>