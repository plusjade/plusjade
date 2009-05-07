
<?php echo form::open('page/add', array( 'class' => 'ajaxForm' ) );?>	

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_page" class="jade_positive">
			<img src="/images/check.png" alt=""/> Add Page
		</button>
		<div id="common_title">Add New Page</div>
	</div>	
	
	<div id="page_label" class="fieldsets huge">
		<b>Name</b> <input id="" type="text" name="label" value="" rel="text_req" size="25" maxlength="40">
		<br><br>
		<b>Link</b> <?php echo url::site();?><input type="text" name="page_name" value="" size="30" maxlength="30">
	</div>
	
</form>

<script type="text/javascript">
$("input[name='label']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
	$("input[name='page_name']").val(input);
});
$("input[name='page_name']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
	$(this).val(input);
});
</script>