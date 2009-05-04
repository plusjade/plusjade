
$('.phpajaxcalendar_wrapper').click($.delegate({

	'a[rel*=ajax]' : function(e){			
		$('a[rel*=ajax]').removeClass('selected');
		$(e.target).addClass('selected');
		
		$('#loadImage').show();
		$('#calendar_event_details').load(e.target.href,{}, function(){
			$('#loadImage').hide().click();
		});
		return false;
	},
	
	'a.monthnav' : function(e){	
		$('.phpajaxcalendar_wrapper').load(e.target.href, {limit: 25}, function(){
		});
		return false;
	}		
}));