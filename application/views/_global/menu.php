<?php

/*
	Consider putting this in the build_page controller
	and/or
	somehow separating Menu View logic entirely
	Include maybe?
	So custom menus can be implemented safely.	
*/

$db		= new Database;	
$pages	= $db->query("
	SELECT id, page_name, label
	FROM pages 
	WHERE fk_site = '$this->site_id' 
	AND menu = 'yes' 
	AND enable = 'yes'
	ORDER BY position
");

# pages not built via build_page controller will not have $this_page_id set.
$this_page_id = (empty($this_page_id)) ? '' : $this_page_id;
?>

<ul>
	<?php
		foreach($pages as $page)
		{
			$name	= ('' == $page->label) ? $page->page_name : $page->label;
			$class	= ($page->id == $this_page_id) ? 'class="selected"' : '';
			?>
			<li><a href="<?php echo url::site("$page->page_name")?>" <?php echo $class?>> <?php echo $name?></a></li>
			<?php
		}
	?>
</ul>