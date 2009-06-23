
<?php echo form::open("theme/edit/$file_name", array('class'=> 'ajaxForm') )?>	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit <?php echo $file_name?></div>
	</div>	
	
	<div class="common_left_panel">
	
	</div>
	<div class="common_main_panel">
		<textarea name="contents" class="render_css"><?php echo $file_contents?></textarea>
	</div>
</form>


<script language="javascript">
	$(document).ready(function(){		 
	});	
</script>