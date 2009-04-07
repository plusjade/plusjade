
<?php  
/*
 * this is a stock ajax view to be loaded in facebox
 */
 
?>

<div id="ajax_primary">
	<?php if(!empty($primary)) echo $primary?>
</div>

<script type="text/javascript"> 
  //<![CDATA[
		<?php if(!empty($rootJS)) echo $rootJS; ?>  
  //]]>
</script>
