
<?php echo form::open("theme/manage", array('class'=> 'ajaxForm') )?>	

	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Save Changes</button>
		<div id="common_title"></div>
	</div>	
	
	<div class="common_left_panel">
	
	</div>
	<div class="common_main_panel">
	
		<div class="fieldsets">
			<b>Upload New Theme</b>
			
			<div style="padding:10px">
				<input type="file" name="theme">
				
				<div style="padding:10px; margin:10px; background:#eee">
					Upload this theme as an update to my installed theme: <b><?php echo $this->theme?></b>
					<br><button type="submit" class="jade_positive">Upload and Overwite</button>
				</div>
				
				<b>OR</b>
				
				<div style="padding:10px; margin:10px; background:#eee">
					<input type="text" name="theme_name">
					<br><button type="submit" class="jade_positive">Upload as New</button>
				</div>
				
			</div>
		</div>
		Current installed Themes:
		<ul>
		<?php
			foreach($themes as $theme)
				if($this->theme == $theme)
					echo "<li><span class=\"icon flag\">&#160; &#160; </span> <b>$theme</b> <a href=\"#\">download</a></li>";
				else
					echo "<li>$theme <a href=\"#\">download</a></li>";
			?>
		</ul>
	</div>
</form>


<script language="javascript">
	$(document).ready(function(){		 
	});	
</script>