

<style type="text/css">
#format_people_filmstrip{
	margin:10px auto;
	background:#d9f7fd;
	width:550px;
	border:1px solid #ceedf4;
	overflow:auto;
}
.people_thumb{
	width:110px;
	float:left;
	margin:5px;
	padding:5px;
	background:#fff;
	border:1px solid #ceedf4;
	text-align:center;
}
.people_thumb img{
	padding:3px;
	background:#eee;
	width:100px;
	heigth:125px;
}
.people_thumb a:hover img,
.people_thumb a.active img{
	background:orange;
}
#format_filmstrip_wrapper{
	margin-top:10px;
}
#format_filmstrip_wrapper .format_item{
	padding:10px;
	margin:10px;
	border:1px solid #ccc;
}
</style>


<div id="format_people_filmstrip">
	<?php
		foreach($format->format_items as $item):
			$thumb = image::thumb($item->image);
	?>
	<div class="people_thumb">
		<a href="#format_item_<?php echo $item->id?>" rel="<?php echo $item->id?>">
			<img src="<?php echo "$img_path/$item->image"?>" alt="">
		</a>
		<br><?php echo $item->title?>
	</div>
	<?php endforeach;?>
</div>


<div id="format_filmstrip_wrapper">		
	<?php foreach($format->format_items as $item):?>
		<div id="format_item_<?php echo $item->id?>" class="person format_item" rel="<?php echo $item->id?>">
			<div class="body">
				<?php echo $item->body?>
				<br>
				<?php
					if(!empty($item->album))
						echo Load_Tool::factory('album')->_index($item->album, TRUE);
				?>
			</div>
		</div>
	<?php endforeach;?>
</div>


