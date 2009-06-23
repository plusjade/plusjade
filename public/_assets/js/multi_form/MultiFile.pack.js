/*
 ### jQuery Multiple File Upload Plugin v1.31 - 2009-01-17 ###
 * Home: http://www.fyneworks.com/jquery/multiple-file-upload/
 * Code: http://code.google.com/p/jquery-multifile-plugin/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 ###
*/
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}(';2(J.1C)(3($){$.B($,{6:3(o){7 $("1f:m.2J").6(o)}});$.B($.6,{19:{j:\'\',k:-1,1v:3(s){2($.1h){$.1h({2C:s.u(/\\n/p,\'<2z/>\'),18:{17:\'2x\',2v:\'2u\',2t:\'12.2r\',2n:\'#2m\',2l:\'#2k\',2j:\'.8\',\'-2i-17-1n\':\'1o\',\'-2h-17-1n\':\'1o\'}});J.2g($.2d,2b)}1w{2a(s)}},1F:\'$F\',A:{O:\'x\',1g:\'28 27 26 a $13 m.\\24 21...\',m:\'$m\',X:\'20 X: $m\',1t:\'1Y m 1X 1W 1U X:\\n$m\'}}});$.B($.6,{Z:3(a){q o=[];$(\'1f:m\').L(3(){2($(5).15()==\'\')o[o.16]=5});7 $(o).L(3(){5.W=V}).1i(a||\'1j\')},Y:3(a){a=a||\'1j\';7 $(\'1f:m.\'+a).1T(a).L(3(){5.W=y})},P:[\'1O\',\'1N\',\'1M\'],14:{},1r:3(b,c,d){q e,l;d=d||[];2(d.1x.1y().1z("1A")<0)d=[d];2(1a(b)==\'3\'){$.6.Z();l=b.1D(c||J,d);$.6.Y();7 l};2(b.1x.1y().1z("1A")<0)b=[b];1E(q i=0;i<b.16;i++){e=b[i]+\'\';2(e)(3(a){$.6.14[a]=$.1c[a]||3(){};$.1c[a]=3(){$.6.Z();l=$.6.14[a].1D(5,1L);$.6.Y();7 l}})(e)}}});$.B($.1c,{1e:3(){7 5.L(3(){1K{5.1e()}2B(e){}})},6:3(h){2($.6.P){$.6.1r($.6.P);$.6.P=N};7 $(5).L(3(e){2(5.1q)7;5.1q=V;J.6=(J.6||0)+1;e=J.6;q g={e:5,E:$(5),T:$(5).T()};2(1a h==\'1P\')h={k:h};2(1a h==\'1Q\')h={j:h};q o=$.B({},$.6.19,h||{},($.1R?g.E.1S():($.1B?g.E.1B():N))||{});2(!(o.k>0)){o.k=g.E.I(\'1V\');2(!(o.k>0)){o.k=(t(g.e.1u.C(/\\b(k|1Z)\\-([0-9]+)\\b/p)||[\'\']).C(/[0-9]+/p)||[\'\'])[0];2(!(o.k>0))o.k=-1;1w o.k=t(o.k).C(/[0-9]+/p)[0]}};o.k=1d 22(o.k);o.j=o.j||g.E.I(\'j\')||\'\';2(!o.j){o.j=(g.e.1u.C(/\\b(j\\-[\\w\\|]+)\\b/p))||\'\';o.j=1d t(o.j).u(/^(j|13)\\-/i,\'\')};$.B(g,o||{});g.A=$.B({},$.6.19.A,g.A);$.B(g,{n:0,H:[],25:[],1b:g.e.D||\'6\'+t(e),1J:3(z){7 g.1b+(z>0?\'29\'+t(z):\'\')},G:3(a,b){q c=g[a],l=$(b).I(\'l\');2(c){q d=c(b,l,g);2(d!=N)7 d}7 V}});2(t(g.j).16>1){g.1s=1d 2c(\'\\\\.(\'+(g.j?g.j:\'\')+\')$\',\'p\')};g.K=g.1b+\'2e\';g.E.2f(\'<U D="\'+g.K+\'"></U>\');g.11=$(\'#\'+g.K+\'\');g.e.F=g.e.F||\'m\'+e+\'[]\';2(!g.M){g.11.10(\'<S D="\'+g.K+\'1I"></S>\');g.M=$(\'#\'+g.K+\'1I\')};g.M=$(g.M);g.Q=3(c,d){g.n++;c.1G=g;c.i=d;2(c.i>0)c.D=c.F=N;c.D=c.D||g.1J(c.i);c.F=t(g.1F.u(/\\$F/p,g.E.I(\'F\')).u(/\\$D/p,g.E.I(\'D\')).u(/\\$g/p,(e>0?e:\'\')).u(/\\$i/p,(d>0?d:\'\')));$(c).15(\'\').I(\'l\',\'\')[0].l=\'\';2((g.k>0)&&((g.n-1)>(g.k)))c.W=V;g.R=g.H[c.i]=c;c=$(c);$(c).2o(3(){$(5).2p();2(!g.G(\'2q\',5,g))7 y;q a=\'\',v=t(5.l||\'\');2(g.j&&v&&!v.C(g.1s))a=g.A.1g.u(\'$13\',t(v.C(/\\.\\w{1,4}$/p)));1E(q f 2s g.H)2(g.H[f]&&g.H[f]!=5)2(g.H[f].l==v)a=g.A.1t.u(\'$m\',v.C(/[^\\/\\\\]+$/p));q b=$(g.T).T();b.1i(\'6\');2(a!=\'\'){g.1v(a);g.n--;g.Q(b[0],5.i);c.1p().1m(b);c.O();7 y};$(5).18({1l:\'2w\',1H:\'-2y\'});g.11.1m(b);g.1k(5);g.Q(b[0],5.i+1);2(!g.G(\'2A\',5,g))7 y})};g.1k=3(c){2(!g.G(\'2K\',c,g))7 y;q r=$(\'<U></U>\'),v=t(c.l||\'\'),a=$(\'<S 2D="m" 2E="\'+g.A.X.u(\'$m\',v)+\'">\'+g.A.m.u(\'$m\',v.C(/[^\\/\\\\]+$/p)[0])+\'</S>\'),b=$(\'<a 2F="#\'+g.K+\'">\'+g.A.O+\'</a>\');g.M.10(r.10(b,\' \',a));b.2G(3(){2(!g.G(\'2H\',c,g))7 y;g.n--;g.R.W=y;g.H[c.i]=N;$(c).O();$(5).1p().O();$(g.R).18({1l:\'\',1H:\'\'});$(g.R).1e().15(\'\').I(\'l\',\'\')[0].l=\'\';2(!g.G(\'2I\',c,g))7 y;7 y});2(!g.G(\'23\',c,g))7 y};2(!g.1G)g.Q(g.e,0);g.n++})}});$(3(){$.6()})})(1C);',62,171,'||if|function||this|MultiFile|return||||||||||||accept|max|value|file|||gi|var|||String|replace||||false||STRING|extend|match|id||name|trigger|slaves|attr|window|wrapID|each|list|null|remove|autoIntercept|addSlave|current|span|clone|div|true|disabled|selected|reEnableEmpty|disableEmpty|append|wrapper||ext|intercepted|val|length|border|css|options|typeof|instanceKey|fn|new|reset|input|denied|blockUI|addClass|mfD|addToList|position|prepend|radius|10px|parent|_MultiFile|intercept|rxAccept|duplicate|className|error|else|constructor|toString|indexOf|Array|metadata|jQuery|apply|for|namePattern|MF|top|_list|generateID|try|arguments|validate|ajaxSubmit|submit|number|string|meta|data|removeClass|been|maxlength|already|has|This|limit|File|again|Number|afterFileAppend|nTry|files|select|cannot|You|_F|alert|2000|RegExp|unblockUI|_wrap|wrap|setTimeout|moz|webkit|opacity|fff|color|900|backgroundColor|change|blur|onFileSelect|0pt|in|size|15px|padding|absolute|none|3000px|br|afterFileSelect|catch|message|class|title|href|click|onFileRemove|afterFileRemove|multi|onFileAppend'.split('|'),0,{}));



/*
 * jQuery blockUI plugin
 * Version 2.04 (04/30/2008)
 * @requires jQuery v1.2.3 or later
 *
 * Examples at: http://malsup.com/jquery/block/
 * Copyright (c) 2007-2008 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * 
 * Thanks to Amir-Hossein Sobhi for some excellent contributions!
 */

(function($) {

if (/1\.(0|1|2)\.(0|1|2)/.test($.fn.jquery) || /^1.1/.test($.fn.jquery)) {
    alert('blockUI requires jQuery v1.2.3 or later!  You are using v' + $.fn.jquery);
    return;
}

// global $ methods for blocking/unblocking the entire page
$.blockUI   = function(opts) { install(window, opts); };
$.unblockUI = function(opts) { remove(window, opts); };

// plugin method for blocking element content
$.fn.block = function(opts) {
    return this.each(function() {
        if ($.css(this,'position') == 'static')
            this.style.position = 'relative';
        if ($.browser.msie) 
            this.style.zoom = 1; // force 'hasLayout'
        install(this, opts);
    });
};

// plugin method for unblocking element content
$.fn.unblock = function(opts) {
    return this.each(function() {
        remove(this, opts);
    });
};

$.blockUI.version = 2.04; // 2nd generation blocking at no extra cost!

// override these in your code to change the default behavior and style
$.blockUI.defaults = {
    // message displayed when blocking (use null for no message)
    message:  '<h1>Please wait...</h1>',
    
    // styles for the message when blocking; if you wish to disable
    // these and use an external stylesheet then do this in your code:
    // $.blockUI.defaults.css = {};
    css: { 
        padding:        0,
        margin:         0,
        width:          '30%', 
        top:            '40%', 
        left:           '35%', 
        textAlign:      'center', 
        color:          '#000', 
        border:         '3px solid #aaa',
        backgroundColor:'#fff',
        cursor:         'wait'
    },
    
    // styles for the overlay
    overlayCSS:  { 
        backgroundColor:'#000', 
        opacity:        '0.6' 
    },
    
    // z-index for the blocking overlay
    baseZ: 1000,
    
    // set these to true to have the message automatically centered
    centerX: true, // <-- only effects element blocking (page block controlled via css above)
    centerY: true,
    
    // allow body element to be stetched in ie6; this makes blocking look better
    // on "short" pages.  disable if you wish to prevent changes to the body height
    allowBodyStretch: true,
    
    // be default blockUI will supress tab navigation from leaving blocking content;
    constrainTabKey: true,
    
    // fadeOut time in millis; set to 0 to disable fadeout on unblock
    fadeOut:  400,
    
    // suppresses the use of overlay styles on FF/Linux (due to significant performance issues with opacity)
    applyPlatformOpacityRules: true
};

// private data and functions follow...

var ie6 = $.browser.msie && /MSIE 6.0/.test(navigator.userAgent);
var pageBlock = null;
var pageBlockEls = [];

function install(el, opts) {
    var full = (el == window);
    var msg = opts && opts.message !== undefined ? opts.message : undefined;
    opts = $.extend({}, $.blockUI.defaults, opts || {});
    opts.overlayCSS = $.extend({}, $.blockUI.defaults.overlayCSS, opts.overlayCSS || {});
    var css = $.extend({}, $.blockUI.defaults.css, opts.css || {});
    msg = msg === undefined ? opts.message : msg;

    // remove the current block (if there is one)
    if (full && pageBlock) 
        remove(window, {fadeOut:0}); 
    
    // if an existing element is being used as the blocking content then we capture
    // its current place in the DOM (and current display style) so we can restore
    // it when we unblock
    if (msg && typeof msg != 'string' && (msg.parentNode || msg.jquery)) {
        var node = msg.jquery ? msg[0] : msg;
        var data = {};
        $(el).data('blockUI.history', data);
        data.el = node;
        data.parent = node.parentNode;
        data.display = node.style.display;
        data.parent.removeChild(node);
    }
    
    var z = opts.baseZ;
    
    // blockUI uses 3 layers for blocking, for simplicity they are all used on every platform;
    // layer1 is the iframe layer which is used to supress bleed through of underlying content
    // layer2 is the overlay layer which has opacity and a wait cursor
    // layer3 is the message content that is displayed while blocking
    
    var lyr1 = ($.browser.msie) ? $('<iframe class="blockUI" style="z-index:'+ z++ +';border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="javascript:false;"></iframe>')
                                : $('<div class="blockUI" style="display:none"></div>');
    var lyr2 = $('<div class="blockUI" style="z-index:'+ z++ +';cursor:wait;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>');
    var lyr3 = full ? $('<div class="blockUI blockMsg blockPage" style="z-index:'+z+';position:fixed"></div>')
                    : $('<div class="blockUI blockMsg blockElement" style="z-index:'+z+';display:none;position:absolute"></div>');

    // if we have a message, style it
    if (msg) 
        lyr3.css(css);

    // style the overlay
    if (!opts.applyPlatformOpacityRules || !($.browser.mozilla && /Linux/.test(navigator.platform))) 
        lyr2.css(opts.overlayCSS);
    lyr2.css('position', full ? 'fixed' : 'absolute');
    
    // make iframe layer transparent in IE
    if ($.browser.msie) 
        lyr1.css('opacity','0.0');

    $([lyr1[0],lyr2[0],lyr3[0]]).appendTo(full ? 'body' : el);
    
    // ie7 must use absolute positioning in quirks mode and to account for activex issues (when scrolling)
    var expr = $.browser.msie && (!$.boxModel || $('object,embed', full ? null : el).length > 0);
    if (ie6 || expr) {
        // give body 100% height
        if (full && opts.allowBodyStretch && $.boxModel)
            $('html,body').css('height','100%');

        // fix ie6 issue when blocked element has a border width
        if ((ie6 || !$.boxModel) && !full) {
            var t = sz(el,'borderTopWidth'), l = sz(el,'borderLeftWidth');
            var fixT = t ? '(0 - '+t+')' : 0;
            var fixL = l ? '(0 - '+l+')' : 0;
        }

        // simulate fixed position
        $.each([lyr1,lyr2,lyr3], function(i,o) {
            var s = o[0].style;
            s.position = 'absolute';
            if (i < 2) {
                full ? s.setExpression('height','document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight + "px"')
                     : s.setExpression('height','this.parentNode.offsetHeight + "px"');
                full ? s.setExpression('width','jQuery.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"')
                     : s.setExpression('width','this.parentNode.offsetWidth + "px"');
                if (fixL) s.setExpression('left', fixL);
                if (fixT) s.setExpression('top', fixT);
            }
            else if (opts.centerY) {
                if (full) s.setExpression('top','(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"');
                s.marginTop = 0;
            }
        });
    }
    
    // show the message
    lyr3.append(msg).show();
    if (msg && (msg.jquery || msg.nodeType))
        $(msg).show();

    // bind key and mouse events
    bind(1, el, opts);
        
    if (full) {
        pageBlock = lyr3[0];
        pageBlockEls = $(':input:enabled:visible',pageBlock);
        setTimeout(focus, 20);
    }
    else
        center(lyr3[0], opts.centerX, opts.centerY);
};

// remove the block
function remove(el, opts) {
    var full = el == window;
    var data = $(el).data('blockUI.history');
    opts = $.extend(true, {}, $.blockUI.defaults, opts);
    bind(0, el, opts); // unbind events
    var els = full ? $('body > .blockUI') : $('.blockUI', el);
    if (full) 
        pageBlock = pageBlockEls = null;

    if (opts.fadeOut) {
        els.fadeOut(opts.fadeOut);
        setTimeout(function() { reset(els,data); }, opts.fadeOut);
    }
    else
        reset(els, data);
};

// move blocking element back into the DOM where it started
function reset(els,data) {
    els.each(function(i,o) {
        // remove via DOM calls so we don't lose event handlers
        if (this.parentNode) 
            this.parentNode.removeChild(this);
    });
    if (data && data.el) {
        data.el.style.display = data.display;
        data.parent.appendChild(data.el);
        $(data.el).removeData('blockUI.history');
    }
};

// bind/unbind the handler
function bind(b, el, opts) {
    var full = el == window, $el = $(el);
    
    // don't bother unbinding if there is nothing to unbind
    if (!b && (full && !pageBlock || !full && !$el.data('blockUI.isBlocked'))) 
        return;
    if (!full) 
        $el.data('blockUI.isBlocked', b);
        
    // bind anchors and inputs for mouse and key events
    var events = 'mousedown mouseup keydown keypress click';
    b ? $(document).bind(events, opts, handler) : $(document).unbind(events, handler);

// former impl...
//    var $e = $('a,:input');
//    b ? $e.bind(events, opts, handler) : $e.unbind(events, handler);
};

// event handler to suppress keyboard/mouse events when blocking
function handler(e) {
    // allow tab navigation (conditionally)
    if (e.keyCode && e.keyCode == 9) {
        if (pageBlock && e.data.constrainTabKey) {
            var els = pageBlockEls;
            var fwd = !e.shiftKey && e.target == els[els.length-1];
            var back = e.shiftKey && e.target == els[0];
            if (fwd || back) {
                setTimeout(function(){focus(back)},10);
                return false;
            }
        }
    }
    // allow events within the message content
    if ($(e.target).parents('div.blockMsg').length > 0)
        return true;
        
    // allow events for content that is not being blocked
    return $(e.target).parents().children().filter('div.blockUI').length == 0;
};

function focus(back) {
    if (!pageBlockEls) 
        return;
    var e = pageBlockEls[back===true ? pageBlockEls.length-1 : 0];
    if (e) 
        e.focus();
};

function center(el, x, y) {
    var p = el.parentNode, s = el.style;
    var l = ((p.offsetWidth - el.offsetWidth)/2) - sz(p,'borderLeftWidth');
    var t = ((p.offsetHeight - el.offsetHeight)/2) - sz(p,'borderTopWidth');
    if (x) s.left = l > 0 ? (l+'px') : '0';
    if (y) s.top  = t > 0 ? (t+'px') : '0';
};

function sz(el, p) { 
    return parseInt($.css(el,p))||0; 
};

})(jQuery);
