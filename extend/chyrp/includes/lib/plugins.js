/**
 * jQuery UI @VERSION
 *
 * Copyright (c) 2008 Paul Bakaus (ui.jquery.com)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI
 */
;(function($){jQuery.extend(jQuery.expr[':'],{data:"jQuery.data(a, m[3])"});$.ui={plugin:{add:function(module,option,set){var proto=$.ui[module].prototype;for(var i in set){proto.plugins[i]=proto.plugins[i]||[];proto.plugins[i].push([option,set[i]]);}},call:function(instance,name,args){var set=instance.plugins[name];if(!set){return;}
for(var i=0;i<set.length;i++){if(instance.options[set[i][0]]){set[i][1].apply(instance.element,args);}}}},cssCache:{},css:function(name){if($.ui.cssCache[name]){return $.ui.cssCache[name];}
var tmp=$('<div class="ui-gen">').addClass(name).css({position:'absolute',top:'-5000px',left:'-5000px',display:'block'}).appendTo('body');$.ui.cssCache[name]=!!((!(/auto|default/).test(tmp.css('cursor'))||(/^[1-9]/).test(tmp.css('height'))||(/^[1-9]/).test(tmp.css('width'))||!(/none/).test(tmp.css('backgroundImage'))||!(/transparent|rgba\(0, 0, 0, 0\)/).test(tmp.css('backgroundColor'))));try{$('body').get(0).removeChild(tmp.get(0));}catch(e){}
return $.ui.cssCache[name];},disableSelection:function(el){$(el).attr('unselectable','on').css('MozUserSelect','none');},enableSelection:function(el){$(el).attr('unselectable','off').css('MozUserSelect','');},hasScroll:function(e,a){var scroll=(a&&a=='left')?'scrollLeft':'scrollTop',has=false;if(e[scroll]>0){return true;}
e[scroll]=1;has=(e[scroll]>0);e[scroll]=0;return has;}};var _remove=$.fn.remove;$.fn.remove=function(){$("*",this).add(this).triggerHandler("remove");return _remove.apply(this,arguments);};function getter(namespace,plugin,method){var methods=$[namespace][plugin].getter||[];methods=(typeof methods=="string"?methods.split(/,?\s+/):methods);return($.inArray(method,methods)!=-1);}
$.widget=function(name,prototype){var namespace=name.split(".")[0];name=name.split(".")[1];$.fn[name]=function(options){var isMethodCall=(typeof options=='string'),args=Array.prototype.slice.call(arguments,1);if(isMethodCall&&getter(namespace,name,options)){var instance=$.data(this[0],name);return(instance?instance[options].apply(instance,args):undefined);}
return this.each(function(){var instance=$.data(this,name);if(isMethodCall&&instance&&$.isFunction(instance[options])){instance[options].apply(instance,args);}else if(!isMethodCall){$.data(this,name,new $[namespace][name](this,options));}});};$[namespace][name]=function(element,options){var self=this;this.widgetName=name;this.widgetEventPrefix=$[namespace][name].eventPrefix||name;this.widgetBaseClass=namespace+'-'+name;this.options=$.extend({},$.widget.defaults,$[namespace][name].defaults,options);this.element=$(element).bind('setData.'+name,function(e,key,value){return self.setData(key,value);}).bind('getData.'+name,function(e,key){return self.getData(key);}).bind('remove',function(){return self.destroy();});this.init();};$[namespace][name].prototype=$.extend({},$.widget.prototype,prototype);};$.widget.prototype={init:function(){},destroy:function(){this.element.removeData(this.widgetName);},getData:function(key){return this.options[key];},setData:function(key,value){this.options[key]=value;if(key=='disabled'){this.element[value?'addClass':'removeClass'](this.widgetBaseClass+'-disabled');}},enable:function(){this.setData('disabled',false);},disable:function(){this.setData('disabled',true);},trigger:function(type,e,data){var eventName=(type==this.widgetEventPrefix?type:this.widgetEventPrefix+type);e=e||$.event.fix({type:eventName,target:this.element[0]});return this.element.triggerHandler(eventName,[e,data],this.options[type]);}};$.widget.defaults={disabled:false};$.ui.mouse={mouseInit:function(){var self=this;this.element.bind('mousedown.'+this.widgetName,function(e){return self.mouseDown(e);});if($.browser.msie){this._mouseUnselectable=this.element.attr('unselectable');this.element.attr('unselectable','on');}
this.started=false;},mouseDestroy:function(){this.element.unbind('.'+this.widgetName);($.browser.msie&&this.element.attr('unselectable',this._mouseUnselectable));},mouseDown:function(e){(this._mouseStarted&&this.mouseUp(e));this._mouseDownEvent=e;var self=this,btnIsLeft=(e.which==1),elIsCancel=(typeof this.options.cancel=="string"?$(e.target).parents().add(e.target).filter(this.options.cancel).length:false);if(!btnIsLeft||elIsCancel||!this.mouseCapture(e)){return true;}
this._mouseDelayMet=!this.options.delay;if(!this._mouseDelayMet){this._mouseDelayTimer=setTimeout(function(){self._mouseDelayMet=true;},this.options.delay);}
if(this.mouseDistanceMet(e)&&this.mouseDelayMet(e)){this._mouseStarted=(this.mouseStart(e)!==false);if(!this._mouseStarted){e.preventDefault();return true;}}
this._mouseMoveDelegate=function(e){return self.mouseMove(e);};this._mouseUpDelegate=function(e){return self.mouseUp(e);};$(document).bind('mousemove.'+this.widgetName,this._mouseMoveDelegate).bind('mouseup.'+this.widgetName,this._mouseUpDelegate);return false;},mouseMove:function(e){if($.browser.msie&&!e.button){return this.mouseUp(e);}
if(this._mouseStarted){this.mouseDrag(e);return false;}
if(this.mouseDistanceMet(e)&&this.mouseDelayMet(e)){this._mouseStarted=(this.mouseStart(this._mouseDownEvent,e)!==false);(this._mouseStarted?this.mouseDrag(e):this.mouseUp(e));}
return!this._mouseStarted;},mouseUp:function(e){$(document).unbind('mousemove.'+this.widgetName,this._mouseMoveDelegate).unbind('mouseup.'+this.widgetName,this._mouseUpDelegate);if(this._mouseStarted){this._mouseStarted=false;this.mouseStop(e);}
return false;},mouseDistanceMet:function(e){return(Math.max(Math.abs(this._mouseDownEvent.pageX-e.pageX),Math.abs(this._mouseDownEvent.pageY-e.pageY))>=this.options.distance);},mouseDelayMet:function(e){return this._mouseDelayMet;},mouseStart:function(e){},mouseDrag:function(e){},mouseStop:function(e){},mouseCapture:function(e){return true;}};$.ui.mouse.defaults={cancel:null,distance:1,delay:0};})(jQuery);(function($){$.widget("ui.draggable",$.extend({},$.ui.mouse,{init:function(){if(this.options.helper=='original'&&!(/^(?:r|a|f)/).test(this.element.css("position")))
this.element[0].style.position='relative';(this.options.disabled&&this.element.addClass('ui-draggable-disabled'));this.mouseInit();},mouseStart:function(e){var o=this.options;if(this.helper||o.disabled||$(e.target).is('.ui-resizable-handle'))
return false;var handle=!this.options.handle||!$(this.options.handle,this.element).length?true:false;$(this.options.handle,this.element).find("*").andSelf().each(function(){if(this==e.target)handle=true;});if(!handle)return false;if($.ui.ddmanager)
$.ui.ddmanager.current=this;this.helper=$.isFunction(o.helper)?$(o.helper.apply(this.element[0],[e])):(o.helper=='clone'?this.element.clone():this.element);if(!this.helper.parents('body').length)this.helper.appendTo((o.appendTo=='parent'?this.element[0].parentNode:o.appendTo));if(this.helper[0]!=this.element[0]&&!(/(fixed|absolute)/).test(this.helper.css("position")))this.helper.css("position","absolute");this.margins={left:(parseInt(this.element.css("marginLeft"),10)||0),top:(parseInt(this.element.css("marginTop"),10)||0)};this.cssPosition=this.helper.css("position");this.offset=this.element.offset();this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left};this.offset.click={left:e.pageX-this.offset.left,top:e.pageY-this.offset.top};this.offsetParent=this.helper.offsetParent();var po=this.offsetParent.offset();if(this.offsetParent[0]==document.body&&$.browser.mozilla)po={top:0,left:0};this.offset.parent={top:po.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:po.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)};var p=this.element.position();this.offset.relative=this.cssPosition=="relative"?{top:p.top-(parseInt(this.helper.css("top"),10)||0)+this.offsetParent[0].scrollTop,left:p.left-(parseInt(this.helper.css("left"),10)||0)+this.offsetParent[0].scrollLeft}:{top:0,left:0};this.originalPosition=this.generatePosition(e);this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()};if(o.cursorAt){if(o.cursorAt.left!=undefined)this.offset.click.left=o.cursorAt.left+this.margins.left;if(o.cursorAt.right!=undefined)this.offset.click.left=this.helperProportions.width-o.cursorAt.right+this.margins.left;if(o.cursorAt.top!=undefined)this.offset.click.top=o.cursorAt.top+this.margins.top;if(o.cursorAt.bottom!=undefined)this.offset.click.top=this.helperProportions.height-o.cursorAt.bottom+this.margins.top;}
if(o.containment){if(o.containment=='parent')o.containment=this.helper[0].parentNode;if(o.containment=='document'||o.containment=='window')this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,$(o.containment=='document'?document:window).width()-this.offset.relative.left-this.offset.parent.left-this.helperProportions.width-this.margins.left-(parseInt(this.element.css("marginRight"),10)||0),($(o.containment=='document'?document:window).height()||document.body.parentNode.scrollHeight)-this.offset.relative.top-this.offset.parent.top-this.helperProportions.height-this.margins.top-(parseInt(this.element.css("marginBottom"),10)||0)];if(!(/^(document|window|parent)$/).test(o.containment)){var ce=$(o.containment)[0];var co=$(o.containment).offset();this.containment=[co.left+(parseInt($(ce).css("borderLeftWidth"),10)||0)-this.offset.relative.left-this.offset.parent.left,co.top+(parseInt($(ce).css("borderTopWidth"),10)||0)-this.offset.relative.top-this.offset.parent.top,co.left+Math.max(ce.scrollWidth,ce.offsetWidth)-(parseInt($(ce).css("borderLeftWidth"),10)||0)-this.offset.relative.left-this.offset.parent.left-this.helperProportions.width-this.margins.left-(parseInt(this.element.css("marginRight"),10)||0),co.top+Math.max(ce.scrollHeight,ce.offsetHeight)-(parseInt($(ce).css("borderTopWidth"),10)||0)-this.offset.relative.top-this.offset.parent.top-this.helperProportions.height-this.margins.top-(parseInt(this.element.css("marginBottom"),10)||0)];}}
this.propagate("start",e);this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()};if($.ui.ddmanager&&!o.dropBehaviour)$.ui.ddmanager.prepareOffsets(this,e);this.helper.addClass("ui-draggable-dragging");this.mouseDrag(e);return true;},convertPositionTo:function(d,pos){if(!pos)pos=this.position;var mod=d=="absolute"?1:-1;return{top:(pos.top
+this.offset.relative.top*mod
+this.offset.parent.top*mod
-(this.cssPosition=="fixed"||(this.cssPosition=="absolute"&&this.offsetParent[0]==document.body)?0:this.offsetParent[0].scrollTop)*mod
+(this.cssPosition=="fixed"?$(document).scrollTop():0)*mod
+this.margins.top*mod),left:(pos.left
+this.offset.relative.left*mod
+this.offset.parent.left*mod
-(this.cssPosition=="fixed"||(this.cssPosition=="absolute"&&this.offsetParent[0]==document.body)?0:this.offsetParent[0].scrollLeft)*mod
+(this.cssPosition=="fixed"?$(document).scrollLeft():0)*mod
+this.margins.left*mod)};},generatePosition:function(e){var o=this.options;var position={top:(e.pageY
-this.offset.click.top
-this.offset.relative.top
-this.offset.parent.top
+(this.cssPosition=="fixed"||(this.cssPosition=="absolute"&&this.offsetParent[0]==document.body)?0:this.offsetParent[0].scrollTop)
-(this.cssPosition=="fixed"?$(document).scrollTop():0)),left:(e.pageX
-this.offset.click.left
-this.offset.relative.left
-this.offset.parent.left
+(this.cssPosition=="fixed"||(this.cssPosition=="absolute"&&this.offsetParent[0]==document.body)?0:this.offsetParent[0].scrollLeft)
-(this.cssPosition=="fixed"?$(document).scrollLeft():0))};if(!this.originalPosition)return position;if(this.containment){if(position.left<this.containment[0])position.left=this.containment[0];if(position.top<this.containment[1])position.top=this.containment[1];if(position.left>this.containment[2])position.left=this.containment[2];if(position.top>this.containment[3])position.top=this.containment[3];}
if(o.grid){var top=this.originalPosition.top+Math.round((position.top-this.originalPosition.top)/o.grid[1])*o.grid[1];position.top=this.containment?(!(top<this.containment[1]||top>this.containment[3])?top:(!(top<this.containment[1])?top-o.grid[1]:top+o.grid[1])):top;var left=this.originalPosition.left+Math.round((position.left-this.originalPosition.left)/o.grid[0])*o.grid[0];position.left=this.containment?(!(left<this.containment[0]||left>this.containment[2])?left:(!(left<this.containment[0])?left-o.grid[0]:left+o.grid[0])):left;}
return position;},mouseDrag:function(e){this.position=this.generatePosition(e);this.positionAbs=this.convertPositionTo("absolute");this.position=this.propagate("drag",e)||this.position;if(!this.options.axis||this.options.axis!="y")this.helper[0].style.left=this.position.left+'px';if(!this.options.axis||this.options.axis!="x")this.helper[0].style.top=this.position.top+'px';if($.ui.ddmanager)$.ui.ddmanager.drag(this,e);return false;},mouseStop:function(e){var dropped=false;if($.ui.ddmanager&&!this.options.dropBehaviour)
var dropped=$.ui.ddmanager.drop(this,e);if((this.options.revert=="invalid"&&!dropped)||(this.options.revert=="valid"&&dropped)||this.options.revert===true){var self=this;$(this.helper).animate(this.originalPosition,parseInt(this.options.revert,10)||500,function(){self.propagate("stop",e);self.clear();});}else{this.propagate("stop",e);this.clear();}
return false;},clear:function(){this.helper.removeClass("ui-draggable-dragging");if(this.options.helper!='original'&&!this.cancelHelperRemoval)this.helper.remove();this.helper=null;this.cancelHelperRemoval=false;},plugins:{},uiHash:function(e){return{helper:this.helper,position:this.position,absolutePosition:this.positionAbs,options:this.options};},propagate:function(n,e){$.ui.plugin.call(this,n,[e,this.uiHash()]);if(n=="drag")this.positionAbs=this.convertPositionTo("absolute");return this.element.triggerHandler(n=="drag"?n:"drag"+n,[e,this.uiHash()],this.options[n]);},destroy:function(){if(!this.element.data('draggable'))return;this.element.removeData("draggable").unbind(".draggable").removeClass('ui-draggable-dragging ui-draggable-disabled');this.mouseDestroy();}}));$.extend($.ui.draggable,{defaults:{appendTo:"parent",axis:false,cancel:":input",delay:0,distance:1,helper:"original"}});$.ui.plugin.add("draggable","cursor",{start:function(e,ui){var t=$('body');if(t.css("cursor"))ui.options._cursor=t.css("cursor");t.css("cursor",ui.options.cursor);},stop:function(e,ui){if(ui.options._cursor)$('body').css("cursor",ui.options._cursor);}});$.ui.plugin.add("draggable","zIndex",{start:function(e,ui){var t=$(ui.helper);if(t.css("zIndex"))ui.options._zIndex=t.css("zIndex");t.css('zIndex',ui.options.zIndex);},stop:function(e,ui){if(ui.options._zIndex)$(ui.helper).css('zIndex',ui.options._zIndex);}});$.ui.plugin.add("draggable","opacity",{start:function(e,ui){var t=$(ui.helper);if(t.css("opacity"))ui.options._opacity=t.css("opacity");t.css('opacity',ui.options.opacity);},stop:function(e,ui){if(ui.options._opacity)$(ui.helper).css('opacity',ui.options._opacity);}});$.ui.plugin.add("draggable","iframeFix",{start:function(e,ui){$(ui.options.iframeFix===true?"iframe":ui.options.iframeFix).each(function(){$('<div class="ui-draggable-iframeFix" style="background: #fff;"></div>').css({width:this.offsetWidth+"px",height:this.offsetHeight+"px",position:"absolute",opacity:"0.001",zIndex:1000}).css($(this).offset()).appendTo("body");});},stop:function(e,ui){$("div.DragDropIframeFix").each(function(){this.parentNode.removeChild(this);});}});$.ui.plugin.add("draggable","scroll",{start:function(e,ui){var o=ui.options;var i=$(this).data("draggable");o.scrollSensitivity=o.scrollSensitivity||20;o.scrollSpeed=o.scrollSpeed||20;i.overflowY=function(el){do{if(/auto|scroll/.test(el.css('overflow'))||(/auto|scroll/).test(el.css('overflow-y')))return el;el=el.parent();}while(el[0].parentNode);return $(document);}(this);i.overflowX=function(el){do{if(/auto|scroll/.test(el.css('overflow'))||(/auto|scroll/).test(el.css('overflow-x')))return el;el=el.parent();}while(el[0].parentNode);return $(document);}(this);if(i.overflowY[0]!=document&&i.overflowY[0].tagName!='HTML')i.overflowYOffset=i.overflowY.offset();if(i.overflowX[0]!=document&&i.overflowX[0].tagName!='HTML')i.overflowXOffset=i.overflowX.offset();},drag:function(e,ui){var o=ui.options;var i=$(this).data("draggable");if(i.overflowY[0]!=document&&i.overflowY[0].tagName!='HTML'){if((i.overflowYOffset.top+i.overflowY[0].offsetHeight)-e.pageY<o.scrollSensitivity)
i.overflowY[0].scrollTop=i.overflowY[0].scrollTop+o.scrollSpeed;if(e.pageY-i.overflowYOffset.top<o.scrollSensitivity)
i.overflowY[0].scrollTop=i.overflowY[0].scrollTop-o.scrollSpeed;}else{if(e.pageY-$(document).scrollTop()<o.scrollSensitivity)
$(document).scrollTop($(document).scrollTop()-o.scrollSpeed);if($(window).height()-(e.pageY-$(document).scrollTop())<o.scrollSensitivity)
$(document).scrollTop($(document).scrollTop()+o.scrollSpeed);}
if(i.overflowX[0]!=document&&i.overflowX[0].tagName!='HTML'){if((i.overflowXOffset.left+i.overflowX[0].offsetWidth)-e.pageX<o.scrollSensitivity)
i.overflowX[0].scrollLeft=i.overflowX[0].scrollLeft+o.scrollSpeed;if(e.pageX-i.overflowXOffset.left<o.scrollSensitivity)
i.overflowX[0].scrollLeft=i.overflowX[0].scrollLeft-o.scrollSpeed;}else{if(e.pageX-$(document).scrollLeft()<o.scrollSensitivity)
$(document).scrollLeft($(document).scrollLeft()-o.scrollSpeed);if($(window).width()-(e.pageX-$(document).scrollLeft())<o.scrollSensitivity)
$(document).scrollLeft($(document).scrollLeft()+o.scrollSpeed);}}});$.ui.plugin.add("draggable","snap",{start:function(e,ui){var inst=$(this).data("draggable");inst.snapElements=[];$(ui.options.snap.constructor!=String?(ui.options.snap.items||':data(draggable)'):ui.options.snap).each(function(){var $t=$(this);var $o=$t.offset();if(this!=inst.element[0])inst.snapElements.push({item:this,width:$t.outerWidth(),height:$t.outerHeight(),top:$o.top,left:$o.left});});},drag:function(e,ui){var inst=$(this).data("draggable");var d=ui.options.snapTolerance||20;var x1=ui.absolutePosition.left,x2=x1+inst.helperProportions.width,y1=ui.absolutePosition.top,y2=y1+inst.helperProportions.height;for(var i=inst.snapElements.length-1;i>=0;i--){var l=inst.snapElements[i].left,r=l+inst.snapElements[i].width,t=inst.snapElements[i].top,b=t+inst.snapElements[i].height;if(!((l-d<x1&&x1<r+d&&t-d<y1&&y1<b+d)||(l-d<x1&&x1<r+d&&t-d<y2&&y2<b+d)||(l-d<x2&&x2<r+d&&t-d<y1&&y1<b+d)||(l-d<x2&&x2<r+d&&t-d<y2&&y2<b+d))){if(inst.snapElements[i].snapping)(inst.options.snap.release&&inst.options.snap.release.call(inst.element,null,$.extend(inst.uiHash(),{snapItem:inst.snapElements[i].item})));inst.snapElements[i].snapping=false;continue;}
if(ui.options.snapMode!='inner'){var ts=Math.abs(t-y2)<=20;var bs=Math.abs(b-y1)<=20;var ls=Math.abs(l-x2)<=20;var rs=Math.abs(r-x1)<=20;if(ts)ui.position.top=inst.convertPositionTo("relative",{top:t-inst.helperProportions.height,left:0}).top;if(bs)ui.position.top=inst.convertPositionTo("relative",{top:b,left:0}).top;if(ls)ui.position.left=inst.convertPositionTo("relative",{top:0,left:l-inst.helperProportions.width}).left;if(rs)ui.position.left=inst.convertPositionTo("relative",{top:0,left:r}).left;}
var first=(ts||bs||ls||rs);if(ui.options.snapMode!='outer'){var ts=Math.abs(t-y1)<=20;var bs=Math.abs(b-y2)<=20;var ls=Math.abs(l-x1)<=20;var rs=Math.abs(r-x2)<=20;if(ts)ui.position.top=inst.convertPositionTo("relative",{top:t,left:0}).top;if(bs)ui.position.top=inst.convertPositionTo("relative",{top:b-inst.helperProportions.height,left:0}).top;if(ls)ui.position.left=inst.convertPositionTo("relative",{top:0,left:l}).left;if(rs)ui.position.left=inst.convertPositionTo("relative",{top:0,left:r-inst.helperProportions.width}).left;}
if(!inst.snapElements[i].snapping&&(ts||bs||ls||rs||first))
(inst.options.snap.snap&&inst.options.snap.snap.call(inst.element,null,$.extend(inst.uiHash(),{snapItem:inst.snapElements[i].item})));inst.snapElements[i].snapping=(ts||bs||ls||rs||first);};}});$.ui.plugin.add("draggable","connectToSortable",{start:function(e,ui){var inst=$(this).data("draggable");inst.sortables=[];$(ui.options.connectToSortable).each(function(){if($.data(this,'sortable')){var sortable=$.data(this,'sortable');inst.sortables.push({instance:sortable,shouldRevert:sortable.options.revert});sortable.refreshItems();sortable.propagate("activate",e,inst);}});},stop:function(e,ui){var inst=$(this).data("draggable");$.each(inst.sortables,function(){if(this.instance.isOver){this.instance.isOver=0;inst.cancelHelperRemoval=true;this.instance.cancelHelperRemoval=false;if(this.shouldRevert)this.instance.options.revert=true;this.instance.mouseStop(e);this.instance.element.triggerHandler("sortreceive",[e,$.extend(this.instance.ui(),{sender:inst.element})],this.instance.options["receive"]);this.instance.options.helper=this.instance.options._helper;}else{this.instance.propagate("deactivate",e,inst);}});},drag:function(e,ui){var inst=$(this).data("draggable"),self=this;var checkPos=function(o){var l=o.left,r=l+o.width,t=o.top,b=t+o.height;return(l<(this.positionAbs.left+this.offset.click.left)&&(this.positionAbs.left+this.offset.click.left)<r&&t<(this.positionAbs.top+this.offset.click.top)&&(this.positionAbs.top+this.offset.click.top)<b);};$.each(inst.sortables,function(i){if(checkPos.call(inst,this.instance.containerCache)){if(!this.instance.isOver){this.instance.isOver=1;this.instance.currentItem=$(self).clone().appendTo(this.instance.element).data("sortable-item",true);this.instance.options._helper=this.instance.options.helper;this.instance.options.helper=function(){return ui.helper[0];};e.target=this.instance.currentItem[0];this.instance.mouseCapture(e,true);this.instance.mouseStart(e,true,true);this.instance.offset.click.top=inst.offset.click.top;this.instance.offset.click.left=inst.offset.click.left;this.instance.offset.parent.left-=inst.offset.parent.left-this.instance.offset.parent.left;this.instance.offset.parent.top-=inst.offset.parent.top-this.instance.offset.parent.top;inst.propagate("toSortable",e);}
if(this.instance.currentItem)this.instance.mouseDrag(e);}else{if(this.instance.isOver){this.instance.isOver=0;this.instance.cancelHelperRemoval=true;this.instance.options.revert=false;this.instance.mouseStop(e,true);this.instance.options.helper=this.instance.options._helper;this.instance.currentItem.remove();if(this.instance.placeholder)this.instance.placeholder.remove();inst.propagate("fromSortable",e);}};});}});$.ui.plugin.add("draggable","stack",{start:function(e,ui){var group=$.makeArray($(ui.options.stack.group)).sort(function(a,b){return(parseInt($(a).css("zIndex"),10)||ui.options.stack.min)-(parseInt($(b).css("zIndex"),10)||ui.options.stack.min);});$(group).each(function(i){this.style.zIndex=ui.options.stack.min+i;});this[0].style.zIndex=ui.options.stack.min+group.length;}});})(jQuery);(function($){$.widget("ui.droppable",{init:function(){var o=this.options,accept=o.accept;this.isover=0;this.isout=1;this.options.accept=this.options.accept&&this.options.accept.constructor==Function?this.options.accept:function(d){return d.is(accept);};this.proportions={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight};$.ui.ddmanager.droppables.push(this);},plugins:{},ui:function(c){return{draggable:(c.currentItem||c.element),helper:c.helper,position:c.position,absolutePosition:c.positionAbs,options:this.options,element:this.element};},destroy:function(){var drop=$.ui.ddmanager.droppables;for(var i=0;i<drop.length;i++)
if(drop[i]==this)
drop.splice(i,1);this.element.removeClass("ui-droppable-disabled").removeData("droppable").unbind(".droppable");},over:function(e){var draggable=$.ui.ddmanager.current;if(!draggable||(draggable.currentItem||draggable.element)[0]==this.element[0])return;if(this.options.accept.call(this.element,(draggable.currentItem||draggable.element))){$.ui.plugin.call(this,'over',[e,this.ui(draggable)]);this.element.triggerHandler("dropover",[e,this.ui(draggable)],this.options.over);}},out:function(e){var draggable=$.ui.ddmanager.current;if(!draggable||(draggable.currentItem||draggable.element)[0]==this.element[0])return;if(this.options.accept.call(this.element,(draggable.currentItem||draggable.element))){$.ui.plugin.call(this,'out',[e,this.ui(draggable)]);this.element.triggerHandler("dropout",[e,this.ui(draggable)],this.options.out);}},drop:function(e,custom){var draggable=custom||$.ui.ddmanager.current;if(!draggable||(draggable.currentItem||draggable.element)[0]==this.element[0])return false;var childrenIntersection=false;this.element.find(":data(droppable)").not(".ui-draggable-dragging").each(function(){var inst=$.data(this,'droppable');if(inst.options.greedy&&$.ui.intersect(draggable,$.extend(inst,{offset:inst.element.offset()}),inst.options.tolerance)){childrenIntersection=true;return false;}});if(childrenIntersection)return false;if(this.options.accept.call(this.element,(draggable.currentItem||draggable.element))){$.ui.plugin.call(this,'drop',[e,this.ui(draggable)]);this.element.triggerHandler("drop",[e,this.ui(draggable)],this.options.drop);return true;}
return false;},activate:function(e){var draggable=$.ui.ddmanager.current;$.ui.plugin.call(this,'activate',[e,this.ui(draggable)]);if(draggable)this.element.triggerHandler("dropactivate",[e,this.ui(draggable)],this.options.activate);},deactivate:function(e){var draggable=$.ui.ddmanager.current;$.ui.plugin.call(this,'deactivate',[e,this.ui(draggable)]);if(draggable)this.element.triggerHandler("dropdeactivate",[e,this.ui(draggable)],this.options.deactivate);}});$.extend($.ui.droppable,{defaults:{disabled:false,tolerance:'intersect'}});$.ui.intersect=function(draggable,droppable,toleranceMode){if(!droppable.offset)return false;var x1=(draggable.positionAbs||draggable.position.absolute).left,x2=x1+draggable.helperProportions.width,y1=(draggable.positionAbs||draggable.position.absolute).top,y2=y1+draggable.helperProportions.height;var l=droppable.offset.left,r=l+droppable.proportions.width,t=droppable.offset.top,b=t+droppable.proportions.height;switch(toleranceMode){case'fit':return(l<x1&&x2<r&&t<y1&&y2<b);break;case'intersect':return(l<x1+(draggable.helperProportions.width/2)&&x2-(draggable.helperProportions.width/2)<r&&t<y1+(draggable.helperProportions.height/2)&&y2-(draggable.helperProportions.height/2)<b);break;case'pointer':return(l<((draggable.positionAbs||draggable.position.absolute).left+(draggable.clickOffset||draggable.offset.click).left)&&((draggable.positionAbs||draggable.position.absolute).left+(draggable.clickOffset||draggable.offset.click).left)<r&&t<((draggable.positionAbs||draggable.position.absolute).top+(draggable.clickOffset||draggable.offset.click).top)&&((draggable.positionAbs||draggable.position.absolute).top+(draggable.clickOffset||draggable.offset.click).top)<b);break;case'touch':return((y1>=t&&y1<=b)||(y2>=t&&y2<=b)||(y1<t&&y2>b))&&((x1>=l&&x1<=r)||(x2>=l&&x2<=r)||(x1<l&&x2>r));break;default:return false;break;}};$.ui.ddmanager={current:null,droppables:[],prepareOffsets:function(t,e){var m=$.ui.ddmanager.droppables;var type=e?e.type:null;for(var i=0;i<m.length;i++){if(m[i].options.disabled||(t&&!m[i].options.accept.call(m[i].element,(t.currentItem||t.element))))continue;m[i].visible=m[i].element.css("display")!="none";if(!m[i].visible)continue;m[i].offset=m[i].element.offset();m[i].proportions={width:m[i].element[0].offsetWidth,height:m[i].element[0].offsetHeight};if(type=="dragstart"||type=="sortactivate")m[i].activate.call(m[i],e);}},drop:function(draggable,e){var dropped=false;$.each($.ui.ddmanager.droppables,function(){if(!this.options)return;if(!this.options.disabled&&this.visible&&$.ui.intersect(draggable,this,this.options.tolerance))
dropped=this.drop.call(this,e);if(!this.options.disabled&&this.visible&&this.options.accept.call(this.element,(draggable.currentItem||draggable.element))){this.isout=1;this.isover=0;this.deactivate.call(this,e);}});return dropped;},drag:function(draggable,e){$.ui.ddmanager.prepareOffsets(draggable,e);$.each($.ui.ddmanager.droppables,function(){if(this.options.disabled||this.greedyChild||!this.visible)return;var intersects=$.ui.intersect(draggable,this,this.options.tolerance);var c=!intersects&&this.isover==1?'isout':(intersects&&this.isover==0?'isover':null);if(!c)return;var parentInstance;if(this.options.greedy){var parent=this.element.parents(':data(droppable):eq(0)');if(parent.length){parentInstance=$.data(parent[0],'droppable');parentInstance.greedyChild=(c=='isover'?1:0);}}
if(parentInstance&&c=='isover'){parentInstance['isover']=0;parentInstance['isout']=1;parentInstance.out.call(parentInstance,e);}
this[c]=1;this[c=='isout'?'isover':'isout']=0;this[c=="isover"?"over":"out"].call(this,e);if(parentInstance&&c=='isout'){parentInstance['isout']=0;parentInstance['isover']=1;parentInstance.over.call(parentInstance,e);}});}};$.ui.plugin.add("droppable","activeClass",{activate:function(e,ui){$(this).addClass(ui.options.activeClass);},deactivate:function(e,ui){$(this).removeClass(ui.options.activeClass);},drop:function(e,ui){$(this).removeClass(ui.options.activeClass);}});$.ui.plugin.add("droppable","hoverClass",{over:function(e,ui){$(this).addClass(ui.options.hoverClass);},out:function(e,ui){$(this).removeClass(ui.options.hoverClass);},drop:function(e,ui){$(this).removeClass(ui.options.hoverClass);}});})(jQuery);(function($){$.widget("ui.resizable",$.extend({},$.ui.mouse,{init:function(){var self=this,o=this.options;var elpos=this.element.css('position');this.originalElement=this.element;this.element.addClass("ui-resizable").css({position:/static/.test(elpos)?'relative':elpos});$.extend(o,{_aspectRatio:!!(o.aspectRatio),helper:o.helper||o.ghost||o.animate?o.helper||'proxy':null,knobHandles:o.knobHandles===true?'ui-resizable-knob-handle':o.knobHandles});var aBorder='1px solid #DEDEDE';o.defaultTheme={'ui-resizable':{display:'block'},'ui-resizable-handle':{position:'absolute',background:'#F2F2F2',fontSize:'0.1px'},'ui-resizable-n':{cursor:'n-resize',height:'4px',left:'0px',right:'0px',borderTop:aBorder},'ui-resizable-s':{cursor:'s-resize',height:'4px',left:'0px',right:'0px',borderBottom:aBorder},'ui-resizable-e':{cursor:'e-resize',width:'4px',top:'0px',bottom:'0px',borderRight:aBorder},'ui-resizable-w':{cursor:'w-resize',width:'4px',top:'0px',bottom:'0px',borderLeft:aBorder},'ui-resizable-se':{cursor:'se-resize',width:'4px',height:'4px',borderRight:aBorder,borderBottom:aBorder},'ui-resizable-sw':{cursor:'sw-resize',width:'4px',height:'4px',borderBottom:aBorder,borderLeft:aBorder},'ui-resizable-ne':{cursor:'ne-resize',width:'4px',height:'4px',borderRight:aBorder,borderTop:aBorder},'ui-resizable-nw':{cursor:'nw-resize',width:'4px',height:'4px',borderLeft:aBorder,borderTop:aBorder}};o.knobTheme={'ui-resizable-handle':{background:'#F2F2F2',border:'1px solid #808080',height:'8px',width:'8px'},'ui-resizable-n':{cursor:'n-resize',top:'0px',left:'45%'},'ui-resizable-s':{cursor:'s-resize',bottom:'0px',left:'45%'},'ui-resizable-e':{cursor:'e-resize',right:'0px',top:'45%'},'ui-resizable-w':{cursor:'w-resize',left:'0px',top:'45%'},'ui-resizable-se':{cursor:'se-resize',right:'0px',bottom:'0px'},'ui-resizable-sw':{cursor:'sw-resize',left:'0px',bottom:'0px'},'ui-resizable-nw':{cursor:'nw-resize',left:'0px',top:'0px'},'ui-resizable-ne':{cursor:'ne-resize',right:'0px',top:'0px'}};o._nodeName=this.element[0].nodeName;if(o._nodeName.match(/canvas|textarea|input|select|button|img/i)){var el=this.element;if(/relative/.test(el.css('position'))&&$.browser.opera)
el.css({position:'relative',top:'auto',left:'auto'});el.wrap($('<div class="ui-wrapper" style="overflow: hidden;"></div>').css({position:el.css('position'),width:el.outerWidth(),height:el.outerHeight(),top:el.css('top'),left:el.css('left')}));var oel=this.element;this.element=this.element.parent();this.element.data('resizable',this);this.element.css({marginLeft:oel.css("marginLeft"),marginTop:oel.css("marginTop"),marginRight:oel.css("marginRight"),marginBottom:oel.css("marginBottom")});oel.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0});if($.browser.safari&&o.preventDefault)oel.css('resize','none');o.proportionallyResize=oel.css({position:'static',zoom:1,display:'block'});this.element.css({margin:oel.css('margin')});this._proportionallyResize();}
if(!o.handles)o.handles=!$('.ui-resizable-handle',this.element).length?"e,s,se":{n:'.ui-resizable-n',e:'.ui-resizable-e',s:'.ui-resizable-s',w:'.ui-resizable-w',se:'.ui-resizable-se',sw:'.ui-resizable-sw',ne:'.ui-resizable-ne',nw:'.ui-resizable-nw'};if(o.handles.constructor==String){o.zIndex=o.zIndex||1000;if(o.handles=='all')o.handles='n,e,s,w,se,sw,ne,nw';var n=o.handles.split(",");o.handles={};var insertionsDefault={handle:'position: absolute; display: none; overflow:hidden;',n:'top: 0pt; width:100%;',e:'right: 0pt; height:100%;',s:'bottom: 0pt; width:100%;',w:'left: 0pt; height:100%;',se:'bottom: 0pt; right: 0px;',sw:'bottom: 0pt; left: 0px;',ne:'top: 0pt; right: 0px;',nw:'top: 0pt; left: 0px;'};for(var i=0;i<n.length;i++){var handle=$.trim(n[i]),dt=o.defaultTheme,hname='ui-resizable-'+handle,loadDefault=!$.ui.css(hname)&&!o.knobHandles,userKnobClass=$.ui.css('ui-resizable-knob-handle'),allDefTheme=$.extend(dt[hname],dt['ui-resizable-handle']),allKnobTheme=$.extend(o.knobTheme[hname],!userKnobClass?o.knobTheme['ui-resizable-handle']:{});var applyZIndex=/sw|se|ne|nw/.test(handle)?{zIndex:++o.zIndex}:{};var defCss=(loadDefault?insertionsDefault[handle]:''),axis=$(['<div class="ui-resizable-handle ',hname,'" style="',defCss,insertionsDefault.handle,'"></div>'].join('')).css(applyZIndex);o.handles[handle]='.ui-resizable-'+handle;this.element.append(axis.css(loadDefault?allDefTheme:{}).css(o.knobHandles?allKnobTheme:{}).addClass(o.knobHandles?'ui-resizable-knob-handle':'').addClass(o.knobHandles));}
if(o.knobHandles)this.element.addClass('ui-resizable-knob').css(!$.ui.css('ui-resizable-knob')?{}:{});}
this._renderAxis=function(target){target=target||this.element;for(var i in o.handles){if(o.handles[i].constructor==String)
o.handles[i]=$(o.handles[i],this.element).show();if(o.transparent)
o.handles[i].css({opacity:0});if(this.element.is('.ui-wrapper')&&o._nodeName.match(/textarea|input|select|button/i)){var axis=$(o.handles[i],this.element),padWrapper=0;padWrapper=/sw|ne|nw|se|n|s/.test(i)?axis.outerHeight():axis.outerWidth();var padPos=['padding',/ne|nw|n/.test(i)?'Top':/se|sw|s/.test(i)?'Bottom':/^e$/.test(i)?'Right':'Left'].join("");if(!o.transparent)
target.css(padPos,padWrapper);this._proportionallyResize();}
if(!$(o.handles[i]).length)continue;}};this._renderAxis(this.element);o._handles=$('.ui-resizable-handle',self.element);if(o.disableSelection)
o._handles.each(function(i,e){$.ui.disableSelection(e);});o._handles.mouseover(function(){if(!o.resizing){if(this.className)
var axis=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i);self.axis=o.axis=axis&&axis[1]?axis[1]:'se';}});if(o.autoHide){o._handles.hide();$(self.element).addClass("ui-resizable-autohide").hover(function(){$(this).removeClass("ui-resizable-autohide");o._handles.show();},function(){if(!o.resizing){$(this).addClass("ui-resizable-autohide");o._handles.hide();}});}
this.mouseInit();},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,options:this.options,originalSize:this.originalSize,originalPosition:this.originalPosition};},propagate:function(n,e){$.ui.plugin.call(this,n,[e,this.ui()]);if(n!="resize")this.element.triggerHandler(["resize",n].join(""),[e,this.ui()],this.options[n]);},destroy:function(){var el=this.element,wrapped=el.children(".ui-resizable").get(0);this.mouseDestroy();var _destroy=function(exp){$(exp).removeClass("ui-resizable ui-resizable-disabled").removeData("resizable").unbind(".resizable").find('.ui-resizable-handle').remove();};_destroy(el);if(el.is('.ui-wrapper')&&wrapped){el.parent().append($(wrapped).css({position:el.css('position'),width:el.outerWidth(),height:el.outerHeight(),top:el.css('top'),left:el.css('left')})).end().remove();_destroy(wrapped);}},mouseStart:function(e){if(this.options.disabled)return false;var handle=false;for(var i in this.options.handles){if($(this.options.handles[i])[0]==e.target)handle=true;}
if(!handle)return false;var o=this.options,iniPos=this.element.position(),el=this.element,num=function(v){return parseInt(v,10)||0;},ie6=$.browser.msie&&$.browser.version<7;o.resizing=true;o.documentScroll={top:$(document).scrollTop(),left:$(document).scrollLeft()};if(el.is('.ui-draggable')||(/absolute/).test(el.css('position'))){var sOffset=$.browser.msie&&!o.containment&&(/absolute/).test(el.css('position'))&&!(/relative/).test(el.parent().css('position'));var dscrollt=sOffset?o.documentScroll.top:0,dscrolll=sOffset?o.documentScroll.left:0;el.css({position:'absolute',top:(iniPos.top+dscrollt),left:(iniPos.left+dscrolll)});}
if($.browser.opera&&/relative/.test(el.css('position')))
el.css({position:'relative',top:'auto',left:'auto'});this._renderProxy();var curleft=num(this.helper.css('left')),curtop=num(this.helper.css('top'));if(o.containment){curleft+=$(o.containment).scrollLeft()||0;curtop+=$(o.containment).scrollTop()||0;}
this.offset=this.helper.offset();this.position={left:curleft,top:curtop};this.size=o.helper||ie6?{width:el.outerWidth(),height:el.outerHeight()}:{width:el.width(),height:el.height()};this.originalSize=o.helper||ie6?{width:el.outerWidth(),height:el.outerHeight()}:{width:el.width(),height:el.height()};this.originalPosition={left:curleft,top:curtop};this.sizeDiff={width:el.outerWidth()-el.width(),height:el.outerHeight()-el.height()};this.originalMousePosition={left:e.pageX,top:e.pageY};o.aspectRatio=(typeof o.aspectRatio=='number')?o.aspectRatio:((this.originalSize.height/this.originalSize.width)||1);if(o.preserveCursor)
$('body').css('cursor',this.axis+'-resize');this.propagate("start",e);return true;},mouseDrag:function(e){var el=this.helper,o=this.options,props={},self=this,smp=this.originalMousePosition,a=this.axis;var dx=(e.pageX-smp.left)||0,dy=(e.pageY-smp.top)||0;var trigger=this._change[a];if(!trigger)return false;var data=trigger.apply(this,[e,dx,dy]),ie6=$.browser.msie&&$.browser.version<7,csdif=this.sizeDiff;if(o._aspectRatio||e.shiftKey)
data=this._updateRatio(data,e);data=this._respectSize(data,e);this.propagate("resize",e);el.css({top:this.position.top+"px",left:this.position.left+"px",width:this.size.width+"px",height:this.size.height+"px"});if(!o.helper&&o.proportionallyResize)
this._proportionallyResize();this._updateCache(data);this.element.triggerHandler("resize",[e,this.ui()],this.options["resize"]);return false;},mouseStop:function(e){this.options.resizing=false;var o=this.options,num=function(v){return parseInt(v,10)||0;},self=this;if(o.helper){var pr=o.proportionallyResize,ista=pr&&(/textarea/i).test(pr.get(0).nodeName),soffseth=ista&&$.ui.hasScroll(pr.get(0),'left')?0:self.sizeDiff.height,soffsetw=ista?0:self.sizeDiff.width;var s={width:(self.size.width-soffsetw),height:(self.size.height-soffseth)},left=(parseInt(self.element.css('left'),10)+(self.position.left-self.originalPosition.left))||null,top=(parseInt(self.element.css('top'),10)+(self.position.top-self.originalPosition.top))||null;if(!o.animate)
this.element.css($.extend(s,{top:top,left:left}));if(o.helper&&!o.animate)this._proportionallyResize();}
if(o.preserveCursor)
$('body').css('cursor','auto');this.propagate("stop",e);if(o.helper)this.helper.remove();return false;},_updateCache:function(data){var o=this.options;this.offset=this.helper.offset();if(data.left)this.position.left=data.left;if(data.top)this.position.top=data.top;if(data.height)this.size.height=data.height;if(data.width)this.size.width=data.width;},_updateRatio:function(data,e){var o=this.options,cpos=this.position,csize=this.size,a=this.axis;if(data.height)data.width=(csize.height/o.aspectRatio);else if(data.width)data.height=(csize.width*o.aspectRatio);if(a=='sw'){data.left=cpos.left+(csize.width-data.width);data.top=null;}
if(a=='nw'){data.top=cpos.top+(csize.height-data.height);data.left=cpos.left+(csize.width-data.width);}
return data;},_respectSize:function(data,e){var el=this.helper,o=this.options,pRatio=o._aspectRatio||e.shiftKey,a=this.axis,ismaxw=data.width&&o.maxWidth&&o.maxWidth<data.width,ismaxh=data.height&&o.maxHeight&&o.maxHeight<data.height,isminw=data.width&&o.minWidth&&o.minWidth>data.width,isminh=data.height&&o.minHeight&&o.minHeight>data.height;if(isminw)data.width=o.minWidth;if(isminh)data.height=o.minHeight;if(ismaxw)data.width=o.maxWidth;if(ismaxh)data.height=o.maxHeight;var dw=this.originalPosition.left+this.originalSize.width,dh=this.position.top+this.size.height;var cw=/sw|nw|w/.test(a),ch=/nw|ne|n/.test(a);if(isminw&&cw)data.left=dw-o.minWidth;if(ismaxw&&cw)data.left=dw-o.maxWidth;if(isminh&&ch)data.top=dh-o.minHeight;if(ismaxh&&ch)data.top=dh-o.maxHeight;var isNotwh=!data.width&&!data.height;if(isNotwh&&!data.left&&data.top)data.top=null;else if(isNotwh&&!data.top&&data.left)data.left=null;return data;},_proportionallyResize:function(){var o=this.options;if(!o.proportionallyResize)return;var prel=o.proportionallyResize,el=this.helper||this.element;if(!o.borderDif){var b=[prel.css('borderTopWidth'),prel.css('borderRightWidth'),prel.css('borderBottomWidth'),prel.css('borderLeftWidth')],p=[prel.css('paddingTop'),prel.css('paddingRight'),prel.css('paddingBottom'),prel.css('paddingLeft')];o.borderDif=$.map(b,function(v,i){var border=parseInt(v,10)||0,padding=parseInt(p[i],10)||0;return border+padding;});}
prel.css({height:(el.height()-o.borderDif[0]-o.borderDif[2])+"px",width:(el.width()-o.borderDif[1]-o.borderDif[3])+"px"});},_renderProxy:function(){var el=this.element,o=this.options;this.elementOffset=el.offset();if(o.helper){this.helper=this.helper||$('<div style="overflow:hidden;"></div>');var ie6=$.browser.msie&&$.browser.version<7,ie6offset=(ie6?1:0),pxyoffset=(ie6?2:-1);this.helper.addClass(o.helper).css({width:el.outerWidth()+pxyoffset,height:el.outerHeight()+pxyoffset,position:'absolute',left:this.elementOffset.left-ie6offset+'px',top:this.elementOffset.top-ie6offset+'px',zIndex:++o.zIndex});this.helper.appendTo("body");if(o.disableSelection)
$.ui.disableSelection(this.helper.get(0));}else{this.helper=el;}},_change:{e:function(e,dx,dy){return{width:this.originalSize.width+dx};},w:function(e,dx,dy){var o=this.options,cs=this.originalSize,sp=this.originalPosition;return{left:sp.left+dx,width:cs.width-dx};},n:function(e,dx,dy){var o=this.options,cs=this.originalSize,sp=this.originalPosition;return{top:sp.top+dy,height:cs.height-dy};},s:function(e,dx,dy){return{height:this.originalSize.height+dy};},se:function(e,dx,dy){return $.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[e,dx,dy]));},sw:function(e,dx,dy){return $.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[e,dx,dy]));},ne:function(e,dx,dy){return $.extend(this._change.n.apply(this,arguments),this._change.e.apply(this,[e,dx,dy]));},nw:function(e,dx,dy){return $.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[e,dx,dy]));}}}));$.extend($.ui.resizable,{defaults:{cancel:":input",distance:1,delay:0,preventDefault:true,transparent:false,minWidth:10,minHeight:10,aspectRatio:false,disableSelection:true,preserveCursor:true,autoHide:false,knobHandles:false}});$.ui.plugin.add("resizable","containment",{start:function(e,ui){var o=ui.options,self=$(this).data("resizable"),el=self.element;var oc=o.containment,ce=(oc instanceof $)?oc.get(0):(/parent/.test(oc))?el.parent().get(0):oc;if(!ce)return;self.containerElement=$(ce);if(/document/.test(oc)||oc==document){self.containerOffset={left:0,top:0};self.containerPosition={left:0,top:0};self.parentData={element:$(document),left:0,top:0,width:$(document).width(),height:$(document).height()||document.body.parentNode.scrollHeight};}
else{self.containerOffset=$(ce).offset();self.containerPosition=$(ce).position();self.containerSize={height:$(ce).innerHeight(),width:$(ce).innerWidth()};var co=self.containerOffset,ch=self.containerSize.height,cw=self.containerSize.width,width=($.ui.hasScroll(ce,"left")?ce.scrollWidth:cw),height=($.ui.hasScroll(ce)?ce.scrollHeight:ch);self.parentData={element:ce,left:co.left,top:co.top,width:width,height:height};}},resize:function(e,ui){var o=ui.options,self=$(this).data("resizable"),ps=self.containerSize,co=self.containerOffset,cs=self.size,cp=self.position,pRatio=o._aspectRatio||e.shiftKey,cop={top:0,left:0},ce=self.containerElement;if(ce[0]!=document&&/static/.test(ce.css('position')))
cop=self.containerPosition;if(cp.left<(o.helper?co.left:cop.left)){self.size.width=self.size.width+(o.helper?(self.position.left-co.left):(self.position.left-cop.left));if(pRatio)self.size.height=self.size.width*o.aspectRatio;self.position.left=o.helper?co.left:cop.left;}
if(cp.top<(o.helper?co.top:0)){self.size.height=self.size.height+(o.helper?(self.position.top-co.top):self.position.top);if(pRatio)self.size.width=self.size.height/o.aspectRatio;self.position.top=o.helper?co.top:0;}
var woset=(o.helper?self.offset.left-co.left:(self.position.left-cop.left))+self.sizeDiff.width,hoset=(o.helper?self.offset.top-co.top:self.position.top)+self.sizeDiff.height;if(woset+self.size.width>=self.parentData.width){self.size.width=self.parentData.width-woset;if(pRatio)self.size.height=self.size.width*o.aspectRatio;}
if(hoset+self.size.height>=self.parentData.height){self.size.height=self.parentData.height-hoset;if(pRatio)self.size.width=self.size.height/o.aspectRatio;}},stop:function(e,ui){var o=ui.options,self=$(this).data("resizable"),cp=self.position,co=self.containerOffset,cop=self.containerPosition,ce=self.containerElement;var helper=$(self.helper),ho=helper.offset(),w=helper.innerWidth(),h=helper.innerHeight();if(o.helper&&!o.animate&&/relative/.test(ce.css('position')))
$(this).css({left:(ho.left-co.left),top:(ho.top-co.top),width:w,height:h});if(o.helper&&!o.animate&&/static/.test(ce.css('position')))
$(this).css({left:cop.left+(ho.left-co.left),top:cop.top+(ho.top-co.top),width:w,height:h});}});$.ui.plugin.add("resizable","grid",{resize:function(e,ui){var o=ui.options,self=$(this).data("resizable"),cs=self.size,os=self.originalSize,op=self.originalPosition,a=self.axis,ratio=o._aspectRatio||e.shiftKey;o.grid=typeof o.grid=="number"?[o.grid,o.grid]:o.grid;var ox=Math.round((cs.width-os.width)/(o.grid[0]||1))*(o.grid[0]||1),oy=Math.round((cs.height-os.height)/(o.grid[1]||1))*(o.grid[1]||1);if(/^(se|s|e)$/.test(a)){self.size.width=os.width+ox;self.size.height=os.height+oy;}
else if(/^(ne)$/.test(a)){self.size.width=os.width+ox;self.size.height=os.height+oy;self.position.top=op.top-oy;}
else if(/^(sw)$/.test(a)){self.size.width=os.width+ox;self.size.height=os.height+oy;self.position.left=op.left-ox;}
else{self.size.width=os.width+ox;self.size.height=os.height+oy;self.position.top=op.top-oy;self.position.left=op.left-ox;}}});$.ui.plugin.add("resizable","animate",{stop:function(e,ui){var o=ui.options,self=$(this).data("resizable");var pr=o.proportionallyResize,ista=pr&&(/textarea/i).test(pr.get(0).nodeName),soffseth=ista&&$.ui.hasScroll(pr.get(0),'left')?0:self.sizeDiff.height,soffsetw=ista?0:self.sizeDiff.width;var style={width:(self.size.width-soffsetw),height:(self.size.height-soffseth)},left=(parseInt(self.element.css('left'),10)+(self.position.left-self.originalPosition.left))||null,top=(parseInt(self.element.css('top'),10)+(self.position.top-self.originalPosition.top))||null;self.element.animate($.extend(style,top&&left?{top:top,left:left}:{}),{duration:o.animateDuration||"slow",easing:o.animateEasing||"swing",step:function(){var data={width:parseInt(self.element.css('width'),10),height:parseInt(self.element.css('height'),10),top:parseInt(self.element.css('top'),10),left:parseInt(self.element.css('left'),10)};if(pr)pr.css({width:data.width,height:data.height});self._updateCache(data);self.propagate("animate",e);}});}});$.ui.plugin.add("resizable","ghost",{start:function(e,ui){var o=ui.options,self=$(this).data("resizable"),pr=o.proportionallyResize,cs=self.size;if(!pr)self.ghost=self.element.clone();else self.ghost=pr.clone();self.ghost.css({opacity:.25,display:'block',position:'relative',height:cs.height,width:cs.width,margin:0,left:0,top:0}).addClass('ui-resizable-ghost').addClass(typeof o.ghost=='string'?o.ghost:'');self.ghost.appendTo(self.helper);},resize:function(e,ui){var o=ui.options,self=$(this).data("resizable"),pr=o.proportionallyResize;if(self.ghost)self.ghost.css({position:'relative',height:self.size.height,width:self.size.width});},stop:function(e,ui){var o=ui.options,self=$(this).data("resizable"),pr=o.proportionallyResize;if(self.ghost&&self.helper)self.helper.get(0).removeChild(self.ghost.get(0));}});$.ui.plugin.add("resizable","alsoResize",{start:function(e,ui){var o=ui.options,self=$(this).data("resizable"),_store=function(exp){$(exp).each(function(){$(this).data("resizable-alsoresize",{width:parseInt($(this).width(),10),height:parseInt($(this).height(),10),left:parseInt($(this).css('left'),10),top:parseInt($(this).css('top'),10)});});};if(typeof(o.alsoResize)=='object'){if(o.alsoResize.length){o.alsoResize=o.alsoResize[0];_store(o.alsoResize);}
else{$.each(o.alsoResize,function(exp,c){_store(exp);});}}else{_store(o.alsoResize);}},resize:function(e,ui){var o=ui.options,self=$(this).data("resizable"),os=self.originalSize,op=self.originalPosition;var delta={height:(self.size.height-os.height)||0,width:(self.size.width-os.width)||0,top:(self.position.top-op.top)||0,left:(self.position.left-op.left)||0},_alsoResize=function(exp,c){$(exp).each(function(){var start=$(this).data("resizable-alsoresize"),style={},css=c&&c.length?c:['width','height','top','left'];$.each(css||['width','height','top','left'],function(i,prop){var sum=(start[prop]||0)+(delta[prop]||0);if(sum&&sum>=0)
style[prop]=sum||null;});$(this).css(style);});};if(typeof(o.alsoResize)=='object'){$.each(o.alsoResize,function(exp,c){_alsoResize(exp,c);});}else{_alsoResize(o.alsoResize);}},stop:function(e,ui){$(this).removeData("resizable-alsoresize-start");}});})(jQuery);(function($){function contains(a,b){var safari2=$.browser.safari&&$.browser.version<522;if(a.contains&&!safari2){return a.contains(b);}
if(a.compareDocumentPosition)
return!!(a.compareDocumentPosition(b)&16);while(b=b.parentNode)
if(b==a)return true;return false;};$.widget("ui.sortable",$.extend({},$.ui.mouse,{init:function(){var o=this.options;this.containerCache={};this.element.addClass("ui-sortable");this.refresh();this.floating=this.items.length?(/left|right/).test(this.items[0].item.css('float')):false;if(!(/(relative|absolute|fixed)/).test(this.element.css('position')))this.element.css('position','relative');this.offset=this.element.offset();this.mouseInit();},plugins:{},ui:function(inst){return{helper:(inst||this)["helper"],placeholder:(inst||this)["placeholder"]||$([]),position:(inst||this)["position"],absolutePosition:(inst||this)["positionAbs"],options:this.options,element:this.element,item:(inst||this)["currentItem"],sender:inst?inst.element:null};},propagate:function(n,e,inst,noPropagation){$.ui.plugin.call(this,n,[e,this.ui(inst)]);if(!noPropagation)this.element.triggerHandler(n=="sort"?n:"sort"+n,[e,this.ui(inst)],this.options[n]);},serialize:function(o){var items=this.getItemsAsjQuery(o&&o.connected);var str=[];o=o||{};$(items).each(function(){var res=($(this.item||this).attr(o.attribute||'id')||'').match(o.expression||(/(.+)[-=_](.+)/));if(res)str.push((o.key||res[1])+'[]='+(o.key&&o.expression?res[1]:res[2]));});return str.join('&');},toArray:function(attr){var items=this.getItemsAsjQuery(o&&o.connected);var ret=[];items.each(function(){ret.push($(this).attr(attr||'id'));});return ret;},intersectsWith:function(item){var x1=this.positionAbs.left,x2=x1+this.helperProportions.width,y1=this.positionAbs.top,y2=y1+this.helperProportions.height;var l=item.left,r=l+item.width,t=item.top,b=t+item.height;var dyClick=this.offset.click.top,dxClick=this.offset.click.left;var isOverElement=(y1+dyClick)>t&&(y1+dyClick)<b&&(x1+dxClick)>l&&(x1+dxClick)<r;if(this.options.tolerance=="pointer"||this.options.forcePointerForContainers||(this.options.tolerance=="guess"&&this.helperProportions[this.floating?'width':'height']>item[this.floating?'width':'height'])){return isOverElement;}else{return(l<x1+(this.helperProportions.width/2)&&x2-(this.helperProportions.width/2)<r&&t<y1+(this.helperProportions.height/2)&&y2-(this.helperProportions.height/2)<b);}},intersectsWithEdge:function(item){var x1=this.positionAbs.left,x2=x1+this.helperProportions.width,y1=this.positionAbs.top,y2=y1+this.helperProportions.height;var l=item.left,r=l+item.width,t=item.top,b=t+item.height;var dyClick=this.offset.click.top,dxClick=this.offset.click.left;var isOverElement=(y1+dyClick)>t&&(y1+dyClick)<b&&(x1+dxClick)>l&&(x1+dxClick)<r;if(this.options.tolerance=="pointer"||(this.options.tolerance=="guess"&&this.helperProportions[this.floating?'width':'height']>item[this.floating?'width':'height'])){if(!isOverElement)return false;if(this.floating){if((x1+dxClick)>l&&(x1+dxClick)<l+item.width/2)return 2;if((x1+dxClick)>l+item.width/2&&(x1+dxClick)<r)return 1;}else{var height=item.height,helperHeight=this.helperProportions.height;var direction=y1-this.updateOriginalPosition.top<0?2:1;if(direction==1&&(y1+dyClick)<t+height/2){return 2;}
else if(direction==2&&(y1+dyClick)>t+height/2){return 1;}}}else{if(!(l<x1+(this.helperProportions.width/2)&&x2-(this.helperProportions.width/2)<r&&t<y1+(this.helperProportions.height/2)&&y2-(this.helperProportions.height/2)<b))return false;if(this.floating){if(x2>l&&x1<l)return 2;if(x1<r&&x2>r)return 1;}else{if(y2>t&&y1<t)return 1;if(y1<b&&y2>b)return 2;}}
return false;},refresh:function(){this.refreshItems();this.refreshPositions();},getItemsAsjQuery:function(connected){var self=this;var items=[];var queries=[];if(this.options.connectWith&&connected){for(var i=this.options.connectWith.length-1;i>=0;i--){var cur=$(this.options.connectWith[i]);for(var j=cur.length-1;j>=0;j--){var inst=$.data(cur[j],'sortable');if(inst&&inst!=this&&!inst.options.disabled){queries.push([$.isFunction(inst.options.items)?inst.options.items.call(inst.element):$(inst.options.items,inst.element).not(".ui-sortable-helper"),inst]);}};};}
queries.push([$.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):$(this.options.items,this.element).not(".ui-sortable-helper"),this]);for(var i=queries.length-1;i>=0;i--){queries[i][0].each(function(){items.push(this);});};return $(items);},refreshItems:function(){this.items=[];this.containers=[this];var items=this.items;var self=this;var queries=[[$.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):$(this.options.items,this.element),this]];if(this.options.connectWith){for(var i=this.options.connectWith.length-1;i>=0;i--){var cur=$(this.options.connectWith[i]);for(var j=cur.length-1;j>=0;j--){var inst=$.data(cur[j],'sortable');if(inst&&inst!=this&&!inst.options.disabled){queries.push([$.isFunction(inst.options.items)?inst.options.items.call(inst.element):$(inst.options.items,inst.element),inst]);this.containers.push(inst);}};};}
for(var i=queries.length-1;i>=0;i--){queries[i][0].each(function(){$.data(this,'sortable-item',queries[i][1]);items.push({item:$(this),instance:queries[i][1],width:0,height:0,left:0,top:0});});};},refreshPositions:function(fast){if(this.offsetParent){var po=this.offsetParent.offset();this.offset.parent={top:po.top+this.offsetParentBorders.top,left:po.left+this.offsetParentBorders.left};}
for(var i=this.items.length-1;i>=0;i--){if(this.items[i].instance!=this.currentContainer&&this.currentContainer&&this.items[i].item[0]!=this.currentItem[0])
continue;var t=this.options.toleranceElement?$(this.options.toleranceElement,this.items[i].item):this.items[i].item;if(!fast){this.items[i].width=t[0].offsetWidth;this.items[i].height=t[0].offsetHeight;}
var p=t.offset();this.items[i].left=p.left;this.items[i].top=p.top;};if(this.options.custom&&this.options.custom.refreshContainers){this.options.custom.refreshContainers.call(this);}else{for(var i=this.containers.length-1;i>=0;i--){var p=this.containers[i].element.offset();this.containers[i].containerCache.left=p.left;this.containers[i].containerCache.top=p.top;this.containers[i].containerCache.width=this.containers[i].element.outerWidth();this.containers[i].containerCache.height=this.containers[i].element.outerHeight();};}},destroy:function(){this.element.removeClass("ui-sortable ui-sortable-disabled").removeData("sortable").unbind(".sortable");this.mouseDestroy();for(var i=this.items.length-1;i>=0;i--)
this.items[i].item.removeData("sortable-item");},createPlaceholder:function(that){var self=that||this,o=self.options;if(!o.placeholder||o.placeholder.constructor==String){var className=o.placeholder;o.placeholder={element:function(){var el=$(document.createElement(self.currentItem[0].nodeName)).addClass(className||"ui-sortable-placeholder")[0];if(!className){el.style.visibility="hidden";el.innerHTML=self.currentItem[0].innerHTML;};return el;},update:function(container,p){if(className)return;if(!p.height()){p.height(self.currentItem.innerHeight());};if(!p.width()){p.width(self.currentItem.innerWidth());};}};}
self.placeholder=$(o.placeholder.element.call(self.element,self.currentItem)).appendTo(self.currentItem.parent());self.currentItem.before(self.placeholder);o.placeholder.update(self,self.placeholder);},contactContainers:function(e){for(var i=this.containers.length-1;i>=0;i--){if(this.intersectsWith(this.containers[i].containerCache)){if(!this.containers[i].containerCache.over){if(this.currentContainer!=this.containers[i]){var dist=10000;var itemWithLeastDistance=null;var base=this.positionAbs[this.containers[i].floating?'left':'top'];for(var j=this.items.length-1;j>=0;j--){if(!contains(this.containers[i].element[0],this.items[j].item[0]))continue;var cur=this.items[j][this.containers[i].floating?'left':'top'];if(Math.abs(cur-base)<dist){dist=Math.abs(cur-base);itemWithLeastDistance=this.items[j];}}
if(!itemWithLeastDistance&&!this.options.dropOnEmpty)
continue;this.currentContainer=this.containers[i];itemWithLeastDistance?this.options.sortIndicator.call(this,e,itemWithLeastDistance,null,true):this.options.sortIndicator.call(this,e,null,this.containers[i].element,true);this.propagate("change",e);this.containers[i].propagate("change",e,this);this.options.placeholder.update(this.currentContainer,this.placeholder);}
this.containers[i].propagate("over",e,this);this.containers[i].containerCache.over=1;}}else{if(this.containers[i].containerCache.over){this.containers[i].propagate("out",e,this);this.containers[i].containerCache.over=0;}}};},mouseCapture:function(e,overrideHandle){if(this.options.disabled||this.options.type=='static')return false;this.refreshItems();var currentItem=null,self=this,nodes=$(e.target).parents().each(function(){if($.data(this,'sortable-item')==self){currentItem=$(this);return false;}});if($.data(e.target,'sortable-item')==self)currentItem=$(e.target);if(!currentItem)return false;if(this.options.handle&&!overrideHandle){var validHandle=false;$(this.options.handle,currentItem).find("*").andSelf().each(function(){if(this==e.target)validHandle=true;});if(!validHandle)return false;}
this.currentItem=currentItem;return true;},mouseStart:function(e,overrideHandle,noActivation){var o=this.options;this.currentContainer=this;this.refreshPositions();this.helper=typeof o.helper=='function'?$(o.helper.apply(this.element[0],[e,this.currentItem])):(o.helper=="original"?this.currentItem:this.currentItem.clone());if(!this.helper.parents('body').length)$(o.appendTo!='parent'?o.appendTo:this.currentItem[0].parentNode)[0].appendChild(this.helper[0]);this.margins={left:(parseInt(this.currentItem.css("marginLeft"),10)||0),top:(parseInt(this.currentItem.css("marginTop"),10)||0)};this.offset=this.currentItem.offset();this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left};this.offset.click={left:e.pageX-this.offset.left,top:e.pageY-this.offset.top};this.offsetParent=this.helper.offsetParent();var po=this.offsetParent.offset();this.offsetParentBorders={top:(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)};this.offset.parent={top:po.top+this.offsetParentBorders.top,left:po.left+this.offsetParentBorders.left};this.updateOriginalPosition=this.originalPosition=this.generatePosition(e);this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]};this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()};if(o.helper=="original"){this._storedCSS={position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left"),clear:this.currentItem.css("clear")};}
if(o.helper!="original")this.currentItem.hide();this.helper.css({position:'absolute',clear:'both'}).addClass('ui-sortable-helper');this.createPlaceholder();this.propagate("start",e);this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()};if(o.cursorAt){if(o.cursorAt.left!=undefined)this.offset.click.left=o.cursorAt.left;if(o.cursorAt.right!=undefined)this.offset.click.left=this.helperProportions.width-o.cursorAt.right;if(o.cursorAt.top!=undefined)this.offset.click.top=o.cursorAt.top;if(o.cursorAt.bottom!=undefined)this.offset.click.top=this.helperProportions.height-o.cursorAt.bottom;}
if(o.containment){if(o.containment=='parent')o.containment=this.helper[0].parentNode;if(o.containment=='document'||o.containment=='window')this.containment=[0-this.offset.parent.left,0-this.offset.parent.top,$(o.containment=='document'?document:window).width()-this.offset.parent.left-this.helperProportions.width-this.margins.left-(parseInt(this.element.css("marginRight"),10)||0),($(o.containment=='document'?document:window).height()||document.body.parentNode.scrollHeight)-this.offset.parent.top-this.helperProportions.height-this.margins.top-(parseInt(this.element.css("marginBottom"),10)||0)];if(!(/^(document|window|parent)$/).test(o.containment)){var ce=$(o.containment)[0];var co=$(o.containment).offset();this.containment=[co.left+(parseInt($(ce).css("borderLeftWidth"),10)||0)-this.offset.parent.left,co.top+(parseInt($(ce).css("borderTopWidth"),10)||0)-this.offset.parent.top,co.left+Math.max(ce.scrollWidth,ce.offsetWidth)-(parseInt($(ce).css("borderLeftWidth"),10)||0)-this.offset.parent.left-this.helperProportions.width-this.margins.left-(parseInt(this.currentItem.css("marginRight"),10)||0),co.top+Math.max(ce.scrollHeight,ce.offsetHeight)-(parseInt($(ce).css("borderTopWidth"),10)||0)-this.offset.parent.top-this.helperProportions.height-this.margins.top-(parseInt(this.currentItem.css("marginBottom"),10)||0)];}}
if(!noActivation){for(var i=this.containers.length-1;i>=0;i--){this.containers[i].propagate("activate",e,this);}}
if($.ui.ddmanager)$.ui.ddmanager.current=this;if($.ui.ddmanager&&!o.dropBehaviour)$.ui.ddmanager.prepareOffsets(this,e);this.dragging=true;this.mouseDrag(e);return true;},convertPositionTo:function(d,pos){if(!pos)pos=this.position;var mod=d=="absolute"?1:-1;return{top:(pos.top
+this.offset.parent.top*mod
-(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollTop)*mod
+this.margins.top*mod),left:(pos.left
+this.offset.parent.left*mod
-(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollLeft)*mod
+this.margins.left*mod)};},generatePosition:function(e){var o=this.options;var position={top:(e.pageY
-this.offset.click.top
-this.offset.parent.top
+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollTop)),left:(e.pageX
-this.offset.click.left
-this.offset.parent.left
+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollLeft))};if(!this.originalPosition)return position;if(this.containment){if(position.left<this.containment[0])position.left=this.containment[0];if(position.top<this.containment[1])position.top=this.containment[1];if(position.left>this.containment[2])position.left=this.containment[2];if(position.top>this.containment[3])position.top=this.containment[3];}
if(o.grid){var top=this.originalPosition.top+Math.round((position.top-this.originalPosition.top)/o.grid[1])*o.grid[1];position.top=this.containment?(!(top<this.containment[1]||top>this.containment[3])?top:(!(top<this.containment[1])?top-o.grid[1]:top+o.grid[1])):top;var left=this.originalPosition.left+Math.round((position.left-this.originalPosition.left)/o.grid[0])*o.grid[0];position.left=this.containment?(!(left<this.containment[0]||left>this.containment[2])?left:(!(left<this.containment[0])?left-o.grid[0]:left+o.grid[0])):left;}
return position;},mouseDrag:function(e){this.position=this.generatePosition(e);this.positionAbs=this.convertPositionTo("absolute");$.ui.plugin.call(this,"sort",[e,this.ui()]);this.positionAbs=this.convertPositionTo("absolute");this.helper[0].style.left=this.position.left+'px';this.helper[0].style.top=this.position.top+'px';for(var i=this.items.length-1;i>=0;i--){var intersection=this.intersectsWithEdge(this.items[i]);if(!intersection)continue;if(this.items[i].item[0]!=this.currentItem[0]&&this.placeholder[intersection==1?"next":"prev"]()[0]!=this.items[i].item[0]&&!contains(this.placeholder[0],this.items[i].item[0])&&(this.options.type=='semi-dynamic'?!contains(this.element[0],this.items[i].item[0]):true)){this.updateOriginalPosition=this.generatePosition(e);this.direction=intersection==1?"down":"up";this.options.sortIndicator.call(this,e,this.items[i]);this.propagate("change",e);break;}}
this.contactContainers(e);if($.ui.ddmanager)$.ui.ddmanager.drag(this,e);this.element.triggerHandler("sort",[e,this.ui()],this.options["sort"]);return false;},rearrange:function(e,i,a,hardRefresh){a?a[0].appendChild(this.placeholder[0]):i.item[0].parentNode.insertBefore(this.placeholder[0],(this.direction=='down'?i.item[0]:i.item[0].nextSibling));this.counter=this.counter?++this.counter:1;var self=this,counter=this.counter;window.setTimeout(function(){if(counter==self.counter)self.refreshPositions(!hardRefresh);},0);},mouseStop:function(e,noPropagation){if($.ui.ddmanager&&!this.options.dropBehaviour)
$.ui.ddmanager.drop(this,e);if(this.options.revert){var self=this;var cur=self.placeholder.offset();$(this.helper).animate({left:cur.left-this.offset.parent.left-self.margins.left+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollLeft),top:cur.top-this.offset.parent.top-self.margins.top+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollTop)},parseInt(this.options.revert,10)||500,function(){self.clear(e);});}else{this.clear(e,noPropagation);}
return false;},clear:function(e,noPropagation){if(!this._noFinalSort)this.placeholder.before(this.currentItem);this._noFinalSort=null;if(this.options.helper=="original")
this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");else
this.currentItem.show();if(this.domPosition.prev!=this.currentItem.prev().not(".ui-sortable-helper")[0]||this.domPosition.parent!=this.currentItem.parent()[0])this.propagate("update",e,null,noPropagation);if(!contains(this.element[0],this.currentItem[0])){this.propagate("remove",e,null,noPropagation);for(var i=this.containers.length-1;i>=0;i--){if(contains(this.containers[i].element[0],this.currentItem[0])){this.containers[i].propagate("update",e,this,noPropagation);this.containers[i].propagate("receive",e,this,noPropagation);}};};for(var i=this.containers.length-1;i>=0;i--){this.containers[i].propagate("deactivate",e,this,noPropagation);if(this.containers[i].containerCache.over){this.containers[i].propagate("out",e,this);this.containers[i].containerCache.over=0;}}
this.dragging=false;if(this.cancelHelperRemoval){this.propagate("stop",e,null,noPropagation);return false;}
this.propagate("beforeStop",e,null,noPropagation);this.placeholder.remove();if(this.options.helper!="original")this.helper.remove();this.helper=null;this.propagate("stop",e,null,noPropagation);return true;}}));$.extend($.ui.sortable,{getter:"serialize toArray",defaults:{helper:"original",tolerance:"guess",distance:1,delay:0,scroll:true,scrollSensitivity:20,scrollSpeed:20,cancel:":input",items:'> *',zIndex:1000,dropOnEmpty:true,appendTo:"parent",sortIndicator:$.ui.sortable.prototype.rearrange}});$.ui.plugin.add("sortable","cursor",{start:function(e,ui){var t=$('body');if(t.css("cursor"))ui.options._cursor=t.css("cursor");t.css("cursor",ui.options.cursor);},beforeStop:function(e,ui){if(ui.options._cursor)$('body').css("cursor",ui.options._cursor);}});$.ui.plugin.add("sortable","zIndex",{start:function(e,ui){var t=ui.helper;if(t.css("zIndex"))ui.options._zIndex=t.css("zIndex");t.css('zIndex',ui.options.zIndex);},beforeStop:function(e,ui){if(ui.options._zIndex)$(ui.helper).css('zIndex',ui.options._zIndex);}});$.ui.plugin.add("sortable","opacity",{start:function(e,ui){var t=ui.helper;if(t.css("opacity"))ui.options._opacity=t.css("opacity");t.css('opacity',ui.options.opacity);},beforeStop:function(e,ui){if(ui.options._opacity)$(ui.helper).css('opacity',ui.options._opacity);}});$.ui.plugin.add("sortable","scroll",{start:function(e,ui){var o=ui.options;var i=$(this).data("sortable");i.overflowY=function(el){do{if(/auto|scroll/.test(el.css('overflow'))||(/auto|scroll/).test(el.css('overflow-y')))return el;el=el.parent();}while(el[0].parentNode);return $(document);}(i.currentItem);i.overflowX=function(el){do{if(/auto|scroll/.test(el.css('overflow'))||(/auto|scroll/).test(el.css('overflow-x')))return el;el=el.parent();}while(el[0].parentNode);return $(document);}(i.currentItem);if(i.overflowY[0]!=document&&i.overflowY[0].tagName!='HTML')i.overflowYOffset=i.overflowY.offset();if(i.overflowX[0]!=document&&i.overflowX[0].tagName!='HTML')i.overflowXOffset=i.overflowX.offset();},sort:function(e,ui){var o=ui.options;var i=$(this).data("sortable");if(i.overflowY[0]!=document&&i.overflowY[0].tagName!='HTML'){if((i.overflowYOffset.top+i.overflowY[0].offsetHeight)-e.pageY<o.scrollSensitivity)
i.overflowY[0].scrollTop=i.overflowY[0].scrollTop+o.scrollSpeed;if(e.pageY-i.overflowYOffset.top<o.scrollSensitivity)
i.overflowY[0].scrollTop=i.overflowY[0].scrollTop-o.scrollSpeed;}else{if(e.pageY-$(document).scrollTop()<o.scrollSensitivity)
$(document).scrollTop($(document).scrollTop()-o.scrollSpeed);if($(window).height()-(e.pageY-$(document).scrollTop())<o.scrollSensitivity)
$(document).scrollTop($(document).scrollTop()+o.scrollSpeed);}
if(i.overflowX[0]!=document&&i.overflowX[0].tagName!='HTML'){if((i.overflowXOffset.left+i.overflowX[0].offsetWidth)-e.pageX<o.scrollSensitivity)
i.overflowX[0].scrollLeft=i.overflowX[0].scrollLeft+o.scrollSpeed;if(e.pageX-i.overflowXOffset.left<o.scrollSensitivity)
i.overflowX[0].scrollLeft=i.overflowX[0].scrollLeft-o.scrollSpeed;}else{if(e.pageX-$(document).scrollLeft()<o.scrollSensitivity)
$(document).scrollLeft($(document).scrollLeft()-o.scrollSpeed);if($(window).width()-(e.pageX-$(document).scrollLeft())<o.scrollSensitivity)
$(document).scrollLeft($(document).scrollLeft()+o.scrollSpeed);}}});$.ui.plugin.add("sortable","axis",{sort:function(e,ui){var i=$(this).data("sortable");if(ui.options.axis=="y")i.position.left=i.originalPosition.left;if(ui.options.axis=="x")i.position.top=i.originalPosition.top;}});})(jQuery);$.widget("ui.tree",{init:function(){var self=this;var blah=this;this.element.sortable({items:this.options.sortOn,placeholder:"sort-placeholder",sortable:null,start:function(e,ui){$(this).data("sortable").placeholder.hide();$(this).data("sortable").refreshPositions(true);$(ui.item).addClass("sort-dragging")},stop:function(e,ui){blah.options.done()
var self=$(this).data("sortable");$(ui.item).removeClass("sort-dragging")},sortIndicator:function(e,item,append,hardRefresh){append?append[0].appendChild(this.placeholder[0]):item.item[0].parentNode.insertBefore(this.placeholder[0],(this.direction=='down'?item.item[0]:item.item[0].nextSibling));}});$(this.options.dropOn,this.element).droppable({accept:this.options.sortOn,hoverClass:this.options.hoverClass,drop:function(e,ui){if($(this).parent().hasClass("sort-dragging"))
return;if(!$(this).parent().find("ul").size())
$(this).parent().append(document.createElement("ul"))
$(this).parent().find("ul:first").append(ui.draggable);self.element.data("sortable")._noFinalSort=true;}});}});$.extend($.ui.tree,{defaults:{sortOn:"*",dropOn:"div",hoverClass:"ui-tree-hover"}});

/**
 * Auto Expanding Text Area (1.2.2)
 * by Chrys Bader (www.chrysbader.com)
 * chrysb@gmail.com
 *
 * Special thanks to:
 * Jake Chapa - jake@hybridstudio.com
 * John Resig - jeresig@gmail.com
 *
 * Copyright (c) 2008 Chrys Bader (www.chrysbader.com)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 *
 * NOTE: This script requires jQuery to work.  Download jQuery at www.jquery.com
 *
 */
(function(jQuery) {
	var self = null;
	jQuery.fn.autogrow = function(o) {
		return this.each(function() {
			new jQuery.autogrow(this, o);
		});
	};
	jQuery.autogrow = function(e, o) {
		if (o == "disable")
			return $(".autogrow-dummy").addClass("disabled")

		this.options = o || {};
		this.dummy = null;
		this.interval = null;
		this.line_height = this.options.lineHeight || parseInt(jQuery(e).css('line-height'));
		this.min_height = this.options.minHeight || parseInt(jQuery(e).css('min-height'));
		this.max_height = this.options.maxHeight || parseInt(jQuery(e).css('max-height'));;
		this.textarea = jQuery(e);
		if (this.line_height == NaN) this.line_height = 0;
		this.init();
	};
	jQuery.autogrow.fn = jQuery.autogrow.prototype = {
		autogrow: '1.2.2'
	};
	jQuery.autogrow.fn.extend = jQuery.autogrow.extend = jQuery.extend;
	jQuery.autogrow.fn.extend({
		init: function() {
			var self = this;
			this.textarea.css({
				overflow: 'hidden',
				display: 'block'
			});
			this.textarea.bind('focus', function() { self.startExpand() }).bind('blur', function() { self.stopExpand() });
			this.checkExpand();
		},
		startExpand: function() {
			var self = this;
			this.interval = window.setInterval(function() { self.checkExpand() }, 400);
			// this.textarea.keydown(function() {
			//     self.checkExpand()
			// })
		},
		stopExpand: function() {
			clearInterval(this.interval);
		},
		checkExpand: function() {
			if (this.dummy == null) {
				this.dummy = jQuery('<div />');
				this.dummy.css({
					fontSize: this.textarea.css('font-size'),
					fontFamily: this.textarea.css('font-family'),
					width: this.textarea.css('width'),
					padding: this.textarea.css('padding'),
					lineHeight: this.line_height + 'px',
					overflowX: 'hidden',
					display: "none"
				}).addClass("autogrow-dummy").appendTo('body');
			}

			if (this.dummy.hasClass("disabled"))
                return clearInterval(this.interval)

			var html = this.textarea.val().replace(/(<|>|&)/g, '&#160;');
			html = ($.browser.msie) ? html.replace(/\n/g, '<BR>new') : html.replace(/\n/g, '<br>new');

			var fixed = html.replace(/&#160;/g, '&nbsp;');

			if (this.dummy.html() != fixed) {
				this.dummy.html(html);
				if (this.max_height > 0 && (this.dummy.height() + this.line_height > this.max_height)) {
					this.textarea.css('overflow-y', 'auto');
				} else {
					this.textarea.css('overflow-y', 'hidden');
					if (this.textarea.height() < this.dummy.height() + this.line_height || (this.dummy.height() < this.textarea.height()))
						this.textarea.height(this.dummy.height() + this.line_height)
				}
			}
		}
	});
})(jQuery);


/**
 * jQuery ifixpng plugin
 * (previously known as pngfix)
 * Version 2.1	(23/04/2008)
 * @requires jQuery v1.1.3 or above
 *
 * Examples at: http://jquery.khurshid.com
 * Copyright (c) 2007 Kush M.
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */
(function($){$.ifixpng=function(customPixel){$.ifixpng.pixel=customPixel;};$.ifixpng.getPixel=function(){return $.ifixpng.pixel||'images/pixel.gif';};var hack={ltie7:$.browser.msie&&$.browser.version<7,filter:function(src){return"progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true,sizingMethod=crop,src='"+src+"')";}};$.fn.ifixpng=hack.ltie7?function(){return this.each(function(){var $$=$(this);var base=$('base').attr('href');if(base){base=base.replace(/\/[^\/]+$/,'/');}if($$.is('img')||$$.is('input')){if($$.attr('src')){if($$.attr('src').match(/.*\.png([?].*)?$/i)){var source=(base&&$$.attr('src').search(/^(\/|http:)/i))?base+$$.attr('src'):$$.attr('src');$$.css({filter:hack.filter(source),width:$$.width(),height:$$.height()}).attr({src:$.ifixpng.getPixel()}).positionFix();}}}else{var image=$$.css('backgroundImage');if(image.match(/^url\(["']?(.*\.png([?].*)?)["']?\)$/i)){image=RegExp.$1;image=(base&&image.substring(0,1)!='/')?base+image:image;$$.css({backgroundImage:'none',filter:hack.filter(image)}).children().children().positionFix();}}});}:function(){return this;};$.fn.iunfixpng=hack.ltie7?function(){return this.each(function(){var $$=$(this);var src=$$.css('filter');if(src.match(/src=["']?(.*\.png([?].*)?)["']?/i)){src=RegExp.$1;if($$.is('img')||$$.is('input')){$$.attr({src:src}).css({filter:''});}else{$$.css({filter:'',background:'url('+src+')'});}}});}:function(){return this;};$.fn.positionFix=function(){return this.each(function(){var $$=$(this);var position=$$.css('position');if(position!='absolute'&&position!='relative'){$$.css({position:'relative'});}});};})(jQuery);(function($){$.fn.ajaxSubmit=function(options){if(!this.length){log('ajaxSubmit: skipping submit process - no element selected');return this;}if(typeof options=='function')options={success:options};options=$.extend({url:this.attr('action')||window.location.toString(),type:this.attr('method')||'GET'},options||{});var veto={};this.trigger('form-pre-serialize',[this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');return this;}var a=this.formToArray(options.semantic);if(options.data){options.extraData=options.data;for(var n in options.data)a.push({name:n,value:options.data[n]});}if(options.beforeSubmit&&options.beforeSubmit(a,this,options)===false){log('ajaxSubmit: submit aborted via beforeSubmit callback');return this;}this.trigger('form-submit-validate',[a,this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-submit-validate trigger');return this;}var q=$.param(a);if(options.type.toUpperCase()=='GET'){options.url+=(options.url.indexOf('?')>=0?'&':'?')+q;options.data=null;}else
options.data=q;var $form=this,callbacks=[];if(options.resetForm)callbacks.push(function(){$form.resetForm();});if(options.clearForm)callbacks.push(function(){$form.clearForm();});if(!options.dataType&&options.target){var oldSuccess=options.success||function(){};callbacks.push(function(data){$(options.target).html(data).each(oldSuccess,arguments);});}else if(options.success)callbacks.push(options.success);options.success=function(data,status){for(var i=0,max=callbacks.length;i<max;i++)callbacks[i](data,status,$form);};var files=$('input:file',this).fieldValue();var found=false;for(var j=0;j<files.length;j++)if(files[j])found=true;if(options.iframe||found){if($.browser.safari&&options.closeKeepAlive)$.get(options.closeKeepAlive,fileUpload);else
fileUpload();}else
$.ajax(options);this.trigger('form-submit-notify',[this,options]);return this;function fileUpload(){var form=$form[0];var opts=$.extend({},$.ajaxSettings,options);var id='jqFormIO'+(new Date().getTime());var $io=$('<iframe id="'+id+'" name="'+id+'" />');var io=$io[0];if($.browser.msie||$.browser.opera)io.src='javascript:false;document.write("");';$io.css({position:'absolute',top:'-1000px',left:'-1000px'});var xhr={responseText:null,responseXML:null,status:0,statusText:'n/a',getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){}};var g=opts.global;if(g&&!$.active++)$.event.trigger("ajaxStart");if(g)$.event.trigger("ajaxSend",[xhr,opts]);var cbInvoked=0;var timedOut=0;setTimeout(function(){var t=$form.attr('target'),a=$form.attr('action');$form.attr({target:id,encoding:'multipart/form-data',enctype:'multipart/form-data',method:'POST',action:opts.url});if(opts.timeout)setTimeout(function(){timedOut=true;cb();},opts.timeout);var extraInputs=[];try{if(options.extraData)for(var n in options.extraData)extraInputs.push($('<input type="hidden" name="'+n+'" value="'+options.extraData[n]+'" />').appendTo(form)[0]);$io.appendTo('body');io.attachEvent?io.attachEvent('onload',cb):io.addEventListener('load',cb,false);form.submit();}finally{$form.attr('action',a);t?$form.attr('target',t):$form.removeAttr('target');$(extraInputs).remove();}},10);function cb(){if(cbInvoked++)return;io.detachEvent?io.detachEvent('onload',cb):io.removeEventListener('load',cb,false);var operaHack=0;var ok=true;try{if(timedOut)throw'timeout';var data,doc;doc=io.contentWindow?io.contentWindow.document:io.contentDocument?io.contentDocument:io.document;if(doc.body==null&&!operaHack&&$.browser.opera){operaHack=1;cbInvoked--;setTimeout(cb,100);return;}xhr.responseText=doc.body?doc.body.innerHTML:null;xhr.responseXML=doc.XMLDocument?doc.XMLDocument:doc;xhr.getResponseHeader=function(header){var headers={'content-type':opts.dataType};return headers[header];};if(opts.dataType=='json'||opts.dataType=='script'){var ta=doc.getElementsByTagName('textarea')[0];xhr.responseText=ta?ta.value:xhr.responseText;}else if(opts.dataType=='xml'&&!xhr.responseXML&&xhr.responseText!=null){xhr.responseXML=toXml(xhr.responseText);}data=$.httpData(xhr,opts.dataType);}catch(e){ok=false;$.handleError(opts,xhr,'error',e);}if(ok){opts.success(data,'success');if(g)$.event.trigger("ajaxSuccess",[xhr,opts]);}if(g)$.event.trigger("ajaxComplete",[xhr,opts]);if(g&&!--$.active)$.event.trigger("ajaxStop");if(opts.complete)opts.complete(xhr,ok?'success':'error');setTimeout(function(){$io.remove();xhr.responseXML=null;},100);};function toXml(s,doc){if(window.ActiveXObject){doc=new ActiveXObject('Microsoft.XMLDOM');doc.async='false';doc.loadXML(s);}else
doc=(new DOMParser()).parseFromString(s,'text/xml');return(doc&&doc.documentElement&&doc.documentElement.tagName!='parsererror')?doc:null;};};};$.fn.ajaxForm=function(options){return this.ajaxFormUnbind().bind('submit.form-plugin',function(){$(this).ajaxSubmit(options);return false;}).each(function(){$(":submit,input:image",this).bind('click.form-plugin',function(e){var $form=this.form;$form.clk=this;if(this.type=='image'){if(e.offsetX!=undefined){$form.clk_x=e.offsetX;$form.clk_y=e.offsetY;}else if(typeof $.fn.offset=='function'){var offset=$(this).offset();$form.clk_x=e.pageX-offset.left;$form.clk_y=e.pageY-offset.top;}else{$form.clk_x=e.pageX-this.offsetLeft;$form.clk_y=e.pageY-this.offsetTop;}}setTimeout(function(){$form.clk=$form.clk_x=$form.clk_y=null;},10);});});};$.fn.ajaxFormUnbind=function(){this.unbind('submit.form-plugin');return this.each(function(){$(":submit,input:image",this).unbind('click.form-plugin');});};$.fn.formToArray=function(semantic){var a=[];if(this.length==0)return a;var form=this[0];var els=semantic?form.getElementsByTagName('*'):form.elements;if(!els)return a;for(var i=0,max=els.length;i<max;i++){var el=els[i];var n=el.name;if(!n)continue;if(semantic&&form.clk&&el.type=="image"){if(!el.disabled&&form.clk==el)a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});continue;}var v=$.fieldValue(el,true);if(v&&v.constructor==Array){for(var j=0,jmax=v.length;j<jmax;j++)a.push({name:n,value:v[j]});}else if(v!==null&&typeof v!='undefined')a.push({name:n,value:v});}if(!semantic&&form.clk){var inputs=form.getElementsByTagName("input");for(var i=0,max=inputs.length;i<max;i++){var input=inputs[i];var n=input.name;if(n&&!input.disabled&&input.type=="image"&&form.clk==input)a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});}}return a;};$.fn.formSerialize=function(semantic){return $.param(this.formToArray(semantic));};$.fn.fieldSerialize=function(successful){var a=[];this.each(function(){var n=this.name;if(!n)return;var v=$.fieldValue(this,successful);if(v&&v.constructor==Array){for(var i=0,max=v.length;i<max;i++)a.push({name:n,value:v[i]});}else if(v!==null&&typeof v!='undefined')a.push({name:this.name,value:v});});return $.param(a);};$.fn.fieldValue=function(successful){for(var val=[],i=0,max=this.length;i<max;i++){var el=this[i];var v=$.fieldValue(el,successful);if(v===null||typeof v=='undefined'||(v.constructor==Array&&!v.length))continue;v.constructor==Array?$.merge(val,v):val.push(v);}return val;};$.fieldValue=function(el,successful){var n=el.name,t=el.type,tag=el.tagName.toLowerCase();if(typeof successful=='undefined')successful=true;if(successful&&(!n||el.disabled||t=='reset'||t=='button'||(t=='checkbox'||t=='radio')&&!el.checked||(t=='submit'||t=='image')&&el.form&&el.form.clk!=el||tag=='select'&&el.selectedIndex==-1))return null;if(tag=='select'){var index=el.selectedIndex;if(index<0)return null;var a=[],ops=el.options;var one=(t=='select-one');var max=(one?index+1:ops.length);for(var i=(one?index:0);i<max;i++){var op=ops[i];if(op.selected){var v=$.browser.msie&&!(op.attributes['value'].specified)?op.text:op.value;if(one)return v;a.push(v);}}return a;}return el.value;};$.fn.clearForm=function(){return this.each(function(){$('input,select,textarea',this).clearFields();});};$.fn.clearFields=$.fn.clearInputs=function(){return this.each(function(){var t=this.type,tag=this.tagName.toLowerCase();if(t=='text'||t=='password'||tag=='textarea')this.value='';else if(t=='checkbox'||t=='radio')this.checked=false;else if(tag=='select')this.selectedIndex=-1;});};$.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=='function'||(typeof this.reset=='object'&&!this.reset.nodeType))this.reset();});};$.fn.enable=function(b){if(b==undefined)b=true;return this.each(function(){this.disabled=!b});};$.fn.select=function(select){if(select==undefined)select=true;return this.each(function(){var t=this.type;if(t=='checkbox'||t=='radio')this.checked=select;else if(this.tagName.toLowerCase()=='option'){var $sel=$(this).parent('select');if(select&&$sel[0]&&$sel[0].type=='select-one'){$sel.find('option').select(false);}this.selected=select;}});};function log(){if($.fn.ajaxSubmit.debug&&window.console&&window.console.log)window.console.log('[jquery.form] '+Array.prototype.join.call(arguments,''));};})(jQuery);


/**
 * jQuery Form Plugin
 * version: 2.12 (06/07/2008)
 * @requires jQuery v1.2.2 or later
 *
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Dual licensed under the MIT and GPL licenses:
 *	 http://www.opensource.org/licenses/mit-license.php
 *	 http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id$
 */
(function($){$.fn.ajaxSubmit=function(options){if(!this.length){log('ajaxSubmit: skipping submit process - no element selected');return this;}
if(typeof options=='function')
options={success:options};options=$.extend({url:this.attr('action')||window.location.toString(),type:this.attr('method')||'GET'},options||{});var veto={};this.trigger('form-pre-serialize',[this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');return this;}
var a=this.formToArray(options.semantic);if(options.data){options.extraData=options.data;for(var n in options.data)
a.push({name:n,value:options.data[n]});}
if(options.beforeSubmit&&options.beforeSubmit(a,this,options)===false){log('ajaxSubmit: submit aborted via beforeSubmit callback');return this;}
this.trigger('form-submit-validate',[a,this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-submit-validate trigger');return this;}
var q=$.param(a);if(options.type.toUpperCase()=='GET'){options.url+=(options.url.indexOf('?')>=0?'&':'?')+q;options.data=null;}
else
options.data=q;var $form=this,callbacks=[];if(options.resetForm)callbacks.push(function(){$form.resetForm();});if(options.clearForm)callbacks.push(function(){$form.clearForm();});if(!options.dataType&&options.target){var oldSuccess=options.success||function(){};callbacks.push(function(data){$(options.target).html(data).each(oldSuccess,arguments);});}
else if(options.success)
callbacks.push(options.success);options.success=function(data,status){for(var i=0,max=callbacks.length;i<max;i++)
callbacks[i](data,status,$form);};var files=$('input:file',this).fieldValue();var found=false;for(var j=0;j<files.length;j++)
if(files[j])
found=true;if(options.iframe||found){if($.browser.safari&&options.closeKeepAlive)
$.get(options.closeKeepAlive,fileUpload);else
fileUpload();}
else
$.ajax(options);this.trigger('form-submit-notify',[this,options]);return this;function fileUpload(){var form=$form[0];if($(':input[@name=submit]',form).length){alert('Error: Form elements must not be named "submit".');return;}
var opts=$.extend({},$.ajaxSettings,options);var id='jqFormIO'+(new Date().getTime());var $io=$('<iframe id="'+id+'" name="'+id+'" />');var io=$io[0];if($.browser.msie||$.browser.opera)
io.src='javascript:false;document.write("");';$io.css({position:'absolute',top:'-1000px',left:'-1000px'});var xhr={responseText:null,responseXML:null,status:0,statusText:'n/a',getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){}};var g=opts.global;if(g&&!$.active++)$.event.trigger("ajaxStart");if(g)$.event.trigger("ajaxSend",[xhr,opts]);var cbInvoked=0;var timedOut=0;var sub=form.clk;if(sub){var n=sub.name;if(n&&!sub.disabled){options.extraData=options.extraData||{};options.extraData[n]=sub.value;if(sub.type=="image"){options.extraData[name+'.x']=form.clk_x;options.extraData[name+'.y']=form.clk_y;}}}
setTimeout(function(){var t=$form.attr('target'),a=$form.attr('action');$form.attr({target:id,encoding:'multipart/form-data',enctype:'multipart/form-data',method:'POST',action:opts.url});if(opts.timeout)
setTimeout(function(){timedOut=true;cb();},opts.timeout);var extraInputs=[];try{if(options.extraData)
for(var n in options.extraData)
extraInputs.push($('<input type="hidden" name="'+n+'" value="'+options.extraData[n]+'" />').appendTo(form)[0]);$io.appendTo('body');io.attachEvent?io.attachEvent('onload',cb):io.addEventListener('load',cb,false);form.submit();}
finally{$form.attr('action',a);t?$form.attr('target',t):$form.removeAttr('target');$(extraInputs).remove();}},10);function cb(){if(cbInvoked++)return;io.detachEvent?io.detachEvent('onload',cb):io.removeEventListener('load',cb,false);var operaHack=0;var ok=true;try{if(timedOut)throw'timeout';var data,doc;doc=io.contentWindow?io.contentWindow.document:io.contentDocument?io.contentDocument:io.document;if(doc.body==null&&!operaHack&&$.browser.opera){operaHack=1;cbInvoked--;setTimeout(cb,100);return;}
xhr.responseText=doc.body?doc.body.innerHTML:null;xhr.responseXML=doc.XMLDocument?doc.XMLDocument:doc;xhr.getResponseHeader=function(header){var headers={'content-type':opts.dataType};return headers[header];};if(opts.dataType=='json'||opts.dataType=='script'){var ta=doc.getElementsByTagName('textarea')[0];xhr.responseText=ta?ta.value:xhr.responseText;}
else if(opts.dataType=='xml'&&!xhr.responseXML&&xhr.responseText!=null){xhr.responseXML=toXml(xhr.responseText);}
data=$.httpData(xhr,opts.dataType);}
catch(e){ok=false;$.handleError(opts,xhr,'error',e);}
if(ok){opts.success(data,'success');if(g)$.event.trigger("ajaxSuccess",[xhr,opts]);}
if(g)$.event.trigger("ajaxComplete",[xhr,opts]);if(g&&!--$.active)$.event.trigger("ajaxStop");if(opts.complete)opts.complete(xhr,ok?'success':'error');setTimeout(function(){$io.remove();xhr.responseXML=null;},100);};function toXml(s,doc){if(window.ActiveXObject){doc=new ActiveXObject('Microsoft.XMLDOM');doc.async='false';doc.loadXML(s);}
else
doc=(new DOMParser()).parseFromString(s,'text/xml');return(doc&&doc.documentElement&&doc.documentElement.tagName!='parsererror')?doc:null;};};};$.fn.ajaxForm=function(options){return this.ajaxFormUnbind().bind('submit.form-plugin',function(){$(this).ajaxSubmit(options);return false;}).each(function(){$(":submit,input:image",this).bind('click.form-plugin',function(e){var $form=this.form;$form.clk=this;if(this.type=='image'){if(e.offsetX!=undefined){$form.clk_x=e.offsetX;$form.clk_y=e.offsetY;}else if(typeof $.fn.offset=='function'){var offset=$(this).offset();$form.clk_x=e.pageX-offset.left;$form.clk_y=e.pageY-offset.top;}else{$form.clk_x=e.pageX-this.offsetLeft;$form.clk_y=e.pageY-this.offsetTop;}}
setTimeout(function(){$form.clk=$form.clk_x=$form.clk_y=null;},10);});});};$.fn.ajaxFormUnbind=function(){this.unbind('submit.form-plugin');return this.each(function(){$(":submit,input:image",this).unbind('click.form-plugin');});};$.fn.formToArray=function(semantic){var a=[];if(this.length==0)return a;var form=this[0];var els=semantic?form.getElementsByTagName('*'):form.elements;if(!els)return a;for(var i=0,max=els.length;i<max;i++){var el=els[i];var n=el.name;if(!n)continue;if(semantic&&form.clk&&el.type=="image"){if(!el.disabled&&form.clk==el)
a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});continue;}
var v=$.fieldValue(el,true);if(v&&v.constructor==Array){for(var j=0,jmax=v.length;j<jmax;j++)
a.push({name:n,value:v[j]});}
else if(v!==null&&typeof v!='undefined')
a.push({name:n,value:v});}
if(!semantic&&form.clk){var inputs=form.getElementsByTagName("input");for(var i=0,max=inputs.length;i<max;i++){var input=inputs[i];var n=input.name;if(n&&!input.disabled&&input.type=="image"&&form.clk==input)
a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});}}
return a;};$.fn.formSerialize=function(semantic){return $.param(this.formToArray(semantic));};$.fn.fieldSerialize=function(successful){var a=[];this.each(function(){var n=this.name;if(!n)return;var v=$.fieldValue(this,successful);if(v&&v.constructor==Array){for(var i=0,max=v.length;i<max;i++)
a.push({name:n,value:v[i]});}
else if(v!==null&&typeof v!='undefined')
a.push({name:this.name,value:v});});return $.param(a);};$.fn.fieldValue=function(successful){for(var val=[],i=0,max=this.length;i<max;i++){var el=this[i];var v=$.fieldValue(el,successful);if(v===null||typeof v=='undefined'||(v.constructor==Array&&!v.length))
continue;v.constructor==Array?$.merge(val,v):val.push(v);}
return val;};$.fieldValue=function(el,successful){var n=el.name,t=el.type,tag=el.tagName.toLowerCase();if(typeof successful=='undefined')successful=true;if(successful&&(!n||el.disabled||t=='reset'||t=='button'||(t=='checkbox'||t=='radio')&&!el.checked||(t=='submit'||t=='image')&&el.form&&el.form.clk!=el||tag=='select'&&el.selectedIndex==-1))
return null;if(tag=='select'){var index=el.selectedIndex;if(index<0)return null;var a=[],ops=el.options;var one=(t=='select-one');var max=(one?index+1:ops.length);for(var i=(one?index:0);i<max;i++){var op=ops[i];if(op.selected){var v=$.browser.msie&&!(op.attributes['value'].specified)?op.text:op.value;if(one)return v;a.push(v);}}
return a;}
return el.value;};$.fn.clearForm=function(){return this.each(function(){$('input,select,textarea',this).clearFields();});};$.fn.clearFields=$.fn.clearInputs=function(){return this.each(function(){var t=this.type,tag=this.tagName.toLowerCase();if(t=='text'||t=='password'||tag=='textarea')
this.value='';else if(t=='checkbox'||t=='radio')
this.checked=false;else if(tag=='select')
this.selectedIndex=-1;});};$.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=='function'||(typeof this.reset=='object'&&!this.reset.nodeType))
this.reset();});};$.fn.enable=function(b){if(b==undefined)b=true;return this.each(function(){this.disabled=!b});};$.fn.select=function(select){if(select==undefined)select=true;return this.each(function(){var t=this.type;if(t=='checkbox'||t=='radio')
this.checked=select;else if(this.tagName.toLowerCase()=='option'){var $sel=$(this).parent('select');if(select&&$sel[0]&&$sel[0].type=='select-one'){$sel.find('option').select(false);}
this.selected=select;}});};function log(){if($.fn.ajaxSubmit.debug&&window.console&&window.console.log)
window.console.log('[jquery.form] '+Array.prototype.join.call(arguments,''));};})(jQuery);


/**
 * Copyright (c) 2007 Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.2
 * Requires jQuery 1.1.3+
 * Docs: http://docs.jquery.com/Plugins/livequery
 */
(function($){$.extend($.fn,{livequery:function(type,fn,fn2){var self=this,q;if($.isFunction(type))fn2=fn,fn=type,type=undefined;$.each($.livequery.queries,function(i,query){if(self.selector==query.selector&&self.context==query.context&&type==query.type&&(!fn||fn.$lqguid==query.fn.$lqguid)&&(!fn2||fn2.$lqguid==query.fn2.$lqguid))return(q=query)&&false;});q=q||new $.livequery(this.selector,this.context,type,fn,fn2);q.stopped=false;$.livequery.run(q.id);return this;},expire:function(type,fn,fn2){var self=this;if($.isFunction(type))fn2=fn,fn=type,type=undefined;$.each($.livequery.queries,function(i,query){if(self.selector==query.selector&&self.context==query.context&&(!type||type==query.type)&&(!fn||fn.$lqguid==query.fn.$lqguid)&&(!fn2||fn2.$lqguid==query.fn2.$lqguid)&&!this.stopped)$.livequery.stop(query.id);});return this;}});$.livequery=function(selector,context,type,fn,fn2){this.selector=selector;this.context=context||document;this.type=type;this.fn=fn;this.fn2=fn2;this.elements=[];this.stopped=false;this.id=$.livequery.queries.push(this)-1;fn.$lqguid=fn.$lqguid||$.livequery.guid++;if(fn2)fn2.$lqguid=fn2.$lqguid||$.livequery.guid++;return this;};$.livequery.prototype={stop:function(){var query=this;if(this.type)this.elements.unbind(this.type,this.fn);else if(this.fn2)this.elements.each(function(i,el){query.fn2.apply(el);});this.elements=[];this.stopped=true;},run:function(){if(this.stopped)return;var query=this;var oEls=this.elements,els=$(this.selector,this.context),nEls=els.not(oEls);this.elements=els;if(this.type){nEls.bind(this.type,this.fn);if(oEls.length>0)$.each(oEls,function(i,el){if($.inArray(el,els)<0)$.event.remove(el,query.type,query.fn);});}else{nEls.each(function(){query.fn.apply(this);});if(this.fn2&&oEls.length>0)$.each(oEls,function(i,el){if($.inArray(el,els)<0)query.fn2.apply(el);});}}};$.extend($.livequery,{guid:0,queries:[],queue:[],running:false,timeout:null,checkQueue:function(){if($.livequery.running&&$.livequery.queue.length){var length=$.livequery.queue.length;while(length--)$.livequery.queries[$.livequery.queue.shift()].run();}},pause:function(){$.livequery.running=false;},play:function(){$.livequery.running=true;$.livequery.run();},registerPlugin:function(){$.each(arguments,function(i,n){if(!$.fn[n])return;var old=$.fn[n];$.fn[n]=function(){var r=old.apply(this,arguments);$.livequery.run();return r;}});},run:function(id){if(id!=undefined){if($.inArray(id,$.livequery.queue)<0)$.livequery.queue.push(id);}else
$.each($.livequery.queries,function(id){if($.inArray(id,$.livequery.queue)<0)$.livequery.queue.push(id);});if($.livequery.timeout)clearTimeout($.livequery.timeout);$.livequery.timeout=setTimeout($.livequery.checkQueue,20);},stop:function(id){if(id!=undefined)$.livequery.queries[id].stop();else
$.each($.livequery.queries,function(id){$.livequery.queries[id].stop();});}});$.livequery.registerPlugin('append','prepend','after','before','wrap','attr','removeAttr','addClass','removeClass','toggleClass','empty','remove');$(function(){$.livequery.play();});var init=$.prototype.init;$.prototype.init=function(a,c){var r=init.apply(this,arguments);if(a&&a.selector)r.context=a.context,r.selector=a.selector;if(typeof a=='string')r.context=c||document,r.selector=a;return r;};$.prototype.init.prototype=$.prototype;})(jQuery);


// Our custom field expander
$.fn.expand = function(){
	$(this).each(function(){
		if ($(this).parent().parent().attr("class") == "more_options" || $.browser.msie) return

		var id = $(this).attr("id")
		var dummy = ".dummy_"+ id

		$(this).css("min-width", $(this).width())

		$(this).css("max-width", $(this).parent().width() - 8)

		$(document.createElement("span")).prependTo("body").addClass("dummy_"+ id).css({
			fontSize: $(this).css("font-size"),
			fontFamily: $(this).css("font-family"),
			padding: $(this).css("padding"),
			position: "absolute",
			top: -9999
		}).text($(this).val())

		$(this).width($(dummy).width() + 20)

		$(this).keypress(function(e){
			$(dummy).text($(this).val() + String.fromCharCode(e.which))

			if (e.which == 8)
				$(dummy).text($(this).val().substring(0, $(this).val().length - 1))

			$(this).width($(dummy).width() + 20)
		})

		if ($.browser.safari)
			$(this).keydown(function(e){
				$(dummy).text($(this).val() + String.fromCharCode(e.which))

				if (e.which == 8)
					$(dummy).text($(this).val().substring(0, $(this).val().length - 1))

				$(this).width($(dummy).width() + 20)
			})
	})
}

// "Loading..." overlay.
$.fn.loader = function(remove) {
	if (remove) {
		$(this).next().remove()
		return this
	}

	var offset = $(this).offset()
	var loading_top = ($(this).outerHeight() / 2) - 11
	var loading_left = ($(this).outerWidth() / 2) - 63

	$(this).after("<div class=\"load_overlay\"><img src=\""+site_url+"/includes/close.png\" style=\"display: none\" class=\"close\" /><img src=\""+site_url+"/includes/loading.gif\" style=\"display: none\" class=\"loading\" /></div>")

	$(".load_overlay .loading").css({
		position: "absolute",
		top: loading_top+"px",
		left: loading_left+"px",
		display: "inline"
	})

	$(".load_overlay .close").css({
		position: "absolute",
		top: "3px",
		right: "3px",
		color: "#fff",
		cursor: "pointer",
		display: "inline"
	}).click(function(){ $(this).parent().remove() })

	$(".load_overlay").css({
		position: "absolute",
		top: offset.top,
		left: offset.left,
		zIndex: 100,
		width: $(this).outerWidth(),
		height: $(this).outerHeight(),
		background: ($.browser.msie) ? "transparent" : "transparent url('"+site_url+"/includes/trans.png')",
		textAlign: "center",
		filter: ($.browser.msie) ? "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src='"+site_url+"/includes/trans.png');" : ""
	})

	return this
}

// Originally from http://livepipe.net/extra/cookie
var Cookie = {
	set: function (name, value, days) {
		if (days) {
			var d = new Date();
			d.setTime(d.getTime() + (days * 1000 * 60 * 60 * 24));
			var expiry = "; expires=" + d.toGMTString();
		} else
			var expiry = "";

		document.cookie = name + "=" + value + expiry + "; path=/";
	},
	get: function(name){
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];

			while(c.charAt(0) == " ")
				c = c.substring(1,c.length);

			if(c.indexOf(nameEQ) == 0)
				return c.substring(nameEQ.length,c.length);
		}
		return null;
	},
	destroy: function(name){
		Cookie.set(name, "", -1);
	}
}

// Used to check if AJAX responses are errors.
function isError(text) {
	return /HEY_JAVASCRIPT_THIS_IS_AN_ERROR_JUST_SO_YOU_KNOW$/m.test(text);
}

Array.prototype.indicesOf = function(value) {
	var results = []

	for (var j = 0; j < this.length; j++)
		if (typeof value != "string") {
			if (value.test(this[j]))
				results.push(j)
		} else if (this[j] == value)
			results.push(j)

	return results
}

Array.prototype.find = function(match) {
	var matches = []

	for (var f = 0; f < this.length; f++)
		if (match.test(this[f]))
			matches.push(this[f])

	return matches
}

Array.prototype.remove = function(value) {
	if (value instanceof Array) {
		for (var r = 0; r < value.length; r++)
			this.remove(value[r])

		return
	}

	var indices = this.indicesOf(value)

	if (indices.length == 0)
		return

	for (var h = 0; h < indices.length; h++)
		this.splice(indices[h] - h, 1)

	return this
}
