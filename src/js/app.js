'use.strict'

var _ = require ('./tools.js');

var displayer = require('./ui/displayer.js');
var home = require('./ui/home.js');
var register = require('./ui/register.js');
var userEdit = require('./ui/userEdit.js');
var search = require('./ui/search.js');
var offer = require('./ui/offer.js');
var dashboard = require('./ui/dashboard.js');

document.addEventListener('DOMContentLoaded',function(){

	displayer.init();
	home.init();
	if(_.byId('register-link')){
		register.init();
		console.log('register form init');
	}
	if(_.byId('password-form')){
		userEdit.init();
		console.log('edit form init');
	}
	if(_.byId('search-display-result')){
		search.init();
		console.log('edit search form');
	}
	if(_.byClass('pickadate').length != 0){
		offer.init();
		console.log('offer view');
	}
	if(_.byId('dashboard-notifs')){
		dashboard.init();
		console.log('dashboard view');
	}

},false);