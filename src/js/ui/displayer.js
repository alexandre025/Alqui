'use.strict'

var $ = require('../tools.js');

var displayer = {

	init : function(){
		displayer.loginPopup();
	},
	// Login popup toggle
	loginPopup : function(){

		// Click on login link
		$.byId('login-link').addEventListener('click',function(e){
			e.preventDefault();
			$.byId('login-overlay').classList.add('active');
		},false);

		// Press escape 
		window.addEventListener('keyup', function(e) {
            if (e.keyCode === 27) {
            	displayer.closeLogin();
            }
        },false);

		// Click on overlay
        $.byId('login-overlay').addEventListener('click',function(e){
        	displayer.closeLogin();
        },false);
        // Stop bubbling when clicking on the form
        $.byClass('login-form')[0].addEventListener('click',function(e){
          e.stopPropagation();
        },false);

	},
	closeLogin : function(){
		$.byId('login-overlay').classList.remove('active');
	}
};
module.exports = displayer;