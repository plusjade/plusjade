
/*
	Setup the image album user interface.
*/

// make images sortable and selectable
	$("#sortable_images_wrapper").sortable({items:'div', handle:'span.move'});
	$("#sortable_images_wrapper").selectable({filter:'img', delay: 20});