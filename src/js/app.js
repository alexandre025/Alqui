'use.strict'

var $ = require ('./tools.js');
var displayer = require('./ui/displayer.js');
var home = require('./ui/home.js');

document.addEventListener('DOMContentLoaded',function(){




	displayer.init();
	home.init();

},false);