'use.strict'

var $ = require ('./tools.js');

var displayer = require('./ui/displayer.js');
var home = require('./ui/home.js');
var register = require('./ui/register.js');
var userEdit = require('./ui/userEdit.js');

document.addEventListener('DOMContentLoaded',function(){




	displayer.init();
	home.init();
	if($.byId('register-form')){
		register.init();
		console.log('register form init');
	}
	if($.byId('password-form')){
		userEdit.init();
		console.log('edit form init');
	}

},false);