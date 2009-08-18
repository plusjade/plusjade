

<style type="text/css">

#format_people_wrapper{
	
}
#format_people_wrapper .person{
	margin-bottom:5px;
	overflow:auto;
}
#format_people_wrapper .portrait{
	width:30%;
	float:left;
	height:250px;
	padding:10px;
	text-align:center;
}
#format_people_wrapper .portrait img{
	padding:5px;
	background:#eee;
	border:1px solid #ccc;
}
#format_people_wrapper .body{
	width:62%;
	float:right;
	height:250px;
	padding:10px;
	margin:10px;
	border-top:1px dashed #ccc;
}
</style>


<div id="format_people_wrapper">		
	<?php foreach($format->format_items as $item):?>
		<div id="format_item_<?php echo $item->id?>" class="person format_item" rel="<?php echo $item->id?>">
			<div class="portrait">
				<img src="<?php echo "$img_path/$item->image"?>" width="200px" height="225px" alt="">
				<br><?php echo $item->title?>
			</div>
			<div class="body">
				<?php echo $item->body?>
				<br>
				<?php
					if(!empty($item->album))
						echo Load_Tool::factory('album')->_index($item->album);
				?>
			</div>
		</div>
	<?php endforeach;?>
</div>