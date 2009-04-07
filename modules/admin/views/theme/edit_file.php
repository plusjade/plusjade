

<?php echo form::open("theme/edit/$file_name", array('class'=> 'ajaxForm') )?>	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Changes
		</button>
		<div id="common_title">Edit <?php echo $file_name?></div>
	</div>	
	
	<textarea class="code_view" name="contents" style="font-family:courier new; font-size:12px; white-space:nowrap !important; height:500px !important; width:99%; overflow:auto;"><?php echo $file_contents?></textarea>
</form>
	