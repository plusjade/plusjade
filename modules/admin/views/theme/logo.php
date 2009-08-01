
<style type="text/css">
	#logo_droppable_wrapper{
		text-align:center;
		background:#eee;
		border:1px solid #ccc;
	}
	#logo_droppable_wrapper img{
		display:block;
		margin:5px;
		padding:5px;
		cursor:pointer;
		cursor:hand;
	}
</style>
<?php echo form::open('theme/logo')?>

	<div id="common_tool_header" class="buttons">
		<button type="submit" name="save_logo" class="jade_positive">Save Logo</button>
		<div id="common_title">Select an image banner for your Website.</div>
	</div>


	<div class="common_left_panel" style="text-align:center">
		<a href="#" class="get_file_browser" rel="albums">Choose new Logo</a>
	</div>

	<div id="logo_droppable_wrapper" class="common_main_panel" style="height:300px">
	<?php if(!empty($this->banner)):?>
		<img src="<?php echo "$img_path/$this->banner"?>" alt="activated banner">
	<?php else:?>
		Place image here.
	<?php endif;?>
	</div>


</div>

<script type="text/javascript">

// make space droppable.
	$("#logo_droppable_wrapper").droppable({
		activeClass: 'ui-state-highlight',
		accept: 'img.image_file',
		drop: function(event, ui) {
			$(this).empty();
			$(ui.draggable).addClass('selected');
			$(ui.draggable).parent('div').addClass('selected');		
			
			var img_path = $(ui.draggable).attr('rel');
			var img = new Image();
			img.src = img_path;
			var html ='<img src="'+ img_path +'" class="selected" rel="'+ img_path +'" alt="">';
			$('#logo_droppable_wrapper').prepend(html);
		}
	});
	
// Save logo handler
	$('button[name="save_logo"]').click(function(){
		var image = $('#logo_droppable_wrapper img').attr('src');
		$('.facebox .show_submit').show();
		$.post('/get/theme/logo', {banner: image}, function(data){
			$('#BANNER a').html('<img src="' + image +'" id="header_banner" alt="">');
			$(document).trigger('server_response.plusjade', data);
		});
		return false;
	});	
	
</script>
