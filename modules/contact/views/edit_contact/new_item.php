

<?php echo form::open("edit_contact/add/$tool_id", array('class' => 'ajaxForm', 'rel' => $js_rel_command) )?>
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_contacts" class="jade_positive">
			<img src="<?php echo url::image_path('admin/check.png')?>" alt=""/> Add Contacts
		</button>
		<div id="common_title">Add New Contacts</div>	
	</div>
	
	<table id="new_contact_table" border="0" width="100%">
		<?php
		# Disable all installed contacts
		$installed = array();
		foreach($contacts as $contact)
			$installed[$contact->type] = 'yes';
		
		#list all contact types (installable)
		foreach($contact_types as $type)
		{
			$class =''; $disabled = '';	
			if(! empty($installed["$type->type"]) )
			{
				$class		= 'class="gray"';
				$disabled	= 'DISABLED';
			}
			?>
			<tr <?php echo $class?>>
				<td width="20px"><input type="checkbox" name="id[<?php echo $type->type_id?>]" value="<?php echo $type->type?>" <?php echo $disabled?>></td>
				<td width="60px"><?php echo $type->type?></td>
				<td width="20px"></td><td>description</td>
			</tr>		
			<?php
		}
		?>
	</table>
</form>