<!-- START admin elements -->		
<div id="admin_bar_wrapper" class="admin_reset">
	<ul id="admin_bar">
	
		<li class="jade">
			<a href="http://<?php echo ROOTDOMAIN ?>/get/auth" class="block_mode" title="Go to your user account at +Jade">+Jade</a>
		</li>

		<li class="dropdown">
			<div><span class="icon global">&#160; &#160; </span> Site</div>
			<ul>
				<li><a href="/get/admin" rel="facebox"><span class="icon wrench">&#160; &#160; </span> Settings</a></li>
				<li><a href="/get/page/navigation" rel="facebox"><span class="icon sitemap">&#160; &#160; </span>Navigation</a></li>
				<li><a href="/get/theme/logo" rel="facebox"><span class="icon asterisk">&#160; &#160; </span>Logo</a></li>				
				<li><a href="/get/auth/logout"><span class="icon cross">&#160; &#160; </span> Logout</a></li>
			</ul>		
		</li>	
		
		<li class="dropdown">
			<div><span class="icon palette">&#160; &#160; </span> Theme</div>
			<ul> 
				<li><a href="/get/theme" rel="facebox"><span class="icon manage">&#160; &#160; </span>Manage</a></li>
				<li><a href="/get/theme/edit/stylesheets" rel="css_styler"><span class="icon css">&#160; &#160; </span>Stylesheets</a></li>
				<li><a href="/get/theme/edit/templates" rel="facebox"><span class="icon template">&#160; &#160; </span>Templates</a></li>
				<li><a href="/get/theme/change" rel="facebox" style="border-top:1px dashed #ccc"><span class="icon palette">&#160; &#160; </span>New Theme</a></li>
			</ul>		
		</li>
		
		<li class="direct">
			<a href="/get/page/index/add" class="block_mode" rel="facebox"><span class="icon page">&#160; &#160;</span> Pages</a>
		</li>
		
		<li class="direct">
			<a href="/get/tool" rel="facebox" class="block_mode"><span class="icon tools">&#160; &#160; </span>Tools</a>
		</li>
		
		<li class="direct">
			<a href="/get/files" rel="facebox" class="block_mode"><span class="icon local">&#160; &#160; </span>Files</a>
		</li>
		
		<li class="this_page">
			<div><b>This Page:</b></div>
		</li>
		
		<li class="this_page" style="width:100px">
			<a href="/get/page/settings/<?php echo $page_id?>" class="block_mode" rel="facebox"><span class="icon wrench">&#160; &#160; </span> Settings</a>
		</li>

		<li class="this_page" style="width:129px">
			<a href="/get/tool/add/<?php echo $page_id?>" class="block_mode" rel="facebox"><span class="icon plus">&#160; &#160; </span> ADD CONTENT</a>
		</li>
		
		
		<li id="hider" class="floatright">
			<a href="#" class="toggle_admin_bar block_mode">Hide Admin</a>
		</li>		
	</ul>
	
	<div style="display:none">
		<span id="global_css_path"><?php echo $global_css_path?></span>
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