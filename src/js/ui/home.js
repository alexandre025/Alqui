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
				home.numbCount($.byClass('count-it'));				
			}
		},false);
	},
	numbCount : function(elem){
		var value0 = parseInt(elem[0].getAttribute('data-count'));
		var value1 = parseInt(elem[1].getAttribute('data-count'));
		var value2 = parseInt(elem[2].getAttribute('data-count'));
		var max;
		if(value0 >= value1 && value0 >= value2 ){ max = value0; }
		else if(value1 >= value0 && value1 >= value2 ){ max = value1; }
		else { max = value2; }
		console.log(max);
		for (var i = 0; i <= max; i++) {
			if(value0>=i){set(elem[0],i,10000/value0);}
			if(value1>=i){set(elem[1],i,10000/value1);}
			if(value2>=i){set(elem[2],i,10000/value2);}
		}
		function set(elem,i,timer){
			setTimeout(function(){
				console.log(timer);
				elem.innerHTML=i;
			},timer);
		}
	} 
};
module.exports = home;