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
						 * GUID			is for pages_tools table
						 * TOOL_NAME	defines the tool table (plural) ex: album(s)
						 * TOOL_ID		gets the tool from the tool table
						 */
						$position = 0;
						foreach($tools_array as $db_position => $name_id)
						{
							$pieces		= explode('|', $name_id);			
							$tool_guid	= $pieces['0'];
							$tool		= strtolower($pieces['1']);			
							$tool_id	= $pieces['2'];
							
							echo '<li id="toolkit_'.++$position.'">';
								echo '<a href="/get/tool/delete/' . $tool_guid . '" class="jade_delete_tool">delete!</a>';
								echo '<span class="name">'. ucwords($tool) .'</span>';					
								echo '<ul>';
									echo View::factory("$tool/edit/toolbar", array('identifer' => $tool_id) );
									
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