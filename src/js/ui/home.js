'use.strict'

var $ = require('../tools.js');

// Home specific JS 
var home = {

	init : function(){
		home.number();
	},
	// Number animation
	number : function(){
		var once = false;
		window.addEventListener('scroll',function(){
			if(document.body.scrollTop > 150 && once == false){
				once = true;
				var $number = $.byClass('number');
				for (var i = 0; i < $number.length; i++) {
					home.numbCount($number[i]);
				};
			}
		},false);
	},
	numbCount : function(elem){
		var value = parseInt(elem.innerHTML);
		for (var i = 0; i < value; i++) {
			setTimeout(function(){
				elem.innerHTML=value;
			},10);
		};
	} 
}