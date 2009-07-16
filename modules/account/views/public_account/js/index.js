
// dashboard navigation links logged in users.
$('ul.account_user_actions li a[rel="ajax"]').click(function(){
	$('ul.account_user_actions li a').removeClass('selected');
	
	var url = $(this).addClass('selected').attr('href');
	$('#inject_content_wrapper')
	.html('<div class="ajax_loading">loading...</div>')
	.load(url);
	return false;
});