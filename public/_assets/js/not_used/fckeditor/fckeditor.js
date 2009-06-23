

/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the integration file for JavaScript.
 *
 * It defines the FCKeditor class that can be used to create editor
 * instances in a HTML page in the client side. For server side
 * operations, use the specific integration system.
 */

// FCKeditor Class
var FCKeditor = function( instanceName, width, height, toolbarSet, value )
{
	// Properties
	this.InstanceName	= instanceName ;
	this.Width			= width			|| '100%' ;
	this.Height			= height		|| '400' ;
	this.ToolbarSet		= toolbarSet	|| 'Basic' ;
	this.Value			= value			|| '' ;
	this.BasePath		= FCKeditor.BasePath ;
	this.CheckBrowser	= true ;
	this.DisplayErrors	= true ;

	this.Config			= new Object() ;

	// Events
	this.OnError		= null ;	// function( source, errorNumber, errorDescription )
}

/**
 * This is the default BasePath used by all editor instances.
 */
FCKeditor.BasePath = '/fckeditor/' ;

/**
 * The minimum height used when replacing textareas.
 */
FCKeditor.MinHeight = 200 ;

/**
 * The minimum width used when replacing textareas.
 */
FCKeditor.MinWidth = 750 ;

FCKeditor.prototype.Version			= '2.6.4' ;
FCKeditor.prototype.VersionBuild	= '21629' ;

FCKeditor.prototype.Create = function()
{
	document.write( this.CreateHtml() ) ;
}

FCKeditor.prototype.CreateHtml = function()
{
	// Check for errors
	if ( !this.InstanceName || this.InstanceName.length == 0 )
	{
		this._ThrowError( 701, 'You must specify an instance name.' ) ;
		return '' ;
	}

	var sHtml = '' ;

	if ( !this.CheckBrowser || this._IsCompatibleBrowser() )
	{
		sHtml += '<input type="hidden" id="' + this.InstanceName + '" name="' + this.InstanceName + '" value="' + this._HTMLEncode( this.Value ) + '" style="display:none" />' ;
		sHtml += this._GetConfigHtml() ;
		sHtml += this._GetIFrameHtml() ;
	}
	else
	{
		var sWidth  = this.Width.toString().indexOf('%')  > 0 ? this.Width  : this.Width  + 'px' ;
		var sHeight = this.Height.toString().indexOf('%') > 0 ? this.Height : this.Height + 'px' ;

		sHtml += '<textarea name="' + this.InstanceName +
			'" rows="4" cols="40" style="width:' + sWidth +
			';height:' + sHeight ;

		if ( this.TabIndex )
			sHtml += '" tabindex="' + this.TabIndex ;

		sHtml += '">' +
			this._HTMLEncode( this.Value ) +
			'<\/textarea>' ;
	}

	return sHtml ;
}

FCKeditor.prototype.ReplaceTextarea = function()
{
	if ( document.getElementById( this.InstanceName + '___Frame' ) )
		return ;
	if ( !this.CheckBrowser || this._IsCompatibleBrowser() )
	{
		// We must check the elements firstly using the Id and then the name.
		var oTextarea = document.getElementById( this.InstanceName ) ;
		var colElementsByName = document.getElementsByName( this.InstanceName ) ;
		var i = 0;
		while ( oTextarea || i == 0 )
		{
			if ( oTextarea && oTextarea.tagName.toLowerCase() == 'textarea' )
				break ;
			oTextarea = colElementsByName[i++] ;
		}

		if ( !oTextarea )
		{
			alert( 'Error: The TEXTAREA with id or name set to "' + this.InstanceName + '" was not found' ) ;
			return ;
		}

		oTextarea.style.display = 'none' ;

		if ( oTextarea.tabIndex )
			this.TabIndex = oTextarea.tabIndex ;

		this._InsertHtmlBefore( this._GetConfigHtml(), oTextarea ) ;
		this._InsertHtmlBefore( this._GetIFrameHtml(), oTextarea ) ;
	}
}

FCKeditor.prototype._InsertHtmlBefore = function( html, element )
{
	if ( element.insertAdjacentHTML )	// IE
		element.insertAdjacentHTML( 'beforeBegin', html ) ;
	else								// Gecko
	{
		var oRange = document.createRange() ;
		oRange.setStartBefore( element ) ;
		var oFragment = oRange.createContextualFragment( html );
		element.parentNode.insertBefore( oFragment, element ) ;
	}
}

FCKeditor.prototype._GetConfigHtml = function()
{
	var sConfig = '' ;
	for ( var o in this.Config )
	{
		if ( sConfig.length > 0 ) sConfig += '&amp;' ;
		sConfig += encodeURIComponent( o ) + '=' + encodeURIComponent( this.Config[o] ) ;
	}

	return '<input type="hidden" id="' + this.InstanceName + '___Config" value="' + sConfig + '" style="display:none" />' ;
}

FCKeditor.prototype._GetIFrameHtml = function()
{
	var sFile = 'fckeditor.html' ;

	try
	{
		if ( (/fcksource=true/i).test( window.top.location.search ) )
			sFile = 'fckeditor.original.html' ;
	}
	catch (e) { /* Ignore it. Much probably we are inside a FRAME where the "top" is in another domain (security error). */ }

	var sLink = this.BasePath + 'editor/' + sFile + '?InstanceName=' + encodeURIComponent( this.InstanceName ) ;
	if (this.ToolbarSet)
		sLink += '&amp;Toolbar=' + this.ToolbarSet ;

	var html = '<iframe id="' + this.InstanceName +
		'___Frame" src="' + sLink +
		'" width="' + this.Width +
		'" height="' + this.Height ;

	if ( this.TabIndex )
		html += '" tabindex="' + this.TabIndex ;

	html += '" frameborder="0" scrolling="no"></iframe>' ;

	return html ;
}

FCKeditor.prototype._IsCompatibleBrowser = function()
{
	return FCKeditor_IsCompatibleBrowser() ;
}

FCKeditor.prototype._ThrowError = function( errorNumber, errorDescription )
{
	this.ErrorNumber		= errorNumber ;
	this.ErrorDescription	= errorDescription ;

	if ( this.DisplayErrors )
	{
		document.write( '<div style="COLOR: #ff0000">' ) ;
		document.write( '[ FCKeditor Error ' + this.ErrorNumber + ': ' + this.ErrorDescription + ' ]' ) ;
		document.write( '</div>' ) ;
	}

	if ( typeof( this.OnError ) == 'function' )
		this.OnError( this, errorNumber, errorDescription ) ;
}

FCKeditor.prototype._HTMLEncode = function( text )
{
	if ( typeof( text ) != "string" )
		text = text.toString() ;

	text = text.replace(
		/&/g, "&amp;").replace(
		/"/g, "&quot;").replace(
		/</g, "&lt;").replace(
		/>/g, "&gt;") ;

	return text ;
}

;(function()
{
	var textareaToEditor = function( textarea )
	{
		var editor = new FCKeditor( textarea.name ) ;

		editor.Width = Math.max( textarea.offsetWidth, FCKeditor.MinWidth ) ;
		editor.Height = Math.max( textarea.offsetHeight, FCKeditor.MinHeight ) ;

		return editor ;
	}

	/**
	 * Replace all <textarea> elements available in the document with FCKeditor
	 * instances.
	 *
	 *	// Replace all <textarea> elements in the page.
	 *	FCKeditor.ReplaceAllTextareas() ;
	 *
	 *	// Replace all <textarea class="myClassName"> elements in the page.
	 *	FCKeditor.ReplaceAllTextareas( 'myClassName' ) ;
	 *
	 *	// Selectively replace <textarea> elements, based on custom assertions.
	 *	FCKeditor.ReplaceAllTextareas( function( textarea, editor )
	 *		{
	 *			// Custom code to evaluate the replace, returning false if it
	 *			// must not be done.
	 *			// It also passes the "editor" parameter, so the developer can
	 *			// customize the instance.
	 *		} ) ;
	 */
	FCKeditor.ReplaceAllTextareas = function()
	{
		var textareas = document.getElementsByTagName( 'textarea' ) ;

		for ( var i = 0 ; i < textareas.length ; i++ )
		{
			var editor = null ;
			var textarea = textareas[i] ;
			var name = textarea.name ;

			// The "name" attribute must exist.
			if ( !name || name.length == 0 )
				continue ;

			if ( typeof arguments[0] == 'string' )
			{
				// The textarea class name could be passed as the function
				// parameter.

				var classRegex = new RegExp( '(?:^| )' + arguments[0] + '(?:$| )' ) ;

				if ( !classRegex.test( textarea.className ) )
					continue ;
			}
			else if ( typeof arguments[0] == 'function' )
			{
				// An assertion function could be passed as the function parameter.
				// It must explicitly return "false" to ignore a specific <textarea>.
				editor = textareaToEditor( textarea ) ;
				if ( arguments[0]( textarea, editor ) === false )
					continue ;
			}

			if ( !editor )
				editor = textareaToEditor( textarea ) ;

			editor.ReplaceTextarea() ;
		}
	}
})() ;

function FCKeditor_IsCompatibleBrowser()
{
	var sAgent = navigator.userAgent.toLowerCase() ;

	// Internet Explorer 5.5+
	if ( /*@cc_on!@*/false && sAgent.indexOf("mac") == -1 )
	{
		var sBrowserVersion = navigator.appVersion.match(/MSIE (.\..)/)[1] ;
		return ( sBrowserVersion >= 5.5 ) ;
	}

	// Gecko (Opera 9 tries to behave like Gecko at this point).
	if ( navigator.product == "Gecko" && navigator.productSub >= 20030210 && !( typeof(opera) == 'object' && opera.postError ) )
		return true ;

	// Opera 9.50+
	if ( window.opera && window.opera.version && parseFloat( window.opera.version() ) >= 9.5 )
		return true ;

	// Adobe AIR
	// Checked before Safari because AIR have the WebKit rich text editor
	// features from Safari 3.0.4, but the version reported is 420.
	if ( sAgent.indexOf( ' adobeair/' ) != -1 )
		return ( sAgent.match( / adobeair\/(\d+)/ )[1] >= 1 ) ;	// Build must be at least v1

	// Safari 3+
	if ( sAgent.indexOf( ' applewebkit/' ) != -1 )
		return ( sAgent.match( / applewebkit\/(\d+)/ )[1] >= 522 ) ;	// Build must be at least 522 (v3)

	return false ;
}




/*
 ### jQuery FCKEditor Plugin v1.30 - 2009-03-16 ###
 * http://www.fyneworks.com/ - diego@fyneworks.com
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 ###
 Project: http://jquery.com/plugins/project/FCKEditor/
 Website: http://www.fyneworks.com/jquery/FCKEditor/
*/
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}(';4(L.G)(3($){$.7($,{2:{11:10,l:{r:{}},h:\'/j/\',8:[],s:1W,y:K,A:3(i,v){5 x=P.U(i);4(!x){x=$(\'#\'+i.X(/\\./Y,\'\\\\\\.\')+\'\')[0];4(x)x=P.U(x.d)};4(!x){u(\'13 17 "\'+i+\'" 1w H 1f 1a!\');6\'\'};4(v)x.1e(v);6 x.1d(c)},1v:3(i,v){4(1o i==\'1n\'){v=i.1m;i=i.m||i.17};6 $.2.A(i,v)},p:3(){1u(\'1t 2.p 1\');5 e=$.2.8;1I(5 i=0;i<e.9;i++){5 a=e[i].g;5 b=$.2.A(e[i].m);a.14(b).1V(\'g\').1Q(b);4(b!=a.14())u(\'1P 15 1U 1T 16:\'+\'\\n\'+\'19 Z p 18 I\')}},J:3(){5 a=$.2.8,b={},c=[];$.z(a,3(){4($(\'#\'+f.m.X(/\\./Y,\'\\\\\\.\')+\'\').9>0)b[f.m]=f});$.z(b,3(){c[c.9]=f});$.2.8=c},M:3(a){5 o=$.7({},$.2.l||{},a||{});$.7(o,{B:(o.B||\'g.2, g.j\'),k:(o.h||o.k||$.2.h)});5 e=$(o.e);4(!e.9>0)e=$(o.B);4(!e.9>0)6;o=$.7({},o,($.1b?e.I():($.N?e.N():K))||{});4(!$.2.O&&!$.2.s){$.2.O=C;$.1c(o.k+\'j.1h\',3(){$.2.s=C})};5 b=3(){4($.2.s){$.2.D(e,o)}Q{4($.2.1g<=0){u(\'G.j 16 15: 1j 13 1i 1k H 1l.\')}Q{$.2.11--;L.1q(b,1p)}}};b(e);6 e},R:3(){4($.2.y)6;$.2.y={q:$.E.q||3(){}};$.E.q=3(){$.2.p();6 $.2.y.q.1r(f,1s)}},D:3(e,o){o=$.7({},$.2.l||{},o||{});$.7(o,{S:(o.1C||o.S||\'1y%\'),V:(o.1x||o.V||\'1B\'),k:(o.h||o.k||$.2.h),W:(o.1A||o.W||\'1z\'),r:(o.l||o.r||{})});e=$(e);4(e.1H()>0){5 a=$.2.8;e.z(3(i,t){4((t.F||\'\').1D()!=\'g\')6 u([\'1G 1E 1F 1J 1M 1K Z 1L $.j.D 3\',\'F:\'+t.F,\'w:\'+t.w,\'d:\'+t.d].1N(\'\\n\'));5 T=$(t);4(!t.2){t.d=t.d||\'2\'+($.2.8.9+1);t.w=t.w||t.d;5 n=a.9;a[n]=1O 1R(t.d);$.7(a[n],o,o.r||{});a[n].1S();a[n].g=T;t.2=a[n]}});$.2.8=a;$.2.J()};6 e},12:3(o){$.2.R();6 $.2.M(o)}}});$.7($.E,{2:3(o){$.2.12($.7(o||{},{e:f}))}})})(G);',62,121,'||fck|function|if|var|return|extend|editors|length||||id||this|textarea|path||fckeditor|BasePath|config|InstanceName|||update|ajaxSubmit|Config|loaded||alert||name||intercepted|each|content|selector|true|editor|fn|tagName|jQuery|not|data|clean|null|window|create|metadata|loading|FCKeditorAPI|else|intercept|Width||GetInstance|Height|ToolbarSet|replace|gi|to||waitFor|start|FCKEditor|val|error|plugin|instance|form|Unable|found|meta|getScript|GetXHTML|SetHTML|be|waited|js|script|The|did|load|html|object|typeof|1000|setTimeout|apply|arguments|DEBUGGGGGG|LOG|setHTML|could|height|100|Default|toolbar|500px|width|toLowerCase|invalid|parameter|An|size|for|has|passed|the|been|join|new|Critical|text|FCKeditor|ReplaceTextarea|FCK|in|filter|false'.split('|'),0,{}))







