'use.strict'

var $ = require ('./tools.js');

var displayer = require('./ui/displayer.js');
var home = require('./ui/home.js');
var register = require('./ui/register.js');

document.addEventListener('DOMContentLoaded',function(){




	displayer.init();
	home.init();
	if($.byId('email-register')){
		register.init();
	}

},false);