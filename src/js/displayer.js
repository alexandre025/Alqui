'use.strict'

var $ = require('../tools.js')

var displayer = {

	init : function(){
		displayer.loginPopup();
	},

	loginPopup : function(){

		// Click on login link
		$.byId('login-link').addEventListener('click',function(e){
			e.preventDefault();
			$.byId('login-overlay').classList.add('active');
		},false);

		// Press escape 
		window.addEventListener('keyup', function(e) {
            if (e.keyCode === 38) {
            	displayer.closeLogin();
            }
        },false);

		// Click on overlay
        $.byId('login-overlay').addEventListener('click',function(){
        	displayer.closeLogin();
        },false);

	},
	closeLogin : function(){
		$.byId('login-overlay').classList.remove('active');
	}

}