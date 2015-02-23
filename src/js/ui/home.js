'use.strict';

var _ = require('../tools.js');
var countUp = require('../dependencies/countup.js');
// Home specific JS 
var home = {

	init : function(){
		home.onScroll();
		home.number();
	},
	// Number animation
	number : function(){
		home.numbCount(_.byClass('count-it'));				
	},
	numbCount : function(elem){
		var value, count;
		var options = {
		  useEasing : true, 
		  useGrouping : true, 
		  separator : ' ', 
		  decimal : '.', 
		  prefix : '', 
		  suffix : '' 
		}
		for (var i = 0; i < elem.length; i++) {
			value=parseInt(elem[i].getAttribute('data-count'));
			count = new countUp(elem[i],0,value,0,10,options);
			count.start();
		};
	},
	onScroll: function(){
		window.onscroll = function(){
			if(document.body.scrollTop >= window.innerHeight - 142){
				if(_.byId('headerLanding'))
					_.byId('headerLanding').setAttribute('id','headerLandingScroll');
			}
			else{
				if(_.byId('headerLandingScroll'))
				_.byId('headerLandingScroll').setAttribute('id','headerLanding');
			}
		}
	}
};
module.exports = home;