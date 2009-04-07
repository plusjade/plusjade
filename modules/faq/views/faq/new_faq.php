Add New Frequently asked question


<?php echo form::open()?>

	<div class="fieldsets">
		<input type="submit" name="add_faq" value="Add New FAQ" style="float:right">
		
		<b>Question</b><br>
		<input type="text" name="question" value="" size="50">
		
		<br><b>Answer</b>	
	</div>
	<textarea name="answer"></textarea>
</form>