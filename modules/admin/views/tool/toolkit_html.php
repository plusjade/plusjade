
<?php extract($data_array)?>

<span id="toolkit_<?php echo $guid?>">	
	<table><tr>
		<td class="name_wrapper">
			<span class="name"><?php echo ucwords($name)?></span>					
		</td>
		<td class="actions_wrapper">
			<a href="#" class="actions_link"><span class="icon cog">&#160; &#160; </span> Edit</a>					
			<ul class="toolkit_dropdown">
				<?php echo View::factory("edit_$name/toolbar", array( 'identifer' => $tool_id ) )?>
				<li><span class="icon css">&#160; &#160; </span> <a href="/get/tool/css/<?php echo "$name_id/$tool_id"?>" rel="css_styler">Edit CSS</a></li>
				<?php 
				if(FALSE == $protected)
					echo '<li><span class="icon '. $scope .'">&#160; &#160; </span> <a href="/get/tool/scope/'. "$guid/$page_id" .'" rel="facebox">Scope</a></li>';
				
				?>
				<li><span class="icon cross">&#160; &#160; </span> <a href="/get/tool/delete/<?php echo $guid?>" class="js_admin_delete" rel="guid_<?php echo $guid?>">Delete</a></li>	
			</ul>
		</td>
	</tr></table>
</span>