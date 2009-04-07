<div id="common_module_header">
	Edit <b>FAQ</b> module for: <span><?php echo url::site($page->page_name)?></span>
</div>

<div id="container-1">

	<ul class="ui-tabs-nav" style="display:none">
	<?php
		$counter = 1;
		foreach($faqs as $faq)
		{	
			echo '<li><a href="#fragment-'.$counter.'"><span>'.$faq->question.'</span></a></li>';
			++$counter;
		}
		echo '<li><a href="#fragment-'.$counter.'"><span><b>Add New Item</b></span></a></li>';
	?>		
	</ul>
	
	<div id="common_module_menu_bar">
		Choose item to edit:
		<select id="select_tab_nav">
			<?php
				$counter = 1;
				foreach($faqs as $faq)
				{	
					echo '<option value="'.$counter.'">'.$faq->question.'</option>';
					++$counter;
				}
				echo '<option value="'.$counter.'"><b>Add New Item</b></option>';
			?>	
		</select>
		
		--- Add New Item. -- Edit Module CSS
	
	</div>
	
	<div id="each_slide_panel_wrapper">
		<?php 
		$position = 0;
		$counter = 1;
		foreach($faqs as $faq)
		{			
			echo '<div id="fragment-'.$counter.'" class="each_slide_panel ui-tabs-hide">'."\t\t\t\n";
			echo form::open();
				?>
				<div class="fieldsets">
					<input type="submit" name="update" value="Update Faq" style="float:right">
					<input type="submit" name="delete" value="delete!" style="float:right">
					<b>Question</b> 
					<input type="text" name="question" value="<?php echo $faq->question?>" size="50">
				</div>
				<textarea name="answer"><?php echo $faq->answer?></textarea>
				<input type="hidden" name="id" value="<?php echo $faq->id?>">
			</form>
			<?php		
			echo "</div>\n";
			++$counter;
		}
		
		echo '<div id="fragment-'.$counter.'" class="ui-tabs-hide">';
			echo form::open();
				?>		
				<h3>Add New Panel</h3>
				<div class="fieldsets">
					<input type="submit" name="add_faq" value="Add New Faq" style="float:right">				
					<b>Question</b> 
					<input type="text" name="question" value="" size="50">
				</div>
				<textarea name="answer"></textarea>
			</form>
		</div>
	
	</div>
</div>	
	

