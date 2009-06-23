
$('#faq_wrapper_%VAR% dd.faq_answer').hide();

// add open/close icons
$("#faq_wrapper_%VAR% dt a.toggle").click(function(){		
		$dt = $(this).parent('dt');
		current = $dt.attr('class');	
		if('minus' == current) opposite = 'plus';
		else opposite = 'minus';

		$dt.removeClass(current).addClass(opposite)
		   .next('dd.faq_answer').slideToggle('fast');
		return false;		
});