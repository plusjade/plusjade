
<div id="container-1">

	<ul id="vertical_tabs" class="ui-tabs-nav">	
		<li><a href="#fragment-1" class="ui-tabs-selected"><span>Dashboard</span></a></li>
		<li><a href="#fragment-2"><span>Website</span></a></li>
		<li><a href="#fragment-3"><span>Marketing</span></a></li>
		<li><a href="#fragment-4"><span>Analytics</span></a></li>
		<li><a href="#fragment-5"><span>Account</span></a></li>
	</ul>

	<div id="vertical_content">

		<div id="fragment-1">

			<div id="common_tool_header" class="buttons">
		
				<a href="/get/auth/manage" class="jade_positive floatright">Edit Website</a>

				<div id="common_title">Hello there, <?php echo ucwords($user->username)?>!</div>
			</div>
		
		
				Glad you are back, we have work to do: 
				
				<ol>
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
			
		</div>

		<div id="fragment-2">
			hello jello
		</div>
		
		<div id="fragment-3">
			hello jello
		</div>
		<div id="fragment-4">
			hello jello
		</div>
		
		<div id="fragment-5">		
			<a href="/get/auth/change_password">Change Password</a>

			<?php 
			#quick hack - remove later
			if($this->client->logged_in(2))
			{
				?>
				<p><a href="/get/auth/create">Create New Site account</a></p>
				<p><a href="/get/auth/destroy">Destroy Site account</a></p>	
				<p><a href="/get/auth/clean_db">Clean Database</a></p>
				<?php
			}
			?>
		</div>
		
		
	</div>

</div>

<script type="text/javascript">
	$("#container-1").tabs();
</script>