

<div id="vertical_content">

	<div id="common_tool_header" class="buttons">
		<div id="common_title">Hello there, <?php echo ucfirst($user->username)?>!</div>
		
		Glad you are back, we have work to do: 
	</div>

	Sites you own:
	<div id="site_button_wrapper">
		<?php
		foreach($sites_array as $name => $site_id)
		{
			?>
			<p>
				<b><?php echo $name?></b> &#8594; <a href="/get/auth/manage?site=<?php echo $site_id?>">Edit Website</a>  -- 
				<a href="/get/auth/safe_mode/<?php echo $name?>">Activate safe-mode</a>
			</p>
			<?php
		}
		#quick hack - remove later
		if($this->client->logged_in(2))
			echo '<p><b>Admin</b> &#8594; <a href="/get/utada">Go to Master</a></p>';
		?>	
	</div>

	<ol style="display:none">
		<li>
			<span class="main_header">Website - Create essential pages.</span>
		</li>
		
		<li>
			<span class="main_header">Marketing - Establish Lines of Communication</span>
			<ol>
				<li>
					<b>Your email Newsletter Campaign</b>

				</li>
				
				<li>
					<b>Your consistently updated blog.</b>
					<br>
				</li>
				
				<li>
					<b>Your Twitter Feed.</b>
					
				</li>
				
			</ol>
		
		</li>
		
		
		<li>
			<span class="main_header">Analytics - Track Your Progress</span>
			<br>Your website automatically comes with 6 core pages. Simply follow the directions to upload your content.
		</li>
		
	</ol>
		

	<h3>Add New Website</h3>
	
	<form action="/get/auth/new_website" method="POST">
		<input type="text" name="site_name" style="width:300px">
		<br>
		<br>
		<button type="submit">Create New Website</button>
	</form>

</div>


<script type="text/javascript">

</script>