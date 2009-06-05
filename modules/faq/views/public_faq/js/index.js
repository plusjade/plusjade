
$('body').click($.delegate({
	// add open/close icons
	"#faq_wrapper_%VAR% dt.faq_item a.toggle": function(e){		
		iconClass = $(e.target).siblings('span').attr('class');	
		if('minus' == iconClass)
			iconClass = 'plus';
		else
			iconClass = 'minus';
			
		img = '<img src="/assets/images/public/'+ iconClass +'.png" alt="">';
			
		$(e.target).siblings('span').removeClass().addClass(iconClass).html(img);
		$(e.target).parent('dt').next('dd.faq_answer').slideToggle('fast');
		return false;		
	},
	
	// initialize this tool. this allows us to re-init via calling .click()
	'#faq_init_%VAR%':function(e){
		$('#faq_wrapper_%VAR% dd.faq_answer').hide();
	}
}));

$('#faq_init_%VAR%').click();

