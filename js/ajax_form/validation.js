			beforeSubmit: function(){
				var form = jqForm[0]; 
				$(".public_ajaxForm input").each(function(){
					if($(this).hasClass("name_req") && !this.name.value){
							$(this).addClass("input_error");
					};
				});
				
				
//Public form validation
//------------------------
function validate(){
	$("form input[type=text]").removeClass("input_error");

	var nameRegex = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
	var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	$("form input[type=text]").each(function()
	{
		var rel = $(this).attr("rel");
		switch (rel)
		{
			case "text_req":
				if(!this.value){$(this).addClass("input_error")};
			break;

			case "email_req":
				if(! this.value.match(emailRegex) ){$(this).addClass("input_error")};
			break;
		}
	});
	return false;
}
