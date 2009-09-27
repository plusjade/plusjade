<?php
$common = array(
	'input:name' => 'Name',
	'input:phone' => 'Phone',
	'input:email' => 'Email',
	'input:url' => 'Website',
	'input:date' => 'Date',
	'input:time' => 'Time',
	'input:number' => 'Number'
);
$generic = array(
	'input' => 'Text Input',
	'textarea' => 'Paragraph Text',
	'select' => 'Dropdown Selection',
	'radio' => 'Multiple Choice',
	'checkbox' => 'Checkboxes'
);

$required = (empty($item->album))
	? ''
	: ' CHECKED';
?>
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_format/edit/<?php echo $item->id?>" method="POST" class="custom_ajaxForm">	
	<input type="hidden" name="meta" value="">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive">Save Changes</button>
		<div id="common_title">Edit Form Element</div>
	</div>	

	<div class="common_full_panel">
		<div class="common_half_left">
			<b>Type</b>
			<select name="type">
				<optgroup label="Common Fields">
					<?php 
					foreach($common as $value => $text)
						if($value == $item->type)
							echo "<option value=\"$value\" SELECTED>$text</option>";
						else	
							echo "<option value=\"$value\">$text</option>";
					?>
				</optgroup>
				
				<optgroup label="Generic Fields">
					<?php
					foreach($generic as $value => $text)
						if($value == $item->type)
							echo "<option value=\"$value\" SELECTED>$text</option>";
						else	
							echo "<option value=\"$value\">$text</option>";
					?>
				</optgroup>
			</select>
			
			<br/><br/>	
			
			<b>Title</b>
			<br><input type="text" name="title" value="<?php echo $item->title?>" class="send_input" rel="text_req" style="width:300px">	
		
		
			<br/><br/>	
			
			<b>Required:</b> <input type="checkbox" name="album" value="1" $<?php echo $required?>> yes
			
			<br/><br/>	
			
		
			<b>Instructions For User</b><br/>
			<textarea name="body" lass="render_html"><?php echo $item->body?></textarea>
			
		</div>
		
		<div class="common_half_right">
		
			<b><span id="link_example"></span></b>
			<br/>
		
			<div id="build_meta">
				<b>Selection Choices</b>
				
				<br/><br/>
				
				<ul id="generic_sortable_list" class=" ui-tabs-nav">
					<?php
					$choices = json_decode($item->meta);
					if(!empty($choices) AND is_array($choices))
					{
						foreach($choices as $choice)
						{
							?>
							<li class="root_entry">
								<ul class="row_wrapper">
									<li class="drag_box"><span class="icon move"> &#160; &#160; </span> DRAG </li>
									<li class="data"><input type="text" value="<?php echo $choice->value?>"></li>
									<li class="delete_item"><span class="icon cross">&#160; &#160;</span></li>				
								</ul>
							</li>
							<?php
						}
					}
					else
					{
						for($x=1; $x <= 3 ; $x++):?>
							<li class="root_entry">
								<ul class="row_wrapper">
									<li class="drag_box"><span class="icon move"> &#160; &#160; </span> DRAG </li>
									<li class="data"><input type="text"></li>
									<li class="delete_item"><span class="icon cross">&#160; &#160;</span></li>				
								</ul>
							</li>
						<?php endfor;				
					}
					?>
				</ul>
				
				<span class="icon plus">&#160; &#160;</span> <a href="#" id="add_field">Add Field</a>
			</div>
		
		</div>
		
	</div>
	
</form>

<style type="text/css">

	li.data input{
		width:260px;
	}
	li.delete_item{
		width:40px !important;
	}
	
	.common_half_left{
		background:#eee;
		width:40%;
		float:left;
		padding:10px;
	}
	.common_half_right{
		width:54%;
		float:right;
		padding:10px;
		border:1px dashed #eee;
	}
</style>


<script type="text/javascript">
	$('#generic_sortable_list').sortable({ 
		handle	: '.drag_box',
		axis	: 'y',
		containment: '#build_meta'
	});	
	
// activate the add field function
	$('a#add_field').click(function(){
		$('#generic_sortable_list li:first')
		.clone()
		.appendTo('#generic_sortable_list');
		return false;
	});

  // activate the selection delete icons
	$('#generic_sortable_list').click($.delegate({
	
		'.delete_item': function(e){
			$(e.target).parent('ul').parent('li').remove();
			return false;
		},
		'.delete_item span': function(e){
			$(e.target).parent('li').parent('ul').parent('li').remove();
			return false;
		}		
	
	}));

	
// custom ajax form, validates inputs and unique page_names	
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData, form){
			if(! $("input", form[0]).jade_validate()) return false;	
			$(document).trigger('show_submit.plusjade');
		
			// do we need to pass meta data?
			var is_meta = $("select[name='type'] option:selected", form[0]).val();
			if(is_meta == 'select' || is_meta == 'radio' || is_meta == 'checkbox'){
				// JSONize any selection inputs.
				var data = new Array();
				$('#generic_sortable_list input').each(function(){
					var field = new Object();
					field.value = $(this).val();
					//field.caption = $(this).attr('title');
					data.push(field);
				});
				// assign to first input; meta to our json string.
				formData[0].value = $.toJSON(data);
				console.log($.toJSON(data));
			}
		},
		success: function(data) {
			$(document).trigger('server_response.plusjade', data);	
		}
	});
	
</script>









