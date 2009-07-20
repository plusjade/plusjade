$('#forum_index_wrapper li a').click(function(){
	var url = $(this).attr('href');
	$('#forum_content_wrapper')
	.html('<div class="ajax_loading">Loading...</div>')
	.load(url);
	return false;
});
	
$('#forum_content_wrapper').click($.delegate({
	'a.preview': function(e){
		var id = $(e.target).attr('rel');
		$('blockquote#preview_'+ id).slideToggle('fast');
		return false;
	},	
	
	'a.load_post_view' : function(e){
		var url = $(e.target).attr('href');
		$('#forum_content_wrapper')
		.html('<div class="ajax_loading">Loading...</div>')
		.load(url);
		return false;
	},

	'.cast_vote' : function(e){
		var url = $(e.target).attr('href');
		var count = $(e.target).siblings('span').html();
		if(1 == $(e.target).attr('rel'))
			$(e.target).siblings('span').html(++count);
		else
			$(e.target).siblings('span').html(--count);
		
		$(e.target).parent('div').children('a').remove();
		
		$.get(url, function(data){
		});
		return false;
	}
	
}));