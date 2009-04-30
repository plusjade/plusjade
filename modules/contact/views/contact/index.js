


//init
$(".email_form_wrapper").hide();
$(".inline_form").click(function(){
	$(".email_form_wrapper").slideToggle("slow");
	return false;	
});
//email form
var options = {
	target: ".public_ajaxForm",
	beforeSubmit: function(){
		if( $(".public_ajaxForm input[type=text]").jade_validate() )
			return true;
		else
			return false;
	}
};
$(".public_ajaxForm").ajaxForm(options);	

//newsletter form
var options = {
	target: "#newsletter_form",
	beforeSubmit: function(){
		if( $("#newsletter_form input[type=text]").jade_validate() )
			return true;
		else
			return false;
	},
	success: function(data) {
		$.facebox(data, "status_reload", "facebox_2");
		return false;		
	}		
};
$("#newsletter_form").ajaxForm(options);	


//focus
$("form input").focus(function(){
	$("form input").removeClass("input_focus");
	$(this).addClass("input_focus");
});

