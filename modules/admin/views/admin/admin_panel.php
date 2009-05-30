<?php 
if( empty($page_id) ) $page_id = NULL;
#HACK
$page_name = uri::easy_segment('1');

if (empty($tools_array))
	$tools_array = array();
?>

<!-- START admin elements -->		
<div id="admin_bar_wrapper">
	<ul id="admin_bar">				
		<li class="root_list">
			<div class="title_tag">SITEWIDE:</div>
		</li>
		
		<li class="root_list dropdown">
			<div>Theme &#8595;</div>
			<ul> 
				<li><img src="<?php echo url::image_path('admin/rainbow.png')?>" alt=""> <a href="/get/theme" rel="facebox">Edit Theme</a></li>
				<li><img src="<?php echo url::image_path('admin/flag_green.png')?>" alt=""> <a href="/get/theme/logo" rel="facebox">Edit Logo</a></li>
				<li><img src="<?php echo url::image_path('admin/flag_green.png')?>" alt=""> <a href="/get/theme/change" rel="facebox">New Theme</a></li>
			</ul>		
		</li>
						
		<li class="root_list dropdown">
			<div>Pages &#8595;</div>
			<ul>
				<li><img src="<?php echo url::image_path('admin/page.png')?>" alt=""> <a href="/get/page" rel="facebox">All Pages</a></li>
				<li><img src="<?php echo url::image_path('admin/sitemap.png')?>" alt=""> <a href="/get/page/navigation" rel="facebox">Navigation</a></li>
				
			</ul>		
		</li>
		
		<li class="root_list dropdown">
			<div>Tools &#8595;</div>
			<ul>
				<li><img src="<?php echo url::image_path('admin/tools.png')?>" alt=""> <a href="/get/tool" rel="facebox">All Tools</a></li>
			</ul>
		</li>

		<li class="root_list dropdown">
			<div>Files &#8595;</div>
			<ul>
				<li><img src="<?php echo url::image_path('admin/tools.png')?>" alt=""> <a href="/get/tool" rel="facebox">(not live)</a></li>
			</ul>
		</li>
		
		<li class="root_list floatright">
			<div><b><a class="toggle_admin_bar" href="#">Hide Admin</a></b></div>
		</li>

		<li class="root_list floatright">
			<div><a href="/get/admin/logout">Logout</a></div>
		</li>	
		
		<li class="root_list floatright">
			<div><a href="http://<?php echo ROOTDOMAIN ?>/get/auth">+Jade</a></div>
		</li>
	</ul>
	
	<div id="tool_bar_wrapper">
		<table><tr>
			<td class="title"><b>ON THIS PAGE: <?php echo $page_name?></b></td>
			<td><a href="/get/page/settings/<?php echo $page_id?>" rel="facebox">Page Settings</a></td>
			<td><a href="#" id="get_tool_sort" rel="<?php echo $page_id?>">Save The Page</a></td>
			<td><a href="/get/tool/add/<?php echo $page_id?>" rel="facebox">ADD CONTENT</a></td>
		</tr></table>		


		<ul id="cssdropdown" style="display:none">
			<span id="click_hook" rel="<?php echo $page_id?>" style="display:none"></span>
			<?php						
			if( count($tools_array) > 0 )
			{
				/*
				 * THIS IS HIDDEN: Exists so JS can grab html.
				 * $tool_array = guid|tool_name|tool_id
				 * guid			is for pages_tools table
				 * name			defines the tool table (plural) ex: album(s)
				 * name_id		tools_list id of the tool
				 * tool_id		gets the tool from the tool table
				 */
				foreach($tools_array as $db_position => $data_array)
				{									
					echo '<li id="toolkit_' , $data_array['guid'] , '">';	
						echo'<table><tr><td class="name_wrapper">';
						echo '<span class="name">', ucwords($data_array['name']) , '</span>';					
						echo '</td><td class="actions_wrapper">';
						echo '<a href="#" class="actions_link"><img src="'. url::image_path('admin/cog_edit.png') .'" alt=""> Edit</a>';
					
						echo '<ul class="toolkit_dropdown">';
							echo View::factory('edit_'.$data_array['name'].'/toolbar' , array( 'identifer' => $data_array['tool_id'] ) );
							echo '<li><img src="'. url::image_path('admin/css_add.png') .'" alt="CSS"> <a href="/get/css/edit/' , $data_array['name_id'] , '/' , $data_array['tool_id'] , '" rel="facebox">Edit CSS</a></li>';
							echo '<li><img src="'. url::image_path('admin/delete.png') .'" alt="delete!"> <a href="/get/tool/delete/' , $data_array['guid'] , '" class="js_admin_delete" rel="guid_',$data_array['guid'],'">Delete</a></li>';	
						echo '</ul>';
						echo '</td></tr></table>';
					echo '</li>';
				}
			}
			?>
		</ul>		
	</div>
</div>

<div id="hide_link">
	<a class="toggle_admin_bar" href="#">Show</a>
</div>
<!-- END admin elements -->	