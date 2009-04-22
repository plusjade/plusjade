
<a href="/showroom/<?php echo "$category"?>">Back to Item List</a>
<br>
<p class="aligncenter">
	Link to this item: <b><?php echo url::site("showroom/$category/$item->url")?></b>
</p>
Intro:
<div>
	<? echo $item->intro?>
</div>

Body:
<div>
	<? echo $item->body?>
</div>

<p>
	<img src="<?php echo $img_path.'/'.$item->img?>" alt="">
</p>