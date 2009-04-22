/*
* Wanneer er een item teveel wordt gzet, moet het terug naar zijn originele plaats.
*
*
*
*/
(function($) {

$.widget("ui.tree", {
    _init: function() {

		var self = this; 

		this.element.sortable({
			items: "li",
			distance: this.options.distance,
			placeholder: this.options.placeholder,
			helper: this.options.helper,
			handle: this.options.handle,
			scroll: this.options.scroll,
			appendTo: this.options.appendTo, 
			cursor: "move",  
			start: function(e, ui) {
				ui.item.after('<li class="ui-tree-startpoint" style="display: none"></li>'); 
			}, 
			stop: function(e, ui) { 
				if(this.revertItem){
					$('.ui-tree-startpoint').after(ui.item);  
				} 
				else{
					if(this.nestItem){  
		               	var itemBefore = $(ui.item).prev();
						var ul = itemBefore.find("ul");
						if(!ul.length) var ul = $("<ul></ul>").appendTo(itemBefore);

						$(ui.item).appendTo(itemBefore.find("> ul") ); 
					
						if(ui.item.parents('ul').length + $("ul", ui.item).length >= self.options.maxDepth){
							$('li', ui.item).appendTo(ui.item.parent());
						} 
					}    
				}
				$('.ui-tree-startpoint').remove();
			},
			sort: function(e, ui){       
				var itemBefore = $(ui.placeholder).prev();    
				if(itemBefore.is('.ui-sortable-helper')) itemBefore = $('.ui-sortable-helper').prev();
				if(itemBefore.is('.ui-tree-startpoint')) itemBefore = $('.ui-sortable-helper').prev(); 
				if(itemBefore.is('li') && !itemBefore.is('.ui-sortable-helper')){ 
				   var itemBeforePosLeft = ui.originalPosition.left; 
				   if(ui.position.left > itemBeforePosLeft + self.options.tabSize){
						if(self.options.maxDepth > 0 && (ui.placeholder.parents('ul').length + $("ul", ui.placeholder).length) >= self.options.maxDepth){
							this.nestItem = false;
						  	ui.placeholder.addClass(self.options.maxDepthError);
							ui.placeholder.html(self.options.maxDepthErrorText);
						}
						else{ 
							this.nestItem = true;                                   
							if(ui.placeholder.hasClass(self.options.maxDepthError)) ui.placeholder.html('');
							ui.placeholder.removeClass(self.options.maxDepthError);  
					   	}  
						ui.placeholder.css({marginLeft: self.options.tabSize+"px"}); 
					}   
					else{ 
						this.nestItem = false;
						ui.placeholder.css({marginLeft: "0px"});  
						if(ui.placeholder.hasClass(self.options.maxDepthError)) ui.placeholder.html('');
						ui.placeholder.removeClass(self.options.maxDepthError);  
					} 
					
					var level = ui.placeholder.parents('ul').length;
					this.nestItem?level++:level;
					
					if(self.options.maxItems[level-1]){ 
						var ul = $(" > ul",itemBefore); 
						var currentNrOfItems = this.nestItem ? $(' > li', ul).length+1 : $(' > li', ui.placeholder.parent()).length;  
						    
						if($(ui.placeholder).parent().get(0) == $(ui.item).parent().get(0) && !this.nestItem && this.placeholderIndex != 0)currentNrOfItems -= 2; 
						if(ul.get(0) == $(ui.item).parent().get(0) && this.nestItem && this.placeholderIndex != 0)currentNrOfItems -= 2; 
						  
						if(currentNrOfItems > self.options.maxItems[level-1]){
							ui.placeholder.addClass(self.options.maxItemsError); 
							ui.placeholder.html(self.options.maxItemsErrorText); 
							this.revertItem = true;  
						}   
						else{                                                       
							if(ui.placeholder.hasClass(self.options.maxItemsError)) ui.placeholder.html(''); 
							ui.placeholder.removeClass(self.options.maxItemsError); 
							this.revertItem = false;
						} 
						
					} 
					else{
						if(ui.placeholder.hasClass(self.options.maxItemsError)) ui.placeholder.html(''); 
						ui.placeholder.removeClass(self.options.maxItemsError); 
						this.revertItem = false;
					}  
				}   
			},
			change: function(e, ui){ 				
				this.placeholderIndex =  $("li",$(ui.placeholder).parent()).index($(ui.placeholder)); 
			   	if(this.placeholderIndex == 0){ 
					this.nestItem = false;
					ui.placeholder.css({marginLeft: "0px"}); 
					ui.placeholder.removeClass(self.options.maxDepthError);
					
					var level = ui.placeholder.parents('ul').length;
					
					if(self.options.maxItems[level-1]){ 
						var currentNrOfItems = $(' > li', ui.placeholder.parent()).length;  
						    
						if($(ui.placeholder).parent().get(0) == $(ui.item).parent().get(0))currentNrOfItems -= 2;  
						 
						if(currentNrOfItems > self.options.maxItems[level-1]){
							ui.placeholder.addClass(self.options.maxItemsError);
							ui.placeholder.html(self.options.maxItemsErrorText);   
							this.revertItem = true; 
						}   
						else{                                                      
							if(ui.placeholder.hasClass(self.options.maxItemsError)) ui.placeholder.html(''); 
							ui.placeholder.removeClass(self.options.maxItemsError); 
							this.revertItem = false;
						}  
					}
					else{
						if(ui.placeholder.hasClass(self.options.maxItemsError)) ui.placeholder.html(''); 
						ui.placeholder.removeClass(self.options.maxItemsError); 
						this.revertItem = false;
					} 
				}
			}
		}); 

		//Make certain nodes droppable 
		
		$(this.options.dropOn, this.element).droppable({
			accept: "li",
			tolerance: "guess"
		});  
	},
	serialize: function(o) {  

		var items = this._getItemsAsjQuery();
		var str = []; o = o || {};
		
		$(items).each(function() {
			var res = ($(this.item || this).attr(o.attribute || 'id') || '').match(o.expression || (/(.+)[-=_](.+)/));
			if(res){  
								
				var subItems = $('li', this.item || this);
				if(subItems.length > 0){
					 str.push((o.key || res[1]+'[]')+'='+(o.key && o.expression ? res[1] : res[2])+'_'+subItems.length);
				}  
				else{
					str.push((o.key || res[1]+'[]')+'='+(o.key && o.expression ? res[1] : res[2]));
				}
			}
		});
		
		return str.join('&');  
	},
	toArray: function(o) {
		
		var items = this._getItemsAsjQuery(o && o.connected);
		var ret = [];

		items.each(function() { ret.push($(this).attr(o.attr || 'id')); });
		return ret;
		
	},
	_getItemsAsjQuery: function() {
		
		var items = [];
		var queries = []; 
		var sortOn = 'li';
		
		queries.push([$.isFunction(sortOn) ? sortOn.call(this.element, null, { options: this.options, item: this.currentItem }) : $(sortOn, this.element).not(".ui-sortable-helper"), this]);

		for (var i = queries.length - 1; i >= 0; i--){ 
			queries[i][0].each(function() {
				items.push(this);
			});
		};

		return $(items);
		
	}
});

$.extend($.ui.tree, {
	getter: "serialize toArray",
	defaults: { 
		tabSize: 40,
		maxDepth: -1, 
		maxDepthError: 'ui-tree-deptherror',
		maxDepthErrorText: 'Only three levels are alowed!',
		maxItems: [], 
		maxItemsError: 'ui-tree-limiterror', 
		maxItemsErrorText: 'You have reached the maximum number of items for this level!', 
		placeholder: 'ui-tree-placeholder'
	}
});

})(jQuery);