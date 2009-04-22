jQuery.iDrag =	{
	helper : null,
	dragged: null,
	destroy : function()
	{
		return this.each(
			function ()
			{
				if (this.isDraggable) {
					this.dragCfg.dhe.unbind('mousedown', jQuery.iDrag.draginit);
					this.dragCfg = null;
					this.isDraggable = false;
					if(jQuery.browser.msie) {
						this.unselectable = "off";
					} else {
						this.style.MozUserSelect = '';
						this.style.KhtmlUserSelect = '';
						this.style.userSelect = '';
					}
				}
			}
		);
	},
	draginit : function (e)
	{
		if (jQuery.iDrag.dragged != null) {
			jQuery.iDrag.dragstop(e);
			return false;
		}
		var elm = this.dragElem;
		jQuery(document)
			.bind('mousemove', jQuery.iDrag.dragmove)
			.bind('mouseup', jQuery.iDrag.dragstop);
		elm.dragCfg.pointer = jQuery.iUtil.getPointer(e);
		elm.dragCfg.currentPointer = elm.dragCfg.pointer;
		elm.dragCfg.init = false;
		elm.dragCfg.fromHandler = this != this.dragElem;
		jQuery.iDrag.dragged = elm;
		if (elm.dragCfg.si && this != this.dragElem) {
				parentPos = jQuery.iUtil.getPosition(elm.parentNode);
				sliderSize = jQuery.iUtil.getSize(elm);
				sliderPos = {
					x : parseInt(jQuery.css(elm,'left')) || 0,
					y : parseInt(jQuery.css(elm,'top')) || 0
				};
				dx = elm.dragCfg.currentPointer.x - parentPos.x - sliderSize.wb/2 - sliderPos.x;
				dy = elm.dragCfg.currentPointer.y - parentPos.y - sliderSize.hb/2 - sliderPos.y;
				jQuery.iSlider.dragmoveBy(elm, [dx, dy]);
		}
		return jQuery.selectKeyHelper||false;
	},

	dragstart : function(e)
	{
		var elm = jQuery.iDrag.dragged;
		elm.dragCfg.init = true;

		var dEs = elm.style;

		elm.dragCfg.oD = jQuery.css(elm,'display');
		elm.dragCfg.oP = jQuery.css(elm,'position');
		if (!elm.dragCfg.initialPosition)
			elm.dragCfg.initialPosition = elm.dragCfg.oP;

		elm.dragCfg.oR = {
			x : parseInt(jQuery.css(elm,'left')) || 0,
			y : parseInt(jQuery.css(elm,'top')) || 0
		};
		elm.dragCfg.diffX = 0;
		elm.dragCfg.diffY = 0;
		if (jQuery.browser.msie) {
			var oldBorder = jQuery.iUtil.getBorder(elm, true);
			elm.dragCfg.diffX = oldBorder.l||0;
			elm.dragCfg.diffY = oldBorder.t||0;
		}

		elm.dragCfg.oC = jQuery.extend(
			jQuery.iUtil.getPosition(elm),
			jQuery.iUtil.getSize(elm)
		);
		if (elm.dragCfg.oP != 'relative' && elm.dragCfg.oP != 'absolute') {
			dEs.position = 'relative';
		}

		jQuery.iDrag.helper.empty();
		var clonedEl = elm.cloneNode(true);
		
		jQuery(clonedEl).css(
			{
				display:	'block',
				left:		'0px',
				top: 		'0px'
			}
		);
		clonedEl.style.marginTop = '0';
		clonedEl.style.marginRight = '0';
		clonedEl.style.marginBottom = '0';
		clonedEl.style.marginLeft = '0';
		jQuery.iDrag.helper.append(clonedEl);
		
		var dhs = jQuery.iDrag.helper.get(0).style;

		if (elm.dragCfg.autoSize) {
			dhs.width = 'auto';
			dhs.height = 'auto';
		} else {
			dhs.height = elm.dragCfg.oC.hb + 'px';
			dhs.width = elm.dragCfg.oC.wb + 'px';
		}

		dhs.display = 'block';
		dhs.marginTop = '0px';
		dhs.marginRight = '0px';
		dhs.marginBottom = '0px';
		dhs.marginLeft = '0px';

		//remeasure the clone to check if the size was changed by user's functions
		jQuery.extend(
			elm.dragCfg.oC,
			jQuery.iUtil.getSize(clonedEl)
		);

		if (elm.dragCfg.cursorAt) {
			if (elm.dragCfg.cursorAt.left) {
				elm.dragCfg.oR.x += elm.dragCfg.pointer.x - elm.dragCfg.oC.x - elm.dragCfg.cursorAt.left;
				elm.dragCfg.oC.x = elm.dragCfg.pointer.x - elm.dragCfg.cursorAt.left;
			}
			if (elm.dragCfg.cursorAt.top) {
				elm.dragCfg.oR.y += elm.dragCfg.pointer.y - elm.dragCfg.oC.y - elm.dragCfg.cursorAt.top;
				elm.dragCfg.oC.y = elm.dragCfg.pointer.y - elm.dragCfg.cursorAt.top;
			}
			if (elm.dragCfg.cursorAt.right) {
				elm.dragCfg.oR.x += elm.dragCfg.pointer.x - elm.dragCfg.oC.x -elm.dragCfg.oC.hb + elm.dragCfg.cursorAt.right;
				elm.dragCfg.oC.x = elm.dragCfg.pointer.x - elm.dragCfg.oC.wb + elm.dragCfg.cursorAt.right;
			}
			if (elm.dragCfg.cursorAt.bottom) {
				elm.dragCfg.oR.y += elm.dragCfg.pointer.y - elm.dragCfg.oC.y - elm.dragCfg.oC.hb + elm.dragCfg.cursorAt.bottom;
				elm.dragCfg.oC.y = elm.dragCfg.pointer.y - elm.dragCfg.oC.hb + elm.dragCfg.cursorAt.bottom;
			}
		}
		elm.dragCfg.nx = elm.dragCfg.oR.x;
		elm.dragCfg.ny = elm.dragCfg.oR.y;

		if (elm.dragCfg.insideParent || elm.dragCfg.containment == 'parent') {
			parentBorders = jQuery.iUtil.getBorder(elm.parentNode, true);
			elm.dragCfg.oC.x = elm.offsetLeft + (jQuery.browser.msie ? 0 : jQuery.browser.opera ? -parentBorders.l : parentBorders.l);
			elm.dragCfg.oC.y = elm.offsetTop + (jQuery.browser.msie ? 0 : jQuery.browser.opera ? -parentBorders.t : parentBorders.t);
			jQuery(elm.parentNode).append(jQuery.iDrag.helper.get(0));
		}
		if (elm.dragCfg.containment) {
			jQuery.iDrag.getContainment(elm);
			elm.dragCfg.onDragModifier.containment = jQuery.iDrag.fitToContainer;
		}

		if (elm.dragCfg.si) {
			jQuery.iSlider.modifyContainer(elm);
		}

		dhs.left = elm.dragCfg.oC.x - elm.dragCfg.diffX + 'px';
		dhs.top = elm.dragCfg.oC.y - elm.dragCfg.diffY + 'px';
		//resize the helper to fit the clone
		dhs.width = elm.dragCfg.oC.wb + 'px';
		dhs.height = elm.dragCfg.oC.hb + 'px';

		jQuery.iDrag.dragged.dragCfg.prot = false;

		if (elm.dragCfg.gx) {
			elm.dragCfg.onDragModifier.grid = jQuery.iDrag.snapToGrid;
		}
		if (elm.dragCfg.zIndex != false) {
			jQuery.iDrag.helper.css('zIndex', elm.dragCfg.zIndex);
		}
		if (elm.dragCfg.opacity) {
			jQuery.iDrag.helper.css('opacity', elm.dragCfg.opacity);
			if (window.ActiveXObject) {
				jQuery.iDrag.helper.css('filter', 'alpha(opacity=' + elm.dragCfg.opacity * 100 + ')');
			}
		}

		if(elm.dragCfg.frameClass) {
			jQuery.iDrag.helper.addClass(elm.dragCfg.frameClass);
			jQuery.iDrag.helper.get(0).firstChild.style.display = 'none';
		}
		if (elm.dragCfg.onStart)
			elm.dragCfg.onStart.apply(elm, [clonedEl, elm.dragCfg.oR.x, elm.dragCfg.oR.y]);
		if (jQuery.iDrop && jQuery.iDrop.count > 0 ){
			jQuery.iDrop.highlight(elm);
		}
		if (elm.dragCfg.ghosting == false) {
			dEs.display = 'none';
		}
		return false;
	},

	getContainment : function(elm)
	{
		if (elm.dragCfg.containment.constructor == String) {
			if (elm.dragCfg.containment == 'parent') {
				elm.dragCfg.cont = jQuery.extend(
					{x:0,y:0},
					jQuery.iUtil.getSize(elm.parentNode)
				);
				var contBorders = jQuery.iUtil.getBorder(elm.parentNode, true);
				elm.dragCfg.cont.w = elm.dragCfg.cont.wb - contBorders.l - contBorders.r;
				elm.dragCfg.cont.h = elm.dragCfg.cont.hb - contBorders.t - contBorders.b;
			} else if (elm.dragCfg.containment == 'document') {
				var clnt = jQuery.iUtil.getClient();
				elm.dragCfg.cont = {
					x : 0,
					y : 0,
					w : clnt.w,
					h : clnt.h
				};
			}
		} else if (elm.dragCfg.containment.constructor == Array) {
			elm.dragCfg.cont = {
				x : parseInt(elm.dragCfg.containment[0])||0,
				y : parseInt(elm.dragCfg.containment[1])||0,
				w : parseInt(elm.dragCfg.containment[2])||0,
				h : parseInt(elm.dragCfg.containment[3])||0
			};
		}
		elm.dragCfg.cont.dx = elm.dragCfg.cont.x - elm.dragCfg.oC.x;
		elm.dragCfg.cont.dy = elm.dragCfg.cont.y - elm.dragCfg.oC.y;
	},

	hidehelper : function(dragged)
	{
		if (dragged.dragCfg.insideParent || dragged.dragCfg.containment == 'parent') {
			jQuery('body', document).append(jQuery.iDrag.helper.get(0));
		}
		jQuery.iDrag.helper.empty().hide().css('opacity', 1);
		if (window.ActiveXObject) {
			jQuery.iDrag.helper.css('filter', 'alpha(opacity=100)');
		}
	},

	dragstop : function(e)
	{

		jQuery(document)
			.unbind('mousemove', jQuery.iDrag.dragmove)
			.unbind('mouseup', jQuery.iDrag.dragstop);

		if (jQuery.iDrag.dragged == null) {
			return;
		}
		var dragged = jQuery.iDrag.dragged;

		jQuery.iDrag.dragged = null;

		if (dragged.dragCfg.init == false) {
			return false;
		}
		if (dragged.dragCfg.so == true) {
			jQuery(dragged).css('position', dragged.dragCfg.oP);
		}
		var dEs = dragged.style;

		if (dragged.si) {
			jQuery.iDrag.helper.css('cursor', 'move');
		}
		if(dragged.dragCfg.frameClass) {
			jQuery.iDrag.helper.removeClass(dragged.dragCfg.frameClass);
		}

		if (dragged.dragCfg.revert == false) {
			if (dragged.dragCfg.fx > 0) {
				if (!dragged.dragCfg.axis || dragged.dragCfg.axis == 'horizontally') {
					var x = new jQuery.fx(dragged,{duration:dragged.dragCfg.fx}, 'left');
					x.custom(dragged.dragCfg.oR.x,dragged.dragCfg.nRx);
				}
				if (!dragged.dragCfg.axis || dragged.dragCfg.axis == 'vertically') {
					var y = new jQuery.fx(dragged,{duration:dragged.dragCfg.fx}, 'top');
					y.custom(dragged.dragCfg.oR.y,dragged.dragCfg.nRy);
				}
			} else {
				if (!dragged.dragCfg.axis || dragged.dragCfg.axis == 'horizontally')
					dragged.style.left = dragged.dragCfg.nRx + 'px';
				if (!dragged.dragCfg.axis || dragged.dragCfg.axis == 'vertically')
					dragged.style.top = dragged.dragCfg.nRy + 'px';
			}
			jQuery.iDrag.hidehelper(dragged);
			if (dragged.dragCfg.ghosting == false) {
				jQuery(dragged).css('display', dragged.dragCfg.oD);
			}
		} else if (dragged.dragCfg.fx > 0) {
			dragged.dragCfg.prot = true;
			var dh = false;
			if(jQuery.iDrop && jQuery.iSort && dragged.dragCfg.so) {
				dh = jQuery.iUtil.getPosition(jQuery.iSort.helper.get(0));
			}
			jQuery.iDrag.helper.animate(
				{
					left : dh ? dh.x : dragged.dragCfg.oC.x,
					top : dh ? dh.y : dragged.dragCfg.oC.y
				},
				dragged.dragCfg.fx,
				function()
				{
					dragged.dragCfg.prot = false;
					if (dragged.dragCfg.ghosting == false) {
						dragged.style.display = dragged.dragCfg.oD;
					}
					jQuery.iDrag.hidehelper(dragged);
				}
			);
		} else {
			jQuery.iDrag.hidehelper(dragged);
			if (dragged.dragCfg.ghosting == false) {
				jQuery(dragged).css('display', dragged.dragCfg.oD);
			}
		}

		if (jQuery.iDrop && jQuery.iDrop.count > 0 ){
			jQuery.iDrop.checkdrop(dragged);
		}
		if (jQuery.iSort && dragged.dragCfg.so) {
			jQuery.iSort.check(dragged);
		}
		if (dragged.dragCfg.onChange && (dragged.dragCfg.nRx != dragged.dragCfg.oR.x || dragged.dragCfg.nRy != dragged.dragCfg.oR.y)){
			dragged.dragCfg.onChange.apply(dragged, dragged.dragCfg.lastSi||[0,0,dragged.dragCfg.nRx,dragged.dragCfg.nRy]);
		}
		if (dragged.dragCfg.onStop)
			dragged.dragCfg.onStop.apply(dragged);
		return false;
	},

	snapToGrid : function(x, y, dx, dy)
	{
		if (dx != 0)
			dx = parseInt((dx + (this.dragCfg.gx * dx/Math.abs(dx))/2)/this.dragCfg.gx) * this.dragCfg.gx;
		if (dy != 0)
			dy = parseInt((dy + (this.dragCfg.gy * dy/Math.abs(dy))/2)/this.dragCfg.gy) * this.dragCfg.gy;
		return {
			dx : dx,
			dy : dy,
			x: 0,
			y: 0
		};
	},

	fitToContainer : function(x, y, dx, dy)
	{
		dx = Math.min(
				Math.max(dx,this.dragCfg.cont.dx),
				this.dragCfg.cont.w + this.dragCfg.cont.dx - this.dragCfg.oC.wb
			);
		dy = Math.min(
				Math.max(dy,this.dragCfg.cont.dy),
				this.dragCfg.cont.h + this.dragCfg.cont.dy - this.dragCfg.oC.hb
			);

		return {
			dx : dx,
			dy : dy,
			x: 0,
			y: 0
		}
	},

	dragmove : function(e)
	{
		if (jQuery.iDrag.dragged == null || jQuery.iDrag.dragged.dragCfg.prot == true) {
			return;
		}

		var dragged = jQuery.iDrag.dragged;

		dragged.dragCfg.currentPointer = jQuery.iUtil.getPointer(e);
		if (dragged.dragCfg.init == false) {
			distance = Math.sqrt(Math.pow(dragged.dragCfg.pointer.x - dragged.dragCfg.currentPointer.x, 2) + Math.pow(dragged.dragCfg.pointer.y - dragged.dragCfg.currentPointer.y, 2));
			if (distance < dragged.dragCfg.snapDistance){
				return;
			} else {
				jQuery.iDrag.dragstart(e);
			}
		}

		var dx = dragged.dragCfg.currentPointer.x - dragged.dragCfg.pointer.x;
		var dy = dragged.dragCfg.currentPointer.y - dragged.dragCfg.pointer.y;

		for (var i in dragged.dragCfg.onDragModifier) {
			var newCoords = dragged.dragCfg.onDragModifier[i].apply(dragged, [dragged.dragCfg.oR.x + dx, dragged.dragCfg.oR.y + dy, dx, dy]);
			if (newCoords && newCoords.constructor == Object) {
				dx = i != 'user' ? newCoords.dx : (newCoords.x - dragged.dragCfg.oR.x);
				dy = i != 'user' ? newCoords.dy : (newCoords.y - dragged.dragCfg.oR.y);
			}
		}

		dragged.dragCfg.nx = dragged.dragCfg.oC.x + dx - dragged.dragCfg.diffX;
		dragged.dragCfg.ny = dragged.dragCfg.oC.y + dy - dragged.dragCfg.diffY;

		if (dragged.dragCfg.si && (dragged.dragCfg.onSlide || dragged.dragCfg.onChange)) {
			jQuery.iSlider.onSlide(dragged, dragged.dragCfg.nx, dragged.dragCfg.ny);
		}

		if(dragged.dragCfg.onDrag)
			dragged.dragCfg.onDrag.apply(dragged, [dragged.dragCfg.oR.x + dx, dragged.dragCfg.oR.y + dy]);
			
		if (!dragged.dragCfg.axis || dragged.dragCfg.axis == 'horizontally') {
			dragged.dragCfg.nRx = dragged.dragCfg.oR.x + dx;
			jQuery.iDrag.helper.get(0).style.left = dragged.dragCfg.nx + 'px';
		}
		if (!dragged.dragCfg.axis || dragged.dragCfg.axis == 'vertically') {
			dragged.dragCfg.nRy = dragged.dragCfg.oR.y + dy;
			jQuery.iDrag.helper.get(0).style.top = dragged.dragCfg.ny + 'px';
		}
		
		if (jQuery.iDrop && jQuery.iDrop.count > 0 ){
			jQuery.iDrop.checkhover(dragged);
		}
		return false;
	},

	build : function(o)
	{
		if (!jQuery.iDrag.helper) {
			jQuery('body',document).append('<div id="dragHelper"></div>');
			jQuery.iDrag.helper = jQuery('#dragHelper');
			var el = jQuery.iDrag.helper.get(0);
			var els = el.style;
			els.position = 'absolute';
			els.display = 'none';
			els.cursor = 'move';
			els.listStyle = 'none';
			els.overflow = 'hidden';
			if (window.ActiveXObject) {
				el.unselectable = "on";
			} else {
				els.mozUserSelect = 'none';
				els.userSelect = 'none';
				els.KhtmlUserSelect = 'none';
			}
		}
		if (!o) {
			o = {};
		}
		return this.each(
			function()
			{
				if (this.isDraggable || !jQuery.iUtil)
					return;
				if (window.ActiveXObject) {
					this.onselectstart = function(){return false;};
					this.ondragstart = function(){return false;};
				}
				var el = this;
				var dhe = o.handle ? jQuery(this).find(o.handle) : jQuery(this);
				if(jQuery.browser.msie) {
					dhe.each(
						function()
						{
							this.unselectable = "on";
						}
					);
				} else {
					dhe.css('-moz-user-select', 'none');
					dhe.css('user-select', 'none');
					dhe.css('-khtml-user-select', 'none');
				}
				this.dragCfg = {
					dhe: dhe,
					revert : o.revert ? true : false,
					ghosting : o.ghosting ? true : false,
					so : o.so ? o.so : false,
					si : o.si ? o.si : false,
					insideParent : o.insideParent ? o.insideParent : false,
					zIndex : o.zIndex ? parseInt(o.zIndex)||0 : false,
					opacity : o.opacity ? parseFloat(o.opacity) : false,
					fx : parseInt(o.fx)||null,
					hpc : o.hpc ? o.hpc : false,
					onDragModifier : {},
					pointer : {},
					onStart : o.onStart && o.onStart.constructor == Function ? o.onStart : false,
					onStop : o.onStop && o.onStop.constructor == Function ? o.onStop : false,
					onChange : o.onChange && o.onChange.constructor == Function ? o.onChange : false,
					axis : /vertically|horizontally/.test(o.axis) ? o.axis : false,
					snapDistance : o.snapDistance ? parseInt(o.snapDistance)||0 : 0,
					cursorAt: o.cursorAt ? o.cursorAt : false,
					autoSize : o.autoSize ? true : false,
					frameClass : o.frameClass || false
					
				};
				if (o.onDragModifier && o.onDragModifier.constructor == Function)
					this.dragCfg.onDragModifier.user = o.onDragModifier;
				if (o.onDrag && o.onDrag.constructor == Function)
					this.dragCfg.onDrag = o.onDrag;
				if (o.containment && ((o.containment.constructor == String && (o.containment == 'parent' || o.containment == 'document')) || (o.containment.constructor == Array && o.containment.length == 4) )) {
					this.dragCfg.containment = o.containment;
				}
				if(o.fractions) {
					this.dragCfg.fractions = o.fractions;
				}
				if(o.grid){
					if(typeof o.grid == 'number'){
						this.dragCfg.gx = parseInt(o.grid)||1;
						this.dragCfg.gy = parseInt(o.grid)||1;
					} else if (o.grid.length == 2) {
						this.dragCfg.gx = parseInt(o.grid[0])||1;
						this.dragCfg.gy = parseInt(o.grid[1])||1;
					}
				}
				if (o.onSlide && o.onSlide.constructor == Function) {
					this.dragCfg.onSlide = o.onSlide;
				}

				this.isDraggable = true;
				dhe.each(
					function(){
						this.dragElem = el;
					}
				);
				dhe.bind('mousedown', jQuery.iDrag.draginit);
			}
		)
	}
};

/**
 * Destroy an existing draggable on a collection of elements
 * 
 * @name DraggableDestroy
 * @descr Destroy a draggable
 * @type jQuery
 * @cat Plugins/Interface
 * @example $('#drag2').DraggableDestroy();
 */

jQuery.fn.extend(
	{
		DraggableDestroy : jQuery.iDrag.destroy,
		Draggable : jQuery.iDrag.build
	}
);

jQuery.iDrop = {
	fit : function (zonex, zoney, zonew, zoneh)
	{
		return 	zonex <= jQuery.iDrag.dragged.dragCfg.nx && 
				(zonex + zonew) >= (jQuery.iDrag.dragged.dragCfg.nx + jQuery.iDrag.dragged.dragCfg.oC.w) &&
				zoney <= jQuery.iDrag.dragged.dragCfg.ny && 
				(zoney + zoneh) >= (jQuery.iDrag.dragged.dragCfg.ny + jQuery.iDrag.dragged.dragCfg.oC.h) ? true :false;
	},
	intersect : function (zonex, zoney, zonew, zoneh)
	{
		return 	! ( zonex > (jQuery.iDrag.dragged.dragCfg.nx + jQuery.iDrag.dragged.dragCfg.oC.w)
				|| (zonex + zonew) < jQuery.iDrag.dragged.dragCfg.nx 
				|| zoney > (jQuery.iDrag.dragged.dragCfg.ny + jQuery.iDrag.dragged.dragCfg.oC.h) 
				|| (zoney + zoneh) < jQuery.iDrag.dragged.dragCfg.ny
				) ? true :false;
	},
	pointer : function (zonex, zoney, zonew, zoneh)
	{
		return	zonex < jQuery.iDrag.dragged.dragCfg.currentPointer.x
				&& (zonex + zonew) > jQuery.iDrag.dragged.dragCfg.currentPointer.x 
				&& zoney < jQuery.iDrag.dragged.dragCfg.currentPointer.y 
				&& (zoney + zoneh) > jQuery.iDrag.dragged.dragCfg.currentPointer.y
				? true :false;
	},
	overzone : false,
	highlighted : {},
	count : 0,
	zones : {},
	
	highlight : function (elm)
	{
		if (jQuery.iDrag.dragged == null) {
			return;
		}
		var i;
		jQuery.iDrop.highlighted = {};
		var oneIsSortable = false;
		for (i in jQuery.iDrop.zones) {
			if (jQuery.iDrop.zones[i] != null) {
				var iEL = jQuery.iDrop.zones[i].get(0);
				if (jQuery(jQuery.iDrag.dragged).is('.' + iEL.dropCfg.a)) {
					if (iEL.dropCfg.m == false) {
						iEL.dropCfg.p = jQuery.extend(
							jQuery.iUtil.getPosition(iEL),
							jQuery.iUtil.getSizeLite(iEL)
						);//jQuery.iUtil.getPos(iEL);
						iEL.dropCfg.m = true;
					}
					if (iEL.dropCfg.ac) {
						jQuery.iDrop.zones[i].addClass(iEL.dropCfg.ac);
					}
					jQuery.iDrop.highlighted[i] = jQuery.iDrop.zones[i];
					//if (jQuery.iSort && jQuery.iDrag.dragged.dragCfg.so) {
					if (jQuery.iSort && iEL.dropCfg.s && jQuery.iDrag.dragged.dragCfg.so) {
						iEL.dropCfg.el = jQuery('.' + iEL.dropCfg.a, iEL);
						elm.style.display = 'none';
						jQuery.iSort.measure(iEL);
						iEL.dropCfg.os = jQuery.iSort.serialize(jQuery.attr(iEL, 'id')).hash;
						elm.style.display = elm.dragCfg.oD;
						oneIsSortable = true;
					}
					if (iEL.dropCfg.onActivate) {
						iEL.dropCfg.onActivate.apply(jQuery.iDrop.zones[i].get(0), [jQuery.iDrag.dragged]);
					}
				}
			}
		}
		//if (jQuery.iSort && jQuery.iDrag.dragged.dragCfg.so) {
		if (oneIsSortable) {
			jQuery.iSort.start();
		}
	},
	/**
	 * remeasure the droppable
	 * 
	 * useful when the positions/dimensions for droppables 
	 * are changed while dragging a element
	 * 
	 * this works for sortables too but with a greate processor 
	 * penality because remeasures each sort items too
	 */
	remeasure : function()
	{
		jQuery.iDrop.highlighted = {};
		for (i in jQuery.iDrop.zones) {
			if (jQuery.iDrop.zones[i] != null) {
				var iEL = jQuery.iDrop.zones[i].get(0);
				if (jQuery(jQuery.iDrag.dragged).is('.' + iEL.dropCfg.a)) {
					iEL.dropCfg.p = jQuery.extend(
						jQuery.iUtil.getPosition(iEL),
						jQuery.iUtil.getSizeLite(iEL)
					);
					if (iEL.dropCfg.ac) {
						jQuery.iDrop.zones[i].addClass(iEL.dropCfg.ac);
					}
					jQuery.iDrop.highlighted[i] = jQuery.iDrop.zones[i];
					
					if (jQuery.iSort && iEL.dropCfg.s && jQuery.iDrag.dragged.dragCfg.so) {
						iEL.dropCfg.el = jQuery('.' + iEL.dropCfg.a, iEL);
						elm.style.display = 'none';
						jQuery.iSort.measure(iEL);
						elm.style.display = elm.dragCfg.oD;
					}
				}
			}
		}
	},
	
	checkhover : function (e)
	{
		if (jQuery.iDrag.dragged == null) {
			return;
		}
		jQuery.iDrop.overzone = false;
		var i;
		var applyOnHover = false;
		var hlt = 0;
		for (i in jQuery.iDrop.highlighted)
		{
			var iEL = jQuery.iDrop.highlighted[i].get(0);
			if ( 
					jQuery.iDrop.overzone == false
					 && 
					jQuery.iDrop[iEL.dropCfg.t](
					 	iEL.dropCfg.p.x, 
						iEL.dropCfg.p.y, 
						iEL.dropCfg.p.wb, 
						iEL.dropCfg.p.hb
					)
					 
			) {
				if (iEL.dropCfg.hc && iEL.dropCfg.h == false) {
					jQuery.iDrop.highlighted[i].addClass(iEL.dropCfg.hc);
				}
				//chec if onHover function has to be called
				if (iEL.dropCfg.h == false &&iEL.dropCfg.onHover) {
					applyOnHover = true;
				}
				iEL.dropCfg.h = true;
				jQuery.iDrop.overzone = iEL;
				//if(jQuery.iSort && jQuery.iDrag.dragged.dragCfg.so) {
				if(jQuery.iSort && iEL.dropCfg.s && jQuery.iDrag.dragged.dragCfg.so) {
					jQuery.iSort.helper.get(0).className = iEL.dropCfg.shc;
					jQuery.iSort.checkhover(iEL);
				}
				hlt ++;
			} else if(iEL.dropCfg.h == true) {
				//onOut function
				if (iEL.dropCfg.onOut) {
					iEL.dropCfg.onOut.apply(iEL, [e, jQuery.iDrag.helper.get(0).firstChild, iEL.dropCfg.fx]);
				}
				if (iEL.dropCfg.hc) {
					jQuery.iDrop.highlighted[i].removeClass(iEL.dropCfg.hc);
				}
				iEL.dropCfg.h = false;
			}
		}
		if (jQuery.iSort && !jQuery.iDrop.overzone && jQuery.iDrag.dragged.so) {
			jQuery.iSort.helper.get(0).style.display = 'none';
			//jQuery('body').append(jQuery.iSort.helper.get(0));
		}
		//call onhover
		if(applyOnHover) {
			jQuery.iDrop.overzone.dropCfg.onHover.apply(jQuery.iDrop.overzone, [e, jQuery.iDrag.helper.get(0).firstChild]);
		}
	},
	checkdrop : function (e)
	{
		var i;
		for (i in jQuery.iDrop.highlighted) {
			var iEL = jQuery.iDrop.highlighted[i].get(0);
			if (iEL.dropCfg.ac) {
				jQuery.iDrop.highlighted[i].removeClass(iEL.dropCfg.ac);
			}
			if (iEL.dropCfg.hc) {
				jQuery.iDrop.highlighted[i].removeClass(iEL.dropCfg.hc);
			}
			if(iEL.dropCfg.s) {
				jQuery.iSort.changed[jQuery.iSort.changed.length] = i;
			}
			if (iEL.dropCfg.onDrop && iEL.dropCfg.h == true) {
				iEL.dropCfg.h = false;
				iEL.dropCfg.onDrop.apply(iEL, [e, iEL.dropCfg.fx]);
			}
			iEL.dropCfg.m = false;
			iEL.dropCfg.h  = false;
		}
		jQuery.iDrop.highlighted = {};
	},
	destroy : function()
	{
		return this.each(
			function()
			{
				if (this.isDroppable) {
					if (this.dropCfg.s) {
						id = jQuery.attr(this,'id');
						jQuery.iSort.collected[id] = null;
						jQuery('.' + this.dropCfg.a, this).DraggableDestroy();
					}
					jQuery.iDrop.zones['d' + this.idsa] = null;
					this.isDroppable = false;
					this.f = null;
				}
			}
		);
	},
	build : function (o)
	{
		return this.each(
			function()
			{
				if (this.isDroppable == true || !o.accept || !jQuery.iUtil || !jQuery.iDrag){
					return;
				}
				this.dropCfg = {
					a : o.accept,
					ac: o.activeclass||false, 
					hc:	o.hoverclass||false,
					shc: o.helperclass||false,
					onDrop:	o.ondrop||o.onDrop||false,
					onHover: o.onHover||o.onhover||false,
					onOut: o.onOut||o.onout||false,
					onActivate: o.onActivate||false,
					t: o.tolerance && ( o.tolerance == 'fit' || o.tolerance == 'intersect') ? o.tolerance : 'pointer',
					fx: o.fx ? o.fx : false,
					m: false,
					h: false
				};
				if (o.sortable == true && jQuery.iSort) {
					id = jQuery.attr(this,'id');
					jQuery.iSort.collected[id] = this.dropCfg.a;
					this.dropCfg.s = true;
					if(o.onChange) {
						this.dropCfg.onChange = o.onChange;
						this.dropCfg.os = jQuery.iSort.serialize(id).hash;
					}
				}
				this.isDroppable = true;
				this.idsa = parseInt(Math.random() * 10000);
				jQuery.iDrop.zones['d' + this.idsa] = jQuery(this);
				jQuery.iDrop.count ++;
			}
		);
	}
};

/**
 * Destroy an existing droppable on a collection of elements
 * 
 * @name DroppableDestroy
 * @descr Destroy a droppable
 * @type jQuery
 * @cat Plugins/Interface
 * @example $('#drag2').DroppableDestroy();
 */

jQuery.fn.extend(
	{
		DroppableDestroy : jQuery.iDrop.destroy,
		Droppable : jQuery.iDrop.build
	}
);

 
/**
 * Recalculate all Droppables
 *
 * @name $.recallDroppables
 * @type jQuery
 * @cat Plugins/Interface
 * @example $.recallDroppable();
 */

jQuery.recallDroppables = jQuery.iDrop.remeasure;




jQuery.iSort = {
	changed : [],
	collected : {},
	helper : false,
	inFrontOf: null,
	
	start : function ()
	{
		if (jQuery.iDrag.dragged == null) {
			return;
		}
		var shs, margins,c, cs;
		
		jQuery.iSort.helper.get(0).className = jQuery.iDrag.dragged.dragCfg.hpc;
		shs = jQuery.iSort.helper.get(0).style;
		shs.display = 'block';
		jQuery.iSort.helper.oC = jQuery.extend(
			jQuery.iUtil.getPosition(jQuery.iSort.helper.get(0)),
			jQuery.iUtil.getSize(jQuery.iSort.helper.get(0))
		);
		
		shs.width = jQuery.iDrag.dragged.dragCfg.oC.wb + 'px';
		shs.height = jQuery.iDrag.dragged.dragCfg.oC.hb + 'px';
		//shs.cssFloat = jQuery.iDrag.dragged.dragCfg.oF;
		margins = jQuery.iUtil.getMargins(jQuery.iDrag.dragged);
		shs.marginTop = margins.t;
		shs.marginRight = margins.r;
		shs.marginBottom = margins.b;
		shs.marginLeft = margins.l;
		if (jQuery.iDrag.dragged.dragCfg.ghosting == true) {
			c = jQuery.iDrag.dragged.cloneNode(true);
			cs = c.style;
			cs.marginTop = '0px';
			cs.marginRight = '0px';
			cs.marginBottom = '0px';
			cs.marginLeft = '0px';
			cs.display = 'block';
			jQuery.iSort.helper.empty().append(c);
		}
		jQuery(jQuery.iDrag.dragged).after(jQuery.iSort.helper.get(0));
		jQuery.iDrag.dragged.style.display = 'none';
	},
	
	check : function (e)
	{
		if (!e.dragCfg.so && jQuery.iDrop.overzone.sortable) {
			if (e.dragCfg.onStop)
				e.dragCfg.onStop.apply(dragged);
			jQuery(e).css('position', e.dragCfg.initialPosition || e.dragCfg.oP);
			jQuery(e).DraggableDestroy();
			jQuery(jQuery.iDrop.overzone).SortableAddItem(e);
		}
		jQuery.iSort.helper.removeClass(e.dragCfg.hpc).html('&nbsp;');
		jQuery.iSort.inFrontOf = null;
		var shs = jQuery.iSort.helper.get(0).style;
		shs.display = 'none';
		jQuery.iSort.helper.after(e);
		if (e.dragCfg.fx > 0) {
			jQuery(e).fadeIn(e.dragCfg.fx);
		}
		jQuery('body').append(jQuery.iSort.helper.get(0));
		var ts = [];
		var fnc = false;
		for(var i=0; i<jQuery.iSort.changed.length; i++){
			var iEL = jQuery.iDrop.zones[jQuery.iSort.changed[i]].get(0);
			var id = jQuery.attr(iEL, 'id');
			var ser = jQuery.iSort.serialize(id);
			if (iEL.dropCfg.os != ser.hash) {
				iEL.dropCfg.os = ser.hash;
				if (fnc == false && iEL.dropCfg.onChange) {
					fnc = iEL.dropCfg.onChange;
				}
				ser.id = id;
				ts[ts.length] = ser;
			}
		}
		jQuery.iSort.changed = [];
		if (fnc != false && ts.length > 0) {
			fnc(ts);
		}
	},
	
	checkhover : function(e,o)
	{
		if (!jQuery.iDrag.dragged)
			return;
		var cur = false;
		var i = 0;
		if ( e.dropCfg.el.size() > 0) {
			for (i = e.dropCfg.el.size(); i >0; i--) {
				if (e.dropCfg.el.get(i-1) != jQuery.iDrag.dragged) {
					if (!e.sortCfg.floats) {
						if ( 
						(e.dropCfg.el.get(i-1).pos.y + e.dropCfg.el.get(i-1).pos.hb/2) > jQuery.iDrag.dragged.dragCfg.ny  
						) {
							cur = e.dropCfg.el.get(i-1);
						} else {
							break;
						}
					} else {
						if (
						(e.dropCfg.el.get(i-1).pos.x + e.dropCfg.el.get(i-1).pos.wb/2) > jQuery.iDrag.dragged.dragCfg.nx && 
						(e.dropCfg.el.get(i-1).pos.y + e.dropCfg.el.get(i-1).pos.hb/2) > jQuery.iDrag.dragged.dragCfg.ny  
						) {
							cur = e.dropCfg.el.get(i-1);
						}
					}
				}
			}
		}
		//helpos = jQuery.iUtil.getPos(jQuery.iSort.helper.get(0));
		if (cur && jQuery.iSort.inFrontOf != cur) {
			jQuery.iSort.inFrontOf = cur;
			jQuery(cur).before(jQuery.iSort.helper.get(0));
		} else if(!cur && (jQuery.iSort.inFrontOf != null || jQuery.iSort.helper.get(0).parentNode != e) ) {
			jQuery.iSort.inFrontOf = null;
			jQuery(e).append(jQuery.iSort.helper.get(0));
		}
		jQuery.iSort.helper.get(0).style.display = 'block';
	},
	
	measure : function (e)
	{
		if (jQuery.iDrag.dragged == null) {
			return;
		}
		e.dropCfg.el.each (
			function ()
			{
				this.pos = jQuery.extend(
					jQuery.iUtil.getSizeLite(this),
					jQuery.iUtil.getPosition(this)
				);
			}
		);
	},
	
	serialize : function(s)
	{
		var i;
		var h = '';
		var o = {};
		if (s) {
			if (jQuery.iSort.collected[s] ) {
				o[s] = [];
				jQuery('#' + s + ' .' + jQuery.iSort.collected[s]).each(
					function ()
					{
						if (h.length > 0) {
							h += '&';
						}
						h += s + '[]=' + jQuery.attr(this,'id');
						o[s][o[s].length] = jQuery.attr(this,'id');
					}
				);
			} else {
				for ( a in s) {
					if (jQuery.iSort.collected[s[a]] ) {
						o[s[a]] = [];			
						jQuery('#' + s[a] + ' .' + jQuery.iSort.collected[s[a]]).each(
							function ()
							{
								if (h.length > 0) {
									h += '&';
								}
								h += s[a] + '[]=' + jQuery.attr(this,'id');
								o[s[a]][o[s[a]].length] = jQuery.attr(this,'id');
							}
						);
					}
				}
			}
		} else {
			for ( i in jQuery.iSort.collected){
				o[i] = [];
				jQuery('#' + i + ' .' + jQuery.iSort.collected[i]).each(
					function ()
					{
						if (h.length > 0) {
							h += '&';
						}
						h += i + '[]=' + jQuery.attr(this,'id');
						o[i][o[i].length] = jQuery.attr(this,'id');
					}
				);
			}
		}
		return {hash:h, o:o};
	},
	
	addItem : function (e)
	{
		if ( !e.childNodes ) {
			return;
		}
		return this.each(
			function ()
			{
				if(!this.sortCfg || !jQuery(e).is('.' +  this.sortCfg.accept))
					jQuery(e).addClass(this.sortCfg.accept);
				jQuery(e).Draggable(this.sortCfg.dragCfg);
			}
		);
	},
	
	destroy: function()
	{
		return this.each(
			function()
			{
				jQuery('.' + this.sortCfg.accept).DraggableDestroy();
				jQuery(this).DroppableDestroy();
				this.sortCfg = null;
				this.isSortable = null;
			}
		);
	},
	
	build : function (o)
	{
		if (o.accept && jQuery.iUtil && jQuery.iDrag && jQuery.iDrop) {
			if (!jQuery.iSort.helper) {
				jQuery('body',document).append('<div id="sortHelper">&nbsp;</div>');
				jQuery.iSort.helper = jQuery('#sortHelper');
				jQuery.iSort.helper.get(0).style.display = 'none';
			}
			this.Droppable(
				{
					accept :  o.accept,
					activeclass : o.activeclass ? o.activeclass : false,
					hoverclass : o.hoverclass ? o.hoverclass : false,
					helperclass : o.helperclass ? o.helperclass : false,
					/*onDrop: function (drag, fx) 
							{
								jQuery.iSort.helper.after(drag);
								if (fx > 0) {
									jQuery(drag).fadeIn(fx);
								}
							},*/
					onHover: o.onHover||o.onhover,
					onOut: o.onOut||o.onout,
					sortable : true,
					onChange : 	o.onChange||o.onchange,
					fx : o.fx ? o.fx : false,
					ghosting : o.ghosting ? true : false,
					tolerance: o.tolerance ? o.tolerance : 'intersect'
				}
			);
			
			return this.each(
				function()
				{
					var dragCfg = {
						revert : o.revert? true : false,
						zindex : 3000,
						opacity : o.opacity ? parseFloat(o.opacity) : false,
						hpc : o.helperclass ? o.helperclass : false,
						fx : o.fx ? o.fx : false,
						so : true,
						ghosting : o.ghosting ? true : false,
						handle: o.handle ? o.handle : null,
						containment: o.containment ? o.containment : null,
						onStart : o.onStart && o.onStart.constructor == Function ? o.onStart : false,
						onDrag : o.onDrag && o.onDrag.constructor == Function ? o.onDrag : false,
						onStop : o.onStop && o.onStop.constructor == Function ? o.onStop : false,
						axis : /vertically|horizontally/.test(o.axis) ? o.axis : false,
						snapDistance : o.snapDistance ? parseInt(o.snapDistance)||0 : false,
						cursorAt: o.cursorAt ? o.cursorAt : false
					};
					jQuery('.' + o.accept, this).Draggable(dragCfg);
					this.isSortable = true;
					this.sortCfg = {
						accept :  o.accept,
						revert : o.revert? true : false,
						zindex : 3000,
						opacity : o.opacity ? parseFloat(o.opacity) : false,
						hpc : o.helperclass ? o.helperclass : false,
						fx : o.fx ? o.fx : false,
						so : true,
						ghosting : o.ghosting ? true : false,
						handle: o.handle ? o.handle : null,
						containment: o.containment ? o.containment : null,
						floats: o.floats ? true : false,
						dragCfg : dragCfg
					}
				}
			);
		}
	}
};

jQuery.fn.extend(
	{
		Sortable : jQuery.iSort.build,
		/**
		 * A new item can be added to a sortable by adding it to the DOM and then adding it via
		 * SortableAddItem. 
		 *
		 * @name SortableAddItem
		 * @param DOMElement elem A DOM Element to add to the sortable list
		 * @example $('#sortable1').append('<li id="newitem">new item</li>')
		 *                         .SortableAddItem($("#new_item")[0])
		 * @type jQuery
		 * @cat Plugins/Interface
		 */
		SortableAddItem : jQuery.iSort.addItem,
		/**
		 * Destroy a sortable
		 *
		 * @name SortableDestroy
		 * @example $('#sortable1').SortableDestroy();
		 * @type jQuery
		 * @cat Plugins/Interface
		 */
		SortableDestroy: jQuery.iSort.destroy
	}
);

/**
 * This function returns the hash and an object (can be used as arguments for $.post) for every 
 * sortable in the page or specific sortables. The hash is based on the 'id' attributes of 
 * container and items.
 *
 * @params String sortable The id of the sortable to serialize
 * @name $.SortSerialize
 * @type String
 * @cat Plugins/Interface
 */

jQuery.SortSerialize = jQuery.iSort.serialize;