

/*
	Setup the image album user interface.
*/

// make images sortable and selectable
	$("#sortable_images_wrapper").sortable({items:'div', handle:'span'});
	$("#sortable_images_wrapper").selectable({filter:'img'});

// make space droppable for images in files browser	
	$("#sortable_images_wrapper").droppable({
		activeClass: 'ui-state-highlight',
		accept: 'img.image_file',
		drop: function(event, ui) {
			$(ui.draggable).addClass('selected');
			$(ui.draggable).parent('div').addClass('selected');		
			
			$('<div></div>')
			.addClass('album_images')
			.prepend('<span class="handle"><b>edit</b> <em>drag</em></span>')
			.append($(ui.draggable ).clone())
			.appendTo(this);
			return false;
		}
	});
// make trash droppable
	$("#image_trash").droppable({
		activeClass: 'ui-state-highlight',
		accept: '.album_images',
		drop: function(event, ui) {
			$(ui.draggable).remove();
		}
	});