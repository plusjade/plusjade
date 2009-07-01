
<span id="on_close"><?php echo $js_rel_command?></span>

<?php echo form::open("edit_contact/add/$tool_id", array('class' => 'ajaxForm'))?>
	<div id="common_tool_header" class="buttons">
		<button type="submit" name="add_contacts" class="jade_positive">Add Contacts</button>
		<div id="common_title">Add New Contacts</div>	
	</div>
	
	<div class="common_left_panel">
		Select the contacts you wish to add.
	</div>
	
	<div class="common_main_panel fieldsets">
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
				
				# TAKE MAP OFFLINE CUZ IT STILL NEEDS TO BE WORKED ON!!!!
				if(! empty($installed["$type->type"]) OR 'map' == $type->type )
				{
					$class		= 'class="gray"';
					$disabled	= 'DISABLED';
				}
				?>
				<tr <?php echo $class?>>
					<td width="30px"><input type="checkbox" name="id[<?php echo $type->type_id?>]" value="<?php echo $type->type?>" <?php echo $disabled?>></td>
					<td width="70px"><b><?php echo $type->type?></b></td>
					<td width="20px"></td>
					<td><?php echo $type->desc?></td>
				</tr>		
				<?php
			}
			?>
		</table>
	</div>
	
</form>


