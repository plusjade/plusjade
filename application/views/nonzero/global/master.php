
<div id="header_wrapper">

	<div id="header_inner" class="fixed">
		<?php echo View::factory("_global/header")?>			

		<div id="menu">
			<?php echo View::factory('_global/menu')?>
		</div>
		
	</div>
</div>

<div id="main">

	<div id="main_inner" class="fixed">

		<div id="primaryContent_2columns">
			<div id="columnA_2columns">
				<?php if( isset($primary) ) echo $primary ?>	
				<br class="clear" />
				<div class="post">
				</div>	
			</div>
	
		</div>
		
		<div id="secondaryContent_2columns">
		
			<div id="columnC_2columns">

				<h4><span>Cool</span> Stuff</h4>
				<ul class="links">
				<li class="first"><a href="http://www.nodethirtythree.com/">NodeThirtyThree</a></li>
				<li><a href="http://www.4templates.com/?aff=n33">4Templates.com</a></li>
				</ul>

			</div>

		</div>

		<br class="clear" />

	</div>

</div>

<div id="footer_wrapper" class="fixed">
	<?php echo View::factory("_global/footer")?>	
</div>


