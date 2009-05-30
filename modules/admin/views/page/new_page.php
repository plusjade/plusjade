<style type="text/css">	
	#new_page_wrapper .pane_left{
		width:51%;
		float:left;	
	}
	.sub_options, .root_options{
		background:#eee;
		padding:10px;
	}
	#new_page_wrapper .pane_right{
		width:42%;
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
	#link_example{
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
			<b>Page Label</b>
			<br><input id="" type="text" name="label" value="" rel="text_req" maxlength="50" style="width:330px">
			<br><br>
			<b>Page Link</b>
			<br><input type="text" name="page_name" maxlength="50" style="width:330px">
			<div id="page_exists" class="aligncenter error_msg"></div>
		
			<p style="line-height:1.6em">
				Add to Main Menu?
				<br><input type="checkbox" name="menu"> Yes!
			</p>
		</div>
		
		<div class="pane_right">		
			<?php
			$slash = '/'; # add slash if not a root page.
			if(! empty($page_builders) )
			{
				$slash = '';
				?>
				<div class="root_options">
					Install Page Builder?<br><br>
					<select name="page_builder">
						<option value="0">none - (blank page)</option>
						<?php 
						foreach($page_builders as $tool)
							echo "<option value='$tool->id'>$tool->name</option>";
						?>
					</select>	
				</div>
				<?php
			}
			?>
		</div>
		
	</div>
	
	<div id="new_page_url">
		Your new page URL:
		<br><b><?php echo url::site()."$directory$slash"?><span id="link_example">...</span></b>
	</div>	
	<input type="hidden" name="directory" value="<?php echo $directory?>">
</form>	

<?php if('' == $directory) $directory = 'ROOT' # for javascript?>
<script type="text/javascript">

	
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
	
	// load the page_name filter
	var filter = [<?php echo $filter?>];
		
	/* 
	 * custom ajax form, validates inputs and unique page_names
	 *
	 */		
	var options = {
		beforeSubmit: function(){
			if(! $(".custom_ajaxForm input").jade_validate() )
				return false

			sent_page = $("input[name='page_name']").val();				
			filter_duplicates = filter.in_array(sent_page);
			
			if(filter_duplicates) {
				$('#page_exists').html('Page name already exists');
				$("input[name='page_name']").addClass('input_error');
				return false;
			}
		},
		success: function(data) {
			directory = '<?php echo $directory?>';
			path_for_css = directory.replace(/\//g,'_');

			$('div.'+path_for_css).append(data);
			$.facebox('Page added!', "status_reload", "facebox_2");
			setTimeout('$.facebox.close("facebox_2")', 500);			
		}					
	};
	$(".custom_ajaxForm").ajaxForm(options);
	
	
</script>