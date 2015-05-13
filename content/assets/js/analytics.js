var code=
{
	ga:			'UA-43175214-1',
	gtm:		'GTM-PW2CX8',
	//facebook:	NOT SET,
	vwo:		91146,
	clicky:		100643662
};
			//_vis_opt_bottom_initialize();
			ga('create', code.ga, {'allowAnchor': true});
			ga('send', 'pageview', { 'page': location.pathname + location.search + location.hash});
			ga('require', 'displayfeatures');
			_fbq.loaded=true;
			_fbq.push(['addPixelId', code.facebook]);
			$('body').append('<noscript><img height="1" width="1" alt="" style="display:none" src="//www.facebook.com/tr?id='+code.facebook+'&amp;ev=PixelInitialized"></noscript>');
			$('body').append('<noscript><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100643662ns.gif" /></noscript>');
			
			
			function clicky_gc(name)
			{
				var ca = document.cookie.split(';');
				for (var i in ca) {
					if (typeof ca[i].indexOf!='undefined' && ca[i].indexOf(name + '=') != -1) {
						return decodeURIComponent(ca[i].split('=')[1]);
					}
				}
				return '';
			}
			
			window.username_check = clicky_gc('comment_author_dce693ad5a243c8ee4cda0ca23145f90');
			if (username_check) var clicky_custom_session = {username: username_check};
			window.clicky_custom = clicky_custom || {};
			clicky_custom.cookies_disable = 1;
			window.clicky = { log : function () { return true;	}, goal: function () { return true;	} };
			window.clicky_site_id = code.clicky;

			
			
	

//var _vwo_code=(function(){
//var account_id=code.vwo,
//settings_tolerance=2000,
//library_tolerance=2500,
//use_existing_jquery=false,
//f=false,d=document;
//	return {use_existing_jquery:function(){return use_existing_jquery;},
//		library_tolerance:function(){return library_tolerance;},
//		finish:function(){if(!f){f=true;var a=d.getElementById('_vis_opt_path_hides');
//			if(a)a.parentNode.removeChild(a);}},
//		finished:function(){return f;},
//		load:function(a){var b=d.createElement('script');
//			b.src=a;b.type='text/javascript';b.innerText;
//			b.onerror=function(){_vwo_code.finish();};
//			d.getElementsByTagName('head')[0].appendChild(b);},init:
//			function(){settings_timer=setTimeout('_vwo_code.finish()',settings_tolerance);
//				this.load('//dev.visualwebsiteoptimizer.com/j.php?a='+account_id+'&u='+encodeURIComponent(d.URL)+'&r='+Math.random());
//				var a=d.createElement('style'),b='',h=d.getElementsByTagName('head')[0];
//				a.setAttribute('id','_vis_opt_path_hides');a.setAttribute('type','text/css');
//				if(a.styleSheet)a.styleSheet.cssText=b;else a.appendChild(d.createTextNode(b));h.appendChild(a);return settings_timer;}};}());
//				_vwo_settings_timer=_vwo_code.init();

(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',code.gtm);