
$('#faq_wrapper_%VAR% dd.faq_answer').hide();

// add open/close icons
$("#faq_wrapper_%VAR% dt.faq_item a.toggle").click(function(){		
		iconClass = $(this).siblings('span').attr('class');	
		if('minus' == iconClass)
			iconClass = 'plus';
		else
			iconClass = 'minus';
			
		img = '<img src="/assets/images/public/'+ iconClass +'.png" alt="">';
			
		$(this).siblings('span').removeClass().addClass(iconClass).html(img);
		$(this).parent('dt').next('dd.faq_answer').slideToggle('fast');
		return false;		
});