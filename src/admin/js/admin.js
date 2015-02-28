'use.strict'

var $ = require ('./tools.js');

document.addEventListener('DOMContentLoaded',function(){

	var init = null;
	navigationAsync(init);
	function navigationAsync(display){
		if(display)
			var $nav = display.querySelectorAll('a.nav-async');
		else
			var $nav = $.selectorAll('a.nav-async');

		for (var i = 0; i < $nav.length; i++) {
			$nav[i].addEventListener('click',function(e){
				e.preventDefault();
				var url = this.href;
				$.async('GET',url,'',inDisplay);
			},false);
		};
	}

	function inDisplay(xhr){
		var display=$.byId('display');
		display.innerHTML=xhr.response;
		navigationAsync(display);
	}
},false);