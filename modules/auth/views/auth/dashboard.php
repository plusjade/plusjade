


<ul id="admin_generic_tab_nav" class="ui-tabs-nav">	
	<li><a href="#fragment-1" class="ui-tabs-selected"><span>Dashboard</span></a></li>
	<li><a href="#fragment-2"><span>Create & Manage</span></a></li>
	<li><a href="#fragment-3"><span>Market</span></a></li>
	<li><a href="#fragment-3"><span>Analyze</span></a></li>
	<li><a href="#fragment-3"><span>Grow</span></a></li>
</ul>
	

	
<div id="common_tool_header" class="buttons">
	<a href="/auth/logout" class="jade_negative">Logout</a>	
	<div id="common_title">Hello there, <?php echo ucwords($user->username)?>!</div>
</div>



	
<div class="indent">

Create your website in 3 easy steps:
	<ol>
		<li><b>Choose a theme</b>
			<br>You can always change your theme later, or even pay a profesional to custom design
			your website for you. But to get started dead-fast, pick a theme to hold the content you will create.
		</li>
		<li><b>Gather content information and images.</b>
			<br>
		</li>
		<li>
			<b>Upload Content</b>
			<br>Your website automatically comes with 6 core pages. Simply follow the directions to upload your content.
		</li>
		
	</ol>

<div id="edit_website_link">	
	<div class="buttons">
		<a href="/auth/manage" class="jade_positive">Edit Website</a>
	</div>
</div>		



</div>






<?php 
#quick hack - remove later
if($this->client->logged_in(2))
{
	?>
	<p><a href="/auth/create">Create New Site account</a></p>

	<p><a href="/auth/destroy">Destroy Site account</a></p>
	
	<p><a href="/auth/clean_db">Clean Database</a></p>
	<?php
}
