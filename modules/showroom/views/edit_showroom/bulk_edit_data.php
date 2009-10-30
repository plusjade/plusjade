

<?php echo $items->count()?> items.
<table style="width:99%">
	<tr><th>#</th><th>name</th> <th>intro</th> <th>desc</th> <th>img</th></tr>		
	<?php foreach($items as $item):
	
		$images = json_decode($item->images);
		$done = false;
		foreach ($images as $image)
			if($done)
				break;
			else
			{
				$first_image = $image->path;
				$done = true;
			}
	?>
		<tr>
			<td><?php echo $item->id?></td>
			<td><input type="text" name="name[<?php echo $item->id?>]" value="<?php echo $item->name?>"></td>
			<td><input type="text" name="intro[<?php echo $item->id?>]" value="<?php echo $item->intro?>"></td>
			<td width="10px"><input type="text" name="body[<?php echo $item->id?>]" style="width:1px; !important" value="<?php echo $item->body?>"></td>
			<td><input type="text" name="images[<?php echo $item->id?>]" style="width:220px !important;" value='<?php echo $first_image?>' READONLY></td>
		</tr>
	<?php endforeach;?>
</table>