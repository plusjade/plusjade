/*
$('div.post_body').expander({
	 userCollapseText: '[collapse]'
});
*/
$('.get_comments').click(function(){
	id = $(this).attr('rel');
	$('#show_comments_'+id).html('<div class="ajax_loading">Loading...</div>');
	$('#show_comments_'+id).load('/blog/comment/'+id);
	return false;
});