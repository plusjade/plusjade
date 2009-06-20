<?php

if(ROOTACCOUNT == $this->site_name)
	if( $this->client->logged_in() )
		echo '<a href="/get/auth/logout">Logout</a> <a href="/get/auth">My Dashboard</a>';	 
	else
		echo '<a href="/get/auth">Login</a> <a href="/get/auth/create">Create Website</a>';

# END