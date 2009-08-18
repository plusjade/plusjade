
<?php extract($data_array)?>

<span id="toolkit_<?php echo $guid?>">	
	
	<div class="bar">
			
		<div class="actions_wrapper">
			<a href="#" class="actions_link"><span class="icon cog">&#160; &#160; </span> Edit</a>					
			<div class="toolkit_wrapper">
				<ul class="toolkit_dropdown">
					<?php echo View::factory("edit_$name/toolbar", array('identifer' => $tool_id))?>
					<li><span class="icon css">&#160; &#160; </span> <a href="/get/tool/css/<?php echo "$name_id/$tool_id"?>" rel="css_styler">Edit CSS</a></li>
					<?php if(FALSE == $protected):?>
						<li><span class="icon <?php echo $scope?> ">&#160; &#160; </span> <a href="/get/tool/scope/<?php echo "$guid/$page_id"?>" rel="facebox">Scope</a></li>
					<?php endif;?>
					<?php if('account' != $name):?>
						<li><span class="icon cross">&#160; &#160; </span> <a href="/get/tool/delete/<?php echo $guid?>" class="js_admin_delete" rel="guid_<?php echo $guid?>">Delete</a></li>	
					<?php endif;?>
					<li><span class="icon help">&#160; &#160; </span> <a href="/get/help?page=<?php echo $name?>" rel="facebox">Help</a></li>		
				</ul>
			</div>
		</div>

		<div class="name_wrapper">
			<span class="icon move">&#160; &#160; </span> <?php echo ucwords($name)?>				
		</div>
		
	</div>
</span>