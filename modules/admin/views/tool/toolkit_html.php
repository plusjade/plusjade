
<?php extract($data_array)?>

<span id="toolkit_<?php echo $instance?>">	
	
	<div class="bar">
			
		<div class="actions_wrapper">
			<a href="#" class="actions_link"><span class="icon cog">&#160; &#160; </span> Edit</a>					
			<div class="toolkit_wrapper">
				<ul class="toolkit_dropdown">
					<?php echo View::factory("edit_$name/toolbar", array('identifer' => $parent_id))?>
					<li><span class="icon css">&#160; &#160; </span> <a href="/get/tool/css/<?php echo "$name_id/$parent_id"?>" rel="css_styler">Edit CSS</a></li>
					<?php if(!$protected):?>
						<li><span class="icon <?php echo $scope?> ">&#160; &#160; </span> <a href="/get/tool/scope/<?php echo "$page_id/$instance"?>" rel="facebox">Scope</a></li>
					<?php endif;?>
					<li><span class="icon asterisk">&#160; &#160; </span> <a href="/get/tool/remove?instance_id=<?php echo "$instance"?>" class="jade_tool_remove" rel="instance_<?php echo $instance?>" title="Removes tool from this page but does not delete the tool.">Remove</a></li>
					<?php if('account' != $name):?>
						<li><span class="icon cross">&#160; &#160; </span> <a href="/get/tool/delete/<?php echo $tool_id?>" class="js_admin_delete" rel="tool_<?php echo $tool_id?>" title="Delete this tool entirely from all pages.">Delete</a></li>	
					<?php endif;?>
					<li><span class="icon help">&#160; &#160; </span> <a href="/get/help?page=<?php echo $name?>" rel="facebox">Help</a></li>		
					<li><span class="icon heart">&#160; &#160; </span> <a href="/get/export/tool?tool_id=<?php echo $tool_id?>" rel="facebox">Export</a></li>	
				</ul>
			</div>
		</div>

		<div class="name_wrapper">
			<span class="icon move">&#160; &#160; </span> <?php echo ucfirst($name)?>				
		</div>
		
	</div>
</span>