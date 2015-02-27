'use.strict';

var _ = require('../tools.js');
var countUp = require('../dependencies/countup.js');
// Home specific JS 
var home = {

	init : function(){
		home.onScroll();
		home.number();
		home.menu();
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
	},
	menu: function(){
		var menu = _.byId('action');
		var sub_menu = _.byId('sub-menu');
		menu.addEventListener('click',function(e){
			e.preventDefault();
			if(sub_menu.getAttribute('class') == 'active'){
				sub_menu.removeAttribute('class');
			}
			else{
				sub_menu.setAttribute('class','active');
			}
		},false);
	}
};
module.exports = home;