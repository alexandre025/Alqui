'use.strict'

var $ = require ('./tools.js');

document.addEventListener('DOMContentLoaded',function(){

	navigationAsync();
	function navigationAsync(){
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
		$.byId('display').innerHTML=xhr.response;
		navigationAsync();
	}
},false);