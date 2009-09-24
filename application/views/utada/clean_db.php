
<style type="text/css">
#results_wrapper{
	height:400px;
	overflow:auto;
}
.table_box{
	float:left;
	width:300px;
	border:1px solid #777;
	margin:10px;
}
.table_name{
	padding:5px;
	color:#fff;
	font-weight:bold;
	text-align:center;
}
.results{
	padding:10px;
}
.dirty{
	background:#cc3333;
}
.clean{
	background:#7ebd40;
}
</style>

Running <b><?php echo $site_count?></b> sites!<br><br>
All site_ids: <?php echo $id_string?>

<div id="results_wrapper">
	<?php
		foreach($results as $table => $result)
		{
			$class = 'dirty';
			if('clean' == $result) $class = 'clean';
			?>
			<div class="table_box">
				<div class="table_name <?php echo $class?>"><?php echo $table?></div>
				<div class="results">
					<?php echo $result?>
				</div>
			</div>
			<?php
		}
	?>
</div>
<div class="clearboth"></div>
<p>
	Cleaning Done
	<br><a href="/auth/clean_db">Reload</a> to confirm
</p>
		