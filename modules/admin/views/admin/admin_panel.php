<!-- START admin elements -->		
<div id="admin_bar_wrapper">
	<ul id="admin_bar">				
		<li class="root_list">
			<div class="title_tag">SITEWIDE:</div>
		</li>
		
		<li class="root_list dropdown">
			<div>Theme &#8595;</div>
			<ul> 
				<li><span class="icon rainbow">&nbsp; &nbsp; </span><a href="/get/theme" rel="facebox" >Edit Theme</a></li>
				<li><span class="icon flag">&nbsp; &nbsp; </span><a href="/get/theme/logo" rel="facebox">Edit Logo</a></li>
				<li><span class="icon flag">&nbsp; &nbsp; </span><a href="/get/theme/change" rel="facebox">New Theme</a></li>
			</ul>		
		</li>
		<li class="root_list dropdown">
			<div>Pages &#8595;</div>
			<ul>
				<li><span class="icon page">&nbsp; &nbsp; </span><a href="/get/page" rel="facebox">All Pages</a></li>
				<li><span class="icon sitemap">&nbsp; &nbsp; </span><a href="/get/page/navigation" rel="facebox">Navigation</a></li>
				
			</ul>		
		</li>		
		<li class="root_list dropdown">
			<div>Tools &#8595;</div>
			<ul>
				<li><span class="icon tools">&nbsp; &nbsp; </span> <a href="/get/tool" rel="facebox">All Tools</a></li>
			</ul>
		</li>
		<li class="root_list dropdown">
			<div>Files &#8595;</div>
			<ul>
				<li><span class="icon tools">&nbsp; &nbsp; </span> <a href="/get/files" rel="facebox">Manage</a></li>
			</ul>
		</li>		
		<li class="root_list floatright">
			<div><b><a class="toggle_admin_bar" href="#">Hide Admin</a></b></div>
		</li>
		<li class="root_list floatright">
			<div><a href="/get/auth/logout">Logout</a></div>
		</li>			
		<li class="root_list floatright">
			<div><a href="http://<?php echo ROOTDOMAIN ?>/get/auth">+Jade</a></div>
		</li>
	</ul>
	
	<div id="tool_bar_wrapper">
		<table><tr>
			<td class="title"><b>ON THIS PAGE: <?php echo $page_name?></b></td>
			<td><a href="/get/page/settings/<?php echo $page_id?>" rel="facebox">Page Settings</a></td>
			<td><a href="/get/tool/add/<?php echo $page_id?>" rel="facebox">ADD CONTENT</a></td>
		</tr></table>		


		<div style="display:none">
			<span id="click_hook" rel="<?php echo $page_id?>" style="display:none"></span>
			<?php						
			if( '0' < count($tools_array) )
			{
				/*
				 * THIS IS HIDDEN: Exists so JS can grab html.
				 * $tool_array = guid|tool_name|tool_id
				 * guid			is for pages_tools table
				 * name			defines the tool table (plural) ex: album(s)
				 * name_id		tools_list id of the tool
				 * tool_id		gets the tool from the tool table
				 * scope		local/global
				 */	
				foreach($tools_array as $db_position => $data_array)
				{
					$data_array['page_id'] = $page_id;
					$data_array['protected'] =
						(in_array($data_array['name_id'], $protected_array)) ?
							TRUE : FALSE;					
					echo View::factory('tool/toolkit_html', array('data_array'=> $data_array));
				}
			}
			?>
		</div>		
	</div>
</div>

<div id="hide_link">
	<a class="toggle_admin_bar" href="#">Show</a>
</div>
<!-- END admin elements -->	