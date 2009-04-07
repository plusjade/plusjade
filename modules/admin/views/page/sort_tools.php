
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
			$position = 0;
			foreach($tools as $tool)
			{
				echo '<li id="tool_'. $tool->guid .'">';
				echo '<a href="/get/tool/move/' . $tool->guid . '" rel="facebox" id="secondary" style="float:right;font-size:0.8em">move to another page</a>';
				echo ++$position.'. '.$tool->name;
				echo '</li>';
			}
		?>
		</ul>
	</div>
</form>

