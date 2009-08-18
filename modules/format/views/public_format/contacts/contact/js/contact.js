	
$('#contact_wrapper_%VAR% .email_form_wrapper').hide();
	
$('#contact_wrapper_%VAR% .inline_form').click(function(){
	$("#contact_wrapper_%VAR% .email_form_wrapper").slideToggle("slow");
	return false;
});

//email form
$("#contact_wrapper_%VAR% form.public_ajaxForm").ajaxForm({
	target: "#contact_wrapper_%VAR% form.public_ajaxForm",
	beforeSubmit: function(){
		if( $("#contact_wrapper_%VAR% form.public_ajaxForm input[type=text]").jade_validate() )
			return true;
		else
			return false;
	}
});	

//newsletter form
$('#contact_wrapper_%VAR% #newsletter_form').ajaxForm({
	target: "#contact_wrapper_%VAR% #newsletter_form",
	beforeSubmit: function(){
		if( $("#contact_wrapper_%VAR% #newsletter_form input[type=text]").jade_validate() )
			return true;
		else
			return false;
	},
	success: function(data) {
		$.facebox(data, 'status_reload', 'facebox_2');
		return false;		
	}			
});	
