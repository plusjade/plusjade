
<?php echo form::open("page/tools/$page_id", array('id' => 'custom_ajaxForm') )?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="update_page" class="jade_positive">
			<img src="/images/check.png" alt=""/> Save Tool Order
		</button>
		<div id="common_title">Sort Tools</div>
	</div>	
	
	<div id="page_tools">
		<ul id="generic_sortable_list" class="ui-tabs-nav">	
			<?php
			$position = 1;
			$holder = array('');
			foreach($tools as $tool)
			{
				$holder[$position] = $tool->container;
				
				if ( $tool->container != $holder[$position-1])
					echo 'Container '. $tool->container;
					
				echo '<li id="tool_'. $tool->guid .'">';
				echo '<div>';
				echo '<a href="/get/tool/move/' . $tool->guid . '" rel="facebox" id="secondary" style="float:right;font-size:0.8em">change page</a>';
				echo ++$position.'. '.$tool->name;
				echo '</div>';
				echo '</li>';
			}
		?>
		</ul>
	</div>
</form>

