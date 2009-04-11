<?php 
if( empty($page_id) ) $page_id = NULL;
#HACK
$page_name = uri::easy_segment(1);

if (empty($tools_array))
	$tools_array = array();
?>

<!-- START admin elements -->		
<div id="admin_bar_wrapper">
	
	<div id="admin_bar">					

			<ul id="admin_right">						
				<li><a href="http://<?php echo ROOTDOMAIN ?>/auth">+Jade</a></li>
				<li><a href="/admin/logout">Logout</a></li>
				<li><b><a class="toggle_admin_bar" href="#">Hide Admin</a></b></li>
			</ul>
			
			<ul id="admin_left">
				<li><b>My site</b></li>
				<li><a href="/get/page" rel="facebox">All Pages</a></li>
				<li><a href="/get/page/add" rel="facebox">New Page</a></li>
				<li><a href="/get/theme" rel="facebox">Theme</a></li>
				<li><a href="/get/theme/logo" rel="facebox">Logo</a></li>
				<li><a href="/get/tool" rel="facebox">All Tools</a></li>
			</ul>	

		<div class="clearboth"></div>
	</div>
	
	<div id="tool_bar_wrapper">
		<table><tr>
			<td class="title"><b>ON THIS PAGE: <?php echo $page_name?></b></td>
			<td><a href="/get/page/settings/<?php echo $page_id?>" rel="facebox">Page Settings</a></td>
			<td><a href="/get/page/tools/<?php echo $page_id?>" rel="facebox">Move Tools</a></td>
			<td><a href="/get/tool/add/<?php echo $page_id?>" rel="facebox">Add to Page</a></td>
		</tr></table>		

			<ul id="cssdropdown" style="display:none">
					<?php						
					if( count($tools_array) > 0 )
					{
						/*
						 * THIS IS HIDDEN: Only here so JS can grab html.
						 * $tool_array = guid|tool_name|tool_id
						 * Notes:
						 * guid			is for pages_tools table
						 * name			defines the tool table (plural) ex: album(s)
						 * name_id		tools_list id of the tool
						 * tool_id		gets the tool from the tool table
						 */
						foreach($tools_array as $db_position => $data_array)
						{									
							echo '<li id="toolkit_' , $data_array['guid'] , '">';
								echo '<a href="/get/tool/delete/' , $data_array['guid'] , '" class="jade_delete_tool">delete!</a>';
								
								echo '<span class="name">', ucwords($data_array['name']) , '</span>';					
								echo '<ul>';
									echo View::factory($data_array['name'].'/edit/toolbar' , array( 'identifer' => $data_array['tool_id'] ) );
									echo '<li><a href="/get/edit_', $data_array['name']  ,'/css/' , $data_array['tool_id'] , '" rel="facebox">CSS</a></li>';
									
								echo '</ul>';
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