
<div id="common_tool_header" class="buttons">
	Showing drafts
</div>

<ul id="generic_sortable_list" class="ui-tabs-nav" style="min-height:320px">
	<?php
	if(! empty($items) )
	{
		foreach($items as $item)
		{
			$class='';
			?>
			<li id="faq_<?php echo $item->id?>" <?php echo $class?>>
				<table id="menu_page_list"><tr>
					<td class="page_edit"><a href="/get/edit_blog/edit/<?php echo $item->id?>" rel="facebox" id="2"><?php echo $item->title?></a></td>
					<td width="150px"><?php echo $item->created_on?></td>
					<td class="alignright" width="50px"><a href="/get/edit_blog/delete/<?php echo $item->id?>" class="delete_page" id="<?php echo $item->id?>">delete</a></td>
				</tr></table>
			</li>		
			<?php
		}
	}
	else
		echo '<li>No Drafts</li>';
	?>
</ul>
