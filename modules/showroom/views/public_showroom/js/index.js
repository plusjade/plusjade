
target_div = '#showroom_wrapper_%VAR% div.showroom_items';
loading = '<div class="ajax_loading">Loading...</div>';

$("#showroom_wrapper_%VAR%").click($.delegate({		
	"a.loader": function(e){
			$(target_div).html(loading);
			$(target_div).load(e.target.href, function(){
				$('#click_hook').click();
			});
			return false;
	},
	
	"a img.loader": function(e){
			$(target_div).html(loading);
			url = $(e.target).parent("a").attr("href");
			$(target_div).load(url, function(){
				$('#click_hook').click();
			});
			return false;
	}
}));