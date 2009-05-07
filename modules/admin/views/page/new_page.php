
<?php echo form::open('page/add', array( 'class' => 'custom_ajaxForm' ) );?>	

	<div id="common_tool_header" class="buttons">
		<button type="submit" id="add_page_submit" name="add_page" class="jade_positive">
			<img src="<?php echo url::image_path('check.png')?>" alt=""/> Add Page
		</button>
		<div id="common_title">Add New Page</div>
	</div>	
	
	<div id="page_label" class="fieldsets huge">
		<b>Name</b> <input id="" type="text" name="label" value="" rel="text_req" size="25" maxlength="40">
		<br><br>
		<b>Link</b> <?php echo url::site();?><input type="text" name="page_name" value="" size="30" maxlength="30">
		<div id="page_exists" class="aligncenter error_msg"></div>
	</div>
	
</form>
<script type="text/javascript">
/* custom validation to check for unique page_names */
Array.prototype.in_array = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
}
var v_array = [<?php echo $page_names?>];

var options = {
	beforeSubmit: function(){
		if(! $(".custom_ajaxForm input").jade_validate() )
			return false

		sent_page = $("input[name='page_name']").val();			
		if(v_array.in_array(sent_page)) {	
			$('#page_exists').html('Page name already exists');
			$("input[name='page_name']").addClass('input_error');
			return false;
		}	
	},
	success: function(data) {
		$.facebox(data, "status_reload", "facebox_2");
		window.location = '<?php url::site()?>' + sent_page;						
	}					
};
$(".custom_ajaxForm").ajaxForm(options);
	

$("input[name='label']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
	$("input[name='page_name']").val(input);
});
$("input[name='page_name']").keyup(function(){
	input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
	$(this).val(input);
});
</script>