

<h2 class="format_form_header"><?php echo $format->name?></h2> 

<form action="" method="POST" class="format_form_list">
	<input type="hidden" name="post_handler" value="format:<?php echo $format->id ?>">
	
	<?php	
	if(isset($errors))
	{
		?>
		<div class="jade_form_status_box box_negative">
			<div><span>Form Not Sent!</span></div>
			<div class="error_count">
				Only <?php echo count($errors)?> more fields to go...
			</div>
		</div>
		<?php
	}		
			
	foreach($format->format_items as $item)
	{
		$type		= explode(':', $item->type);
		$field_name	= "$item->id:" . valid::filter_php_url($item->title);
		$unique		= 1;
		$star		= '';
		$jade_error	= '';
		$rel		= '';
		
		# was there an error with this field?
		if(!empty($errors[$field_name]))
			$jade_error = 'jade_error';
		
		# does a previous field value exist?
		if(empty($values[$field_name]))
			$values[$field_name] = '';

		# is field required? :: 0 = optional , 1 = required		
		if(!empty($item->album))
		{
			$star = '<span class="jade_required_star">*</span>';
			
			# what type of requirement? (this is for javascript)
			$rel = (isset($type[1]))
				? $type[1]
				: 'text';
			$rel = $rel . '_req';
		}
		?>
		<fieldset id="format_item_<?php echo $item->id?>" class="format_item <?php echo $jade_error?>" rel="<?php echo $item->id?>">
			<label><?php echo $item->title?> <?php echo $star?></label>
			<div class="user_info"><?php echo $item->body?></div>
		<?php
		switch($type[0])
		{
			case 'input':
				# is this a special type of input ?
				if(isset($type[1]) AND 'url' == $type[1])
					echo 'http://';
				?>
				<input type="text" name="<?php echo $field_name?>" value="<?php echo $values[$field_name]?>" class="text_input" rel="<?php echo $rel?>">
				<?php
				break;
				
			case 'textarea':
				?>
				<textarea name="<?php echo $field_name?>" rel="<?php echo $rel?>"><?php echo $values[$field_name]?></textarea>
				<?php
				break;
				
			case 'select':
				$choices = json_decode($item->meta);	
				if(!empty($choices) AND is_array($choices))
				{
					echo "<select name='$field_name'>";
					foreach($choices as $choice)
					{
						if($values[$field_name] == $choice->value)
							echo "<option selected='selected'>$choice->value</option>";
						else
							echo "<option>$choice->value</option>";
						++$unique;
					}
					echo '</select>';
				}
				break;


			case 'radio':
				$choices = json_decode($item->meta);	

				if(!empty($choices) AND is_array($choices))
				{
					$required = (empty($item->album))
						? ''
						: 'checked="checked"';
						
					foreach($choices as $choice)
					{
						if($values[$field_name] == $choice->value)
							echo "<input type='radio' name='$field_name' value='$choice->value' id='opt_$unique' checked='checked'> ";
						else
							echo "<input type='radio' name='$field_name' value='$choice->value' id='opt_$unique' $required>";
						
						echo "<label for='opt_$unique'>$choice->value</label> <br/>";
						++$unique;
						$required = '';
					}
				}
				break;

				
			case 'checkbox':
				# TODO this is not finished. 
				$choices = json_decode($item->meta);	
				if(!empty($choices) AND is_array($choices))
					foreach($choices as $choice)
					{
						$url_value = valid::filter_php_url($choice->value);
						echo "<input type='checkbox' name='$field_name:$url_value' id='opt_$unique'> <label for='opt_$unique'>$choice->value</label> <br/>";
						++$unique;
					}
				break;
		}
			if(isset($errors[$field_name])) echo "<span class='error_msg'>{$errors[$field_name]}</span>";
		?>	
		</fieldset>
		<?php
	}	
	?>	
	
		<button type="submit">Submit Form</button>
</form>


<script type="text/javascript">
		$('.format_form_list').ajaxForm({		 
			beforeSubmit: function(fields, form){
				if(! $("input, textarea", form[0]).jade_validate()) return false;
				$('.format_form_list').html('<div class="ajax_loading">Loading...</div>');
			},
			success: function(data) {
				$('.format_form_list').replaceWith(data);
			}
		});
</script>


