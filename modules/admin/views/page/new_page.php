<style type="text/css">	
	#new_page_wrapper .pane_left{
		width:42%;
		float:left;	
	}
	.sub_options, .root_options{
		background:#eee;
		padding:10px;
	}
	#new_page_wrapper .pane_right{
		width:51%;
		float:right
	}

	#new_page_url{
		text-align:center;
		line-height:1.8em;
	}
	#new_page_url b{
		color:#333;
		font-size:1.4em;
	}
	#link_example,
	#sub_page_example{
		
		color:green;
	}	
</style>
<?php echo form::open('page/add', array('class' => 'custom_ajaxForm') );?>	

	<div id="common_tool_header" class="buttons">
		<button type="submit" id="add_page_submit" name="add_page" class="jade_positive">
			<img src="<?php echo url::image_path('check.png')?>" alt=""/> Add Page
		</button>
		<div id="common_title">Add New Page</div>
	</div>	

	<div id="new_page_wrapper" class="fieldsets big">
		
		<div class="pane_left">
			<input type="radio" name="page_type" value="root" CHECKED> <b>Add Root Page</b>
			<div class="root_options">
				Install Page Builder?<br><br>
				<select name="page_builder">
					<option value="0">none - (blank page)</option>
					<?php 
					foreach($page_builders as $tool)
					{
						echo "<option value='$tool->id'>$tool->name</option>";
					}
					?>
				</select>	
			</div>
			
			<p style="text-align:center;font-size:1.6em;">OR</p>
			
			<input type="radio" name="page_type" value="sub"> <b>Add Sub Page</b> 
			<div class="sub_options">
				Sub page of...<br><br>
				<select name="sub_page" disabled="disabled">
					<?php 
					foreach($allowed_pages as $page)
					{
						$pieces = explode(':',$page);
						echo "<option value='$pieces[1]' rel='$pieces[0]'>$pieces[1]</option>";
					}
					?>
				</select>				
			</div>
		</div>
		
		<div class="pane_right">
			<b>Page Label</b>
			<br><input id="" type="text" name="label" value="" rel="text_req" maxlength="50" style="width:330px">
			<br><br>
			<b>Page Link</b>
			<br><input type="text" name="page_name" maxlength="50" style="width:330px">
			<div id="page_exists" class="aligncenter error_msg"></div>
		
			<p style="line-height:1.6em">
				Add this page to primary menu?
				<br><input type="checkbox" name="menu"> Yes!
			</p>
		</div>
		
	</div>
	
	<div id="new_page_url">
		Your new page URL:
		<br><b><?php echo url::site()?><span id="sub_page_example"></span><span id="link_example"></span></b>
	</div>	

</form>	
<script type="text/javascript">
	
	// if sub_page update the url example
	function update_sub_page(){
		value = $("select[name='sub_page'] option:selected").text();
		$('span#sub_page_example').html(value +'/');
	}
	
	
	/* 
	 * toggle root or sub page options
	 *
	 */
	$("input[name='page_type']").click(function(){
		value = $(this).val();
		if('root' == value){
			$("select[name='sub_page']").attr('disabled','disabled');
			$("select[name='page_builder']").removeAttr('disabled');
			$('span#sub_page_example').html('');
		}
		if('sub' == value){
			$("select[name='sub_page']").removeAttr('disabled');
			$("select[name='page_builder']").attr('disabled','disabled');
			update_sub_page();
		}
	});
	
	// if sub_page update the url example
	$("select[name='sub_page']").change(function(){
		update_sub_page();
	});	
	
	
	// if page_builder, update the name/label/url views
	$("select[name='page_builder']").change(function(){	
		value = '';
		num = $("option:selected", this).val();
		if(0 != num)
			value = $("option:selected", this).text();
			
		$("input[name='label']").val(value);
		$("input[name='page_name']").val(value.toLowerCase());
		$('span#link_example').html(value.toLowerCase());
		
	});
	
	
	//sanitize and populate page_name fields
	$("input[name='label']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
		$("input[name='page_name']").val(input);
		$('span#link_example').html(input);
	});
	$("input[name='page_name']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-');
		$(this).val(input);
		$('span#link_example').html(input);
	});
	
	/* 
	 * custom validation to check for unique page_names
	 *
	 */
	Array.prototype.in_array = function(p_val) {
		for(var i = 0, l = this.length; i < l; i++) {
			if(this[i] == p_val)
				return true;
		}
		return false;
	}
	
	// if root page use root_filter
	var root_filter = [<?php echo $root_filter?>];
	
	
	// collection of sub_filters (sub_page_filters)
	var filters = new Object;
	<?php echo $sub_filter?>

	
	/* 
	 * custom ajax form, validates inputs and unique page_names
	 *
	 */		
	var options = {
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			sent_page = $("input[name='page_name']").val();				
			sub_node = $("select[name='sub_page']:enabled option:selected").attr('rel');
			
			if(sub_node){
				if(filters[sub_node])
					filter_duplicates = filters[sub_node].in_array(sent_page);
				else
					filter_duplicates = false;
			}
			else
				filter_duplicates = root_filter.in_array(sent_page);
			
			if(filter_duplicates) {
				$('#page_exists').html('Page name already exists');
				$("input[name='page_name']").addClass('input_error');
				return false;
			}
		},
		success: function(data) {
			$.facebox(data, "status_reload", "facebox_2");
			sub_page = $('#sub_page_example').html();			
			window.location = '<?php echo url::site()?>' + sub_page + sent_page;						
		}					
	};
	$(".custom_ajaxForm").ajaxForm(options);
	

	
</script>