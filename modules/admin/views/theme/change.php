<style type="text/css">
	#jade_tool_box{
		width:80%;
		margin:0 auto;
	}
	#jade_tool_box label{
		display:block;
		padding:10px;
		margin:10px;
		cursor:cursor;
		cursor:pointer;
		background:lightblue;
		color:#fff;
		font-size:1.6em;
		width:99%;
	}
	#jade_tool_box label:hover,
	#jade_tool_box label.selected
	{	
		background: #7ebd40 url(/images/admin/light_green_bg.png) repeat-x bottom left;
	}
</style>

<?php echo form::open('theme/change', array('class' => 'ajaxForm'))?>
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="change_theme" class="jade_positive">
			Change Theme
		</button>

		<div id="common_title">Current Theme: <?php echo $theme_name;?></div>
	</div>


	<div id="jade_tool_box">
		<?php	
		foreach($themes as $key => $theme)
		{					
			$selected = '';
			if($theme_name == $theme->name)
				$selected = 'CHECKED';
			echo '<label FOR="radio_'.$key.'">';
			echo '<input type="radio" name="theme" id="radio_'.$key.'" value="' . $theme->name .' " ' . $selected .'> '. $theme->name;							
			echo '</label>';
			
			unset($selected);
		}
		?>
	</div>
</form>