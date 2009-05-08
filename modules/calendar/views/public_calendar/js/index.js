
$('.phpajaxcalendar_wrapper').click($.delegate({

	'a[rel*=ajax]' : function(e){		
		$('a[rel*=ajax]').removeClass('selected');
		$(e.target).addClass('selected');
		
		$('#calendar_event_details').html('<div class="ajax_loading">Loading...</div>');
		$('#calendar_event_details').load(e.target.href,{}, function(){
			$('#click_hook').click();
		});
		return false;
	},
	
	'a.monthnav' : function(e){
		$('.phpajaxcalendar_wrapper').html('<div class="ajax_loading">Loading...</div>');
		$('.phpajaxcalendar_wrapper').load(e.target.href, {limit: 25});
		return false;
	}		
}));