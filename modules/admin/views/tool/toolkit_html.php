
<?php extract($data_array)?>

<span id="toolkit_<?php echo $guid?>">	
	<table><tr>
		<td class="name_wrapper">
			<span class="name"><?php echo ucwords($name)?></span>					
		</td>
		<td class="actions_wrapper">
			<a href="#" class="actions_link"><img src="<?php echo url::image_path('admin/cog_edit.png')?>" alt=""> Edit</a>					
			<ul class="toolkit_dropdown">
				<?php echo View::factory("edit_$name/toolbar", array( 'identifer' => $tool_id ) )?>
				<li><img src="<?php echo url::image_path('admin/css_add.png')?>" alt="CSS"> <a href="/get/css/edit/<?php echo "$name_id/$tool_id"?>" rel="facebox">Edit CSS</a></li>
				<li><img src="<?php echo url::image_path('admin/delete.png')?>" alt="delete!"> <a href="/get/tool/delete/<?php echo $guid?>" class="js_admin_delete" rel="guid_<?php echo $guid?>">Delete</a></li>	
			</ul>
		</td>
	</tr></table>
</span>