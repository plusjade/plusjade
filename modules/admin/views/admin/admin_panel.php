<!-- START admin elements -->		
<div id="admin_bar_wrapper" class="admin_reset">
	<ul id="admin_bar">
	
		<li class="jade">
			<a href="http://<?php echo ROOTDOMAIN ?>/get/auth" class="block_mode" title="Go to your user account at +Jade">+Jade</a>
		</li>

		<li class="dropdown">
			<div><span class="icon global">&nbsp; &nbsp; </span> Site</div>
			<ul>
				<li><a href="/get/admin" rel="facebox"><span class="icon wrench">&nbsp; &nbsp; </span> Settings</a></li>
				<li><a href="/get/auth/logout"><span class="icon cross">&nbsp; &nbsp; </span> Logout</a></li>
			</ul>		
		</li>	
		
		<li class="dropdown">
			<div><span class="icon rainbow">&nbsp; &nbsp; </span> Theme</div>
			<ul> 
				<li>This Theme &#8594;</li>
				<li><a href="/get/theme/templates" rel="facebox"><span class="icon rainbow">&nbsp; &nbsp; </span>Templates</a></li>
				<li><a href="/get/theme/stylesheets" rel="css_styler"><span class="icon rainbow">&nbsp; &nbsp; </span>Stylesheets</a></li>
				<li><a href="/get/theme/logo" rel="facebox"><span class="icon flag">&nbsp; &nbsp; </span>Edit Logo</a></li>
				<li>All Themes &#8594;</li>
				<li><a href="/get/theme/manage" rel="facebox"><span class="icon flag">&nbsp; &nbsp; </span>Your Themes</a></li>
				<li><a href="/get/theme/change" rel="facebox"><span class="icon flag">&nbsp; &nbsp; </span>New Theme</a></li>
			</ul>		
		</li>
		<li class="dropdown">
			<div><span class="icon page">&nbsp; &nbsp;</span> Pages</div>
			<ul>
				<li><a href="/get/page/index/add" rel="facebox"><span class="icon add_page">&nbsp; &nbsp; </span>New Page</a></li>
				<li><a href="/get/page" rel="facebox"><span class="icon page">&nbsp; &nbsp; </span>All Pages</a></li>
				<li><a href="/get/page/navigation" rel="facebox"><span class="icon sitemap">&nbsp; &nbsp; </span>Navigation</a></li>
			</ul>		
		</li>		
		<li class="dropdown">
			<div><span class="icon tools">&nbsp; &nbsp; </span> Tools</div>
			<ul>
				<li><a href="/get/tool" rel="facebox"><span class="icon tools">&nbsp; &nbsp; </span>Manage</a></li>
			</ul>
		</li>
		<li class="dropdown">
			<div><span class="icon local">&nbsp; &nbsp; </span> Files</div>
			<ul>
				<li><a href="/get/files" rel="facebox"><span class="icon tools">&nbsp; &nbsp; </span>Manage</a></li>
			</ul>
		</li>
		
		<li class="this_page">
			<div><b>This Page:</b></div>
		</li>
		
		<li class="this_page" style="width:100px">
			<a href="/get/page/settings/<?php echo $page_id?>" class="block_mode" rel="facebox"><span class="icon wrench">&nbsp; &nbsp; </span> Settings</a>
		</li>

		<li class="this_page" style="width:129px">
			<a href="/get/tool/add/<?php echo $page_id?>" class="block_mode" rel="facebox"><span class="icon plus">&nbsp; &nbsp; </span> ADD CONTENT</a>
		</li>
		
		
		<li id="hider" class="floatright">
			<a href="#" class="toggle_admin_bar block_mode">Hide Admin</a>
		</li>		
	</ul>
	
	<div style="display:none">
		<span id="click_hook" rel="<?php echo $page_id?>" style="display:none"></span>
		<?php						
		if( '0' < count($tools_array) )
		{
			/*
			 * THIS IS HIDDEN: Exists so JS can grab html.
			 * $tool_array = array(guid, name, name_id, tool_id, scope);
			 */	
			foreach($tools_array as $guid => $data_array)
			{
				$data_array['page_id']		= $page_id;
				$data_array['protected']	=
					(in_array($data_array['name_id'], $protected_array))
					? TRUE : FALSE;					
				
				echo View::factory('tool/toolkit_html', array('data_array'=> $data_array));
			}
		}
		?>
	</div>
</div>
<div id="shadow"><div></div></div>

<div id="hide_link">
	<a class="toggle_admin_bar" href="#">Show</a>
</div>
<!-- END admin elements -->	