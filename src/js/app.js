'use.strict'

var $ = require ('./tools.js');

var displayer = require('./ui/displayer.js');
var home = require('./ui/home.js');
var register = require('./ui/register.js');
var userEdit = require('./ui/userEdit.js');
var search = require('./ui/search.js');

document.addEventListener('DOMContentLoaded',function(){

	displayer.init();
	home.init();
	if($.byId('register-link')){
		register.init();
		console.log('register form init');
	}
	if($.byId('password-form')){
		userEdit.init();
		console.log('edit form init');
	}
	if($.byId('search-display-result')){
		search.init();
		console.log('edit search form');
	}

},false);