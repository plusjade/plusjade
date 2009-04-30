
<style type="text/css">
	#jade_tool_box{
		width:750px;
		padding:5px;
		background:#eee;
		overflow:auto;
		border:1px solid #ccc;
	}
	.tool_box_wrapper{
		width:170px;
		height:170px;
		border:1px solid lightblue;
		margin:5px;
		float:left;
		background:#fff;
	}
	.tool_box_wrapper div{
		padding:10px;
		line-height:1.5em;
		margin-top:15px;
	}
	#jade_tool_box label{
		display:block;
		padding:10px 0;
		cursor:cursor;
		cursor:pointer;
		background: lightblue url(/assets/images/admin/blue_bg.png) repeat-x bottom left;
		color:#fff;
		font-size:1.4em;
	}
	#jade_tool_box label:hover,
	#jade_tool_box label.selected
	{	
		background: #7ebd40 url(/assets/images/admin/light_green_bg.png) repeat-x bottom left;
	}
</style>


<?php echo form::open('theme/change', array('class' => 'ajaxForm'))?>
	
	<div id="common_tool_header" class="buttons">
		<div id="common_title">Current Theme: <?php echo $this->theme;?></div>
	</div>


	<div id="jade_tool_box">
		<?php	
		foreach($themes as $key => $theme)
		{					
			$disabled = '';
			if($this->theme == $theme->name)
				$disabled = 'disabled="disabled"';
				
			echo '<div class="tool_box_wrapper">';
			echo'
				<button type="submit" name="theme" value="' . $theme->name .'" class="jade_positive" style="width:100%;height:40px" '.$disabled.'>
				<img src="/assets/images/admin/add.png"> ' . $theme->name .'
				</button>
			';
			echo '</div>';
			
			unset($disabled);
		}
		?>
	</div>
</form>
<script type="text/javascript">
	// ADD tool label stuff...
	$('#jade_tool_box button').click(function(){
		$('#jade_tool_box button').removeClass('selected');
		$(this).addClass('selected');
	});
</script>