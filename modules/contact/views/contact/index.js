
$("#email_form_wrapper").hide();

// Initialize Inline View
//------------------------	
$(".inline_form").click(function(){
	$("#email_form_wrapper").slideToggle("slow");
	return false;	
});

// Enable ajaxForm on normal pages
// -----------------------------------
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
	

$("form input").focus(function(){
	$("form input").removeClass("input_focus");
	$(this).addClass("input_focus");
});

