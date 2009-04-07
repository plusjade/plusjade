// login form validation
//------------------------
function validate(){

	$("form input[type=text]").removeClass("input_error");

	var nameRegex = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
	var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	var errors = false;
	
	$("form input[type=text]").each(function()
	{
		var rel = $(this).attr("rel");
		switch (rel)
		{
			case "text_req":
				if(!this.value){
					$(this).addClass("input_error");
					errors = true;
				}
			break;

			case "email_req":
				if(! this.value.match(emailRegex) ){
					$(this).addClass("input_error");
					errors = true;
				}
			break;
		}
	});
	if(errors)
		return false;
	else 
		return true;

};

$("#jade_login_form").submit(function(){		
	if(!validate())
		return false;
});

