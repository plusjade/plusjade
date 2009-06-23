;(function(h){var m=h.scrollTo=function(b,c,g){h(window).scrollTo(b,c,g)};m.defaults={axis:'y',duration:1};m.window=function(b){return h(window).scrollable()};h.fn.scrollable=function(){return this.map(function(){var b=this.parentWindow||this.defaultView,c=this.nodeName=='#document'?b.frameElement||b:this,g=c.contentDocument||(c.contentWindow||c).document,i=c.setInterval;return c.nodeName=='IFRAME'||i&&h.browser.safari?g.body:i?g.documentElement:this})};h.fn.scrollTo=function(r,j,a){if(typeof j=='object'){a=j;j=0}if(typeof a=='function')a={onAfter:a};a=h.extend({},m.defaults,a);j=j||a.speed||a.duration;a.queue=a.queue&&a.axis.length>1;if(a.queue)j/=2;a.offset=n(a.offset);a.over=n(a.over);return this.scrollable().each(function(){var k=this,o=h(k),d=r,l,e={},p=o.is('html,body');switch(typeof d){case'number':case'string':if(/^([+-]=)?\d+(px)?$/.test(d)){d=n(d);break}d=h(d,this);case'object':if(d.is||d.style)l=(d=h(d)).offset()}h.each(a.axis.split(''),function(b,c){var g=c=='x'?'Left':'Top',i=g.toLowerCase(),f='scroll'+g,s=k[f],t=c=='x'?'Width':'Height',v=t.toLowerCase();if(l){e[f]=l[i]+(p?0:s-o.offset()[i]);if(a.margin){e[f]-=parseInt(d.css('margin'+g))||0;e[f]-=parseInt(d.css('border'+g+'Width'))||0}e[f]+=a.offset[i]||0;if(a.over[i])e[f]+=d[v]()*a.over[i]}else e[f]=d[i];if(/^\d+$/.test(e[f]))e[f]=e[f]<=0?0:Math.min(e[f],u(t));if(!b&&a.queue){if(s!=e[f])q(a.onAfterFirst);delete e[f]}});q(a.onAfter);function q(b){o.animate(e,j,a.easing,b&&function(){b.call(this,r,a)})};function u(b){var c='scroll'+b,g=k.ownerDocument;return p?Math.max(g.documentElement[c],g.body[c]):k[c]}}).end()};function n(b){return typeof b=='object'?b:{top:b,left:b}}})(jQuery);
;(function($){var g=location.href.replace(/#.*/,''),h=$.localScroll=function(a){$('body').localScroll(a)};h.defaults={duration:1e3,axis:'y',event:'click',stop:1};h.hash=function(a){a=$.extend({},h.defaults,a);a.hash=0;if(location.hash)setTimeout(function(){i(0,location,a)},0)};$.fn.localScroll=function(b){b=$.extend({},h.defaults,b);return(b.persistent||b.lazy)?this.bind(b.event,function(e){var a=$([e.target,e.target.parentNode]).filter(c)[0];a&&i(e,a,b)}):this.find('a,area').filter(c).bind(b.event,function(e){i(e,this,b)}).end().end();function c(){var a=this;return!!a.href&&!!a.hash&&a.href.replace(a.hash,'')==g&&(!b.filter||$(a).is(b.filter))}};function i(e,a,b){var c=a.hash.slice(1),d=document.getElementById(c)||document.getElementsByName(c)[0],f;if(d){e&&e.preventDefault();f=$(b.target||$.scrollTo.window());if(b.lock&&f.is(':animated')||b.onBefore&&b.onBefore.call(a,e,d,f)===!1)return;if(b.stop)f.queue('fx',[]).stop();f.scrollTo(d,b).trigger('notify.serialScroll',[d]);if(b.hash)f.queue(function(){location=a.hash;$(this).dequeue()})}}})(jQuery);
;(function($){var a='serialScroll',b='.'+a,c='bind',C=$[a]=function(b){$.scrollTo.window()[a](b)};C.defaults={duration:1e3,axis:'x',event:'click',start:0,step:1,lock:1,cycle:1,constant:1};$.fn[a]=function(y){y=$.extend({},C.defaults,y);var z=y.event,A=y.step,B=y.lazy;return this.each(function(){var j=y.target?this:document,k=$(y.target||this,j),l=k[0],m=y.items,o=y.start,p=y.interval,q=y.navigation,r;if(!B)m=w();if(y.force)t({},o);$(y.prev||[],j)[c](z,-A,s);$(y.next||[],j)[c](z,A,s);if(!l.ssbound)k[c]('prev'+b,-A,s)[c]('next'+b,A,s)[c]('goto'+b,t);if(p)k[c]('start'+b,function(e){if(!p){v();p=1;u()}})[c]('stop'+b,function(){v();p=0});k[c]('notify'+b,function(e,a){var i=x(a);if(i>-1)o=i});l.ssbound=1;if(y.jump)(B?k:w())[c](z,function(e){t(e,x(e.target))});if(q)q=$(q,j)[c](z,function(e){e.data=Math.round(w().length/q.length)*q.index(this);t(e,this)});function s(e){e.data+=o;t(e,this)};function t(e,a){if(!isNaN(a)){e.data=a;a=l}var c=e.data,n,d=e.type,f=y.exclude?w().slice(0,-y.exclude):w(),g=f.length,h=f[c],i=y.duration;if(d)e.preventDefault();if(p){v();r=setTimeout(u,y.interval)}if(!h){n=c<0?0:n=g-1;if(o!=n)c=n;else if(!y.cycle)return;else c=g-n-1;h=f[c]}if(!h||d&&o==c||y.lock&&k.is(':animated')||d&&y.onBefore&&y.onBefore.call(a,e,h,k,w(),c)===!1)return;if(y.stop)k.queue('fx',[]).stop();if(y.constant)i=Math.abs(i/A*(o-c));k.scrollTo(h,i,y).trigger('notify'+b,[c])};function u(){k.trigger('next'+b)};function v(){clearTimeout(r)};function w(){return $(m,l)};function x(a){if(!isNaN(a))return a;var b=w(),i;while((i=b.index(a))==-1&&a!=l)a=a.parentNode;return i}})}})(jQuery);
$(document).ready(function () {

var $panels = $('#slide_panel_wrapper .scrollContainer > div');
var $container = $('#slide_panel_wrapper .scrollContainer');

var horizontal = true;
if (horizontal) {
  $panels.css({
    'float' : 'left',
    'position' : 'relative' 
  });
  $container.css('width', $panels[0].offsetWidth * $panels.length);
}
var $scroll = $('#slide_panel_wrapper .scroll').css('overflow', 'hidden');
function selectNav() {
  $(this)
    .parents('ul:first')
      .find('a')
        .removeClass('selected')
      .end()
    .end()
    .addClass('selected');
}

$('#slide_panel_wrapper .navigation').find('a').click(selectNav);
function trigger(data) {
  var el = $('#slide_panel_wrapper .navigation').find('a[href$="' + data.id + '"]').get(0);
  selectNav.call(el);
}

if (window.location.hash) {
  trigger({ id : window.location.hash.substr(1) });
} else {
  $('ul.navigation a:first').click();
}
var offset = parseInt((horizontal ? 
  $container.css('paddingTop') : 
  $container.css('paddingLeft')) 
  || 0) * -1;


var scrollOptions = {
  target: $scroll, 
  items: $panels,
  navigation: '.navigation a',
  prev: 'img.left', 
  next: 'img.right',
  axis: 'xy', 
  onAfter: trigger,  
  offset: offset,
  duration: 500,
  easing: 'swing'
};
$('#slide_panel_wrapper').serialScroll(scrollOptions);
$.localScroll(scrollOptions);
scrollOptions.duration = 1;
$.localScroll.hash(scrollOptions);

});
