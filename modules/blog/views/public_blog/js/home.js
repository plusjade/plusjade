$('div.post_body').expander({
	 userCollapseText: '[collapse]'
});

$('.get_comments').click(function(){
	id = $(this).attr('rel');
	$('#post_comments_'+id).load('/blog/comment/'+id);
	return false;
});