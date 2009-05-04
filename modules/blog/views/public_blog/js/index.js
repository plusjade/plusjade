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