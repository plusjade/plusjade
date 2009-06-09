
<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">
		<img src="<?php echo url::image_path('admin/check.png')?>" alt=""> Save Image Order
	</button>
	<div id="common_title">Manage Album</div>
</div>	

<div id="left_window">
	<div id="select-result">
	
	</div>
</div>

<div id="sortable_images_wrapper">
	<?php
		foreach($items as $image)
		{
			?>
			<div id="image_<?php echo $image->id?>">
				<span>drag</span>
				<img src="<?php echo url::site()?>data/<?php echo $this->site_name?>/assets/images/albums/<?php echo $album->id?>/sm_<?php echo $image->path?>" rel="<?php echo $image->id?>" height="100px" width="100px">	
			</div>
			<?php
		}				
	?>
</div>

<script type="text/javascript">
	
	$("#sortable_images_wrapper").sortable({items:'div', handle:'span'});
	$("#sortable_images_wrapper").selectable({
		
		filter:'img',
		stop: function(){
			var result = $("#select-result").empty();
			$(".ui-selected:first", this).each(function(){
				id = $(this).attr('rel');
				actions ='<p><a href="get/edit_album/edit_item/'+ id +'" class="edit_image" rel="facebox" id="2">Edit this</a><br><br><a href="/get/edit_album/delete_image/'+ id +'" class="delete_image" rel="'+ id +'">Delete this</a></p>';
				result.append(actions);
				$(this).clone().prependTo(result);
				
			});
			
			var id_string = '';
			$(".ui-selected", this).each(function(){
				id_string += $(this).attr('rel') + '-';
			});
			if('' != id_string){
				delete_all ='<p><a href="/get/edit_album/delete_image/'+ id_string +'" class="delete_image" rel="'+ id_string +'">Delete all selected</a></p>';
				result.append(delete_all);		
			}
			
			$('a.delete_image').click(function(){
				if (confirm('This cannot be undone! Delete selected image(s)?')) {				
					url = $(this).attr('href');
					id_array = $(this).attr('rel').split('-');
					
					$.get(url, function(){
						$(id_array).each(function(){
							$('#image_'+ this, $('#sortable_images_wrapper')).remove();
						});
						result.empty();
					});
				}
				return false;
			});			
		}
	});
		
	<?php
		echo javascript::save_sort('album', $album->id, NULL, 'sortable_images_wrapper');	
	?>
</script>