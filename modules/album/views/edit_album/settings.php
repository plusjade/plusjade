<?php
$views = array( 'cycle', 'galleria', 'lightbox' );
$cycle_params = array( 
		    'blindX',
		    'blindY',

		    'blindZ',
		    'cover',
		    'curtainX',
		    'curtainY',
		    'fade',
		    'fadeZoom',

		    'growX',
		    'growY',
		    'scrollUp',
		    'scrollDown',
		    'scrollLeft',
		    'scrollRight',

		    'scrollHorz',
		    'scrollVert',
		    'shuffle',
		    'slideX',
		    'slideY',
		    'toss',

		    'turnUp',
		    'turnDown',
		    'turnLeft',
		    'turnRight',
		    'uncover',
		    'wipe',

		    'zoom',
);

/*
what effect,  (include FX plus easing and speed)
show title
next/prev buttons,
pause on hover
*/
echo form::open("edit_album/settings/$tool_id", array('id' => "$tool_id", 'class' => 'ajaxForm', 'rel' => $js_rel_command));
?>			
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_album" class="jade_positive">Save Settings</button>
		<div id="common_title">Edit Settings</div>
	</div>	
	
	<div class="fieldsets" style="display:none">
		<b>ALbum Name</b><br>
		<input type="text" name="name" value="<?php echo $album->name?>">
	</div>

	
	<div class="fieldsets">
		<b>View Style</b><br>
		<select id="" name="view">
			<?php 
				foreach($views as $view)
				{
					if($view == $album->view)
						echo '<option SELECTED>'.$view.'</option>';
					else
						echo '<option>'.$view.'</option>';
				}
			?>
		</select>
	</div>

	
	<div class="fieldsets">
		<b>Transition Effect</b><br>
		<select id="effects_list" name="params">
			<?php 
				foreach($cycle_params as $option)
				{
					if($option == $album->params)
						echo '<option value="'.$option.'" SELECTED>'.$option.'</option>';
					else
						echo '<option value="'.$option.'">'.$option.'</option>';
				}
			?>
		</select>
	</div>

	<div id="show"></div>
	<div id="caption" style="text-align:center"></div>
	
	<input type="checkbox" name="title"> Yes, Show Title!
	<br><br>
	<input type="checkbox" name="title"> Yes, Show Prev/Next Links
	<br><br>
	<input type="checkbox" name="title"> Yes, pause slideshow when pointer is over image.


</form>

<script type="text/javascript">
$(function() {	
	var fx;
	$('#effects_list').change(function() {
		fx = $('option:selected', this).val();
		start();
	});
	
	var markup = '<div id="slideshow">'
		+ '<img src="/assets/images/admin/smiley.jpg"><img src="/assets/images/admin/sample2.jpg">'
		+ '</div>';
		
	function start() {
		$('#slideshow').cycle('stop').remove();
        $('#show').append(markup);
		$('#effect').html(fx);
		$('#slideshow').cycle({
			fx: fx,
			timeout: 2000,
			delay:  -1000,
			sync: 1
		});
	}

});

</script>
