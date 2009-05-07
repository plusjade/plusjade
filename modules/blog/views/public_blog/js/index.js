$('body').submit($.delegate({
	'form.public_ajaxForm': function(e){
		form = $(e.target);
		var options = {
			beforeSubmit: function(){
				if( $('input[type=text]', form).jade_validate() )
					return true;
				else
					return false;
			},
			success: function(data) {
				$('.comments_wrapper', form).append(data);
				$('.add_comment', form).replaceWith('<div class="blog_response">Comment Added!</div>');
			}	
		};
		$(form).ajaxSubmit(options);	
		return false;
	}	
}));

$('body').click($.delegate({
	'a[rel*=blog_ajax]': function(e){
		url = $(e.target).attr('href');
		$('.blog_content').html('<div class="ajax_loading">Loading...</div>');
		$('.blog_content').load(url);
		return false;
	}
}));