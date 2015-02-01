'use.strict'

var $ = require('../tools.js');
var countUp = require('../dependencies/countup.js');
// Home specific JS 
var home = {

	init : function(){
		home.number();
	},
	// Number animation
	number : function(){
		home.numbCount($.byClass('count-it'));				
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
	} 
};
module.exports = home;