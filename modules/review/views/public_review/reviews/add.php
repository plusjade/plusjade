

<?php if($allowed):?>

Add a New Review!

<form action="" method="post">

	Rating 
	<select name="rating">
		<option>1</option>
		<option>2</option>
		<option>3</option>
		<option>4</option>
		<option>5</option>
	</select>

	<p>
		Comments:<br/>	
		<textarea name="body"></textarea>
	</p>

	email
	<br/><input type="text" name="email" value="<?php echo $email?>" />

	<br/><br/>
	<input type="hidden" name="name" value="<?php echo $name?>" />
	<button type="submit">Submit Review</button>
</form>

<?php else:?>
	Reviews are currently closed!
<?php endif;?>