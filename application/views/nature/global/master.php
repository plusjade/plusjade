<div id="header">
	<div id="logo">
		<?php  echo View::factory("_global/header")?>
	</div>
	
	<div id="menu">	
		<?php echo View::factory("_global/menu")?>
	</div>
</div>

<!-- start page -->
<div id="page">

	
	<!-- start sidebar -->
	<div id="sidebar" style="display:none;">
		<ul>
			<li>
				<h2>Categories</h2>
				<ul>
					<li><a href="#">Aliquam</a></li>
					<li><a href="#">Consectetuer </a></li>
				</ul>
			</li>
			<li>
				<h2>Archives</h2>
				<ul>
					<li><a href="#">September</a> (23)</li>
					<li><a href="#">August</a> (31)</li>
				</ul>
			</li>
		</ul>
	</div>
	<!-- end sidebar -->
	

	<div id="content">
	
		<?php if( isset($primary) ) echo $primary ?>
		
	</div>
	

	
</div>

<div style="clear: both; height: 30px">&nbsp;</div>
<!-- end page -->


<div id="footer">
	<p>&copy;2007 All Rights Reserved. &nbsp;&bull;&nbsp; Designed by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a></p>
	<?php echo View::factory("_global/footer")?>
</div>


