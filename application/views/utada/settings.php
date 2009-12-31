

<?php echo form::open_multipart("utada/settings", array('class' => 'custom_ajaxForm'))?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="edit_text" class="jade_positive">Save Changes</button>
		<div id="common_title">Configure +Jade Settings</div>
	</div>	
	
	Serve Full Page Cache:
	<select name="serve_page_cache">
		<option value="yes">Yes</option>
<?php 
	if(!Kohana::config('core.serve_page_cache'))
		echo '<option value="no" selected="selected">No</option>';
	else
		echo '<option value="no">No</option>';
?>
	</select>
	<br/>Non protected pages are <b>always<b/> fully cached.
	This option is whether or not we want to serve them.
	
	<br/><br/>
	<a href="/get/utada/clear_all_cache">Clear All Page Cache.</a>
	<br/>Clear the page cache files of EVERY site.
	(each site can clear its page cache separately via site/settings.
	
	<br/><br/>
	Reset CSS cache: 
	<select name="reset_css_cache">
		<option value="yes">Yes</option>
<?php 
	if(!Kohana::config('core.reset_css_cache'))
		echo '<option value="no" selected="selected">No</option>';
	else
		echo '<option value="no">No</option>';
?>
	</select>
	<br/>If yes, every page request will update the global and page css cache even if they already exist.
	
	<br/><br/>
	Clear ALL CSS Cache:
	<br/><a href="/get/utada/clear_all_css">Clear All CSS Cache.</a>
	<br/>Clears css cache folder for all sites.
	
</form>

<script type="text/javascript">
		$('.custom_ajaxForm').ajaxForm({		 
			beforeSubmit: function(fields, form){
				$('.custom_ajaxForm').html('<div class="ajax_loading">Submitting...</div>');
			},
			success: function(data) {
				$('.custom_ajaxForm').html(data);
			}
	});
			
			
			
</script>