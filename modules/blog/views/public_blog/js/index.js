
$('body').click($.delegate({
	'#blog_wrapper_%VAR% a[rel*=blog_ajax]': function(e){
		url = $(e.target).attr('href');
		$('.blog_content').html('<div class="ajax_loading">Loading...</div>');
		$('.blog_content').load(url);
		return false;
	},
	'#blog_wrapper_%VAR% a.get_comments':function(e){
		url			= $(e.target).attr('rel');
		$container	= $(e.target).parent();
		
		$container.html('<div class="ajax_loading">Loading...</div>');
		$.get(url, function(data){
			$container.replaceWith(data);
		});
		return false;
	}
}));

$('body').submit($.delegate({
	'#blog_wrapper_%VAR% form.public_ajaxForm': function(e){
		form = $(e.target);
		$(form).ajaxSubmit({
			beforeSubmit: function(){
				if( $('input[type=text]', form).jade_validate() )
					return true;
				
				return false;
			},
			success: function(data) {
				$('.comments_wrapper', form).append(data);
				$('.add_comment', form).replaceWith('<div class="blog_response">Comment Added!</div>');
			}
		});			
		return false;
	}	
}));