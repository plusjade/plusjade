<div id="header">
	<div id="logo">
		<h1><a href="#">SnowFlakes</a></h1>
		<h2><a href="http://www.freecsstemplates.org/">By Free CSS Templates</a></h2>
	</div>
	
	<div id="menu">
		<?php echo View::factory("_global/menu")?>
	</div>
	
</div>
<div id="snowflakes_content">

	<div id="posts">
		<div class="post">
		
			<?php if( isset($primary) ) echo $primary ?>
			
		<!--
			<h2 class="title">Welcome to The Green House!</h2>
			<h3 class="date">Posted on February 25, 2007 by John Doe</h3>
			<div class="story">
				<p><strong>The Green House</strong> is a free template from <a href="http://www.freecsstemplates.org/">Free CSS Templates</a> released under a <a href="http://creativecommons.org/licenses/by/2.5/">Creative Commons Attribution 2.5 License</a>. The  photo is from <a href="http://www.pdphoto.org/">PDPhoto.org</a>. You"re free to use it for both commercial or personal use. I only ask that you link back to my site in some way. <em><strong>Enjoy :)</strong></em></p>
			</div>
			<div class="meta">
				<p><span>Filed under </span><a href="#" class="category">Uncategorized</a><span> | </span><a href="#" class="comments">28 Comments</a></p>
			</div>
		-->	
		
		</div>
		
		
		<!--
		<div class="post">
			<h2 class="title">A Few Examples of Common Tags</h2>
			<h3 class="date">Posted on February 22, 2007 by Jane Smith</h3>
			<div class="story">
				<p><strong></strong>This is an example of a paragraph followed by a blockquote. In posuere eleifend odio. Quisque semper augue mattis wisi. Maecenas ligula. Pellentesque viverra vulputate enim. Aliquam erat volutpat lorem ipsum dolorem.</p>
				<blockquote>
					<p>Pellentesque tristique ante ut risus. Quisque dictum. Integer nisl risus, sagittis convallis, rutrum id, elementum congue, nibh. Suspendisse dictum porta lectus. Donec placerat odio</p>
				</blockquote>
				<h3>Heading Level Three</h3>
				<p>An unordered list example:</p>
				<ul>
					<li>List item number one</li>
					<li>List item number two</li>
					<li>List item number three </li>
				</ul>
				<p>An ordered list example:</p>
				<ol>
					<li>List item number one</li>
					<li>List item number two</li>
					<li>List item number three</li>
				</ol>
			</div>
			<div class="meta">
				<p><span>Filed under </span><a href="#" class="category">Uncategorized</a><span> | </span><a href="#" class="comments">28 Comments</a></p>
			</div>
		</div>
		-->
		
		
	</div>
	
	<div id="bar">
		<div id="archives" class="boxed1">
			<h2>Archives</h2>
			<ul>
				<li><a href="#">February 2007</a> <i>(25)</i></li>
				<li class="active"><a href="#">January 2007</a> <i>(31)</i></li>
				<li><a href="#">December 2006</a> <i>(31)</i></li>
				<li><a href="#">November 2006</a> <i>(30)</i></li>
				<li><a href="#">October 2006</a> <i>(31)</i></li>
			</ul>
		</div>
		<div id="categories" class="boxed2">
			<h2>Categories</h2>
			<ul>
				<li><a href="#">Donec Dictum Metus</a></li>
				<li><a href="#">Etiam Rhoncus Volutpat</a></li>
				<li><a href="#">Integer Gravida Nibh</a></li>
				<li><a href="#">Maecenas Luctus Lectus</a></li>
				<li><a href="#">Mauris Vulputate Dolor Nibh</a></li>
				<li class="active"><a href="#">Nulla Luctus Eleifend</a></li>
				<li><a href="#">Posuere Augue Sit Nisl</a></li>
			</ul>
		</div>
	
	
	</div>
</div>
<div id="footer">
	<p id="copy">&copy;2007 Snow Flakes. Designed by <a href="#">Free CSS Templates</a></p>
	<p id="feed"><a href="#" id="rss">RSS Feed</a></p>
</div>



<?php
/*

<div id="header_wrapper">
	<?php  View::factory("_global/header")->render(true);?>
	<div class="clearboth"></div>
</div> 


<div id="footer_wrapper">
	<?php View::factory("_global/footer")->render(true);	?>
</div>

*/
