'use.strict'

var _ = require('../tools.js');
var displayer = require('../ui/displayer.js');

var register = {

	init : function(){
		register.checkEmail();
		register.checkConfirm();
		register.registerSubmit();
		register.registerPopup();
	},
	registerPopup : function(){
	    _.byId('register-link').addEventListener('click',function(e){
            e.preventDefault();
            _.byId('register-overlay').classList.add('active');
        },false);

		// Press escape 
		window.addEventListener('keyup', function(e) {
            if (e.keyCode === 27) {
            	register.closeRegister();
            }
        },false);

		// Click on overlay
        _.byId('register-overlay').addEventListener('click',function(e){
        	register.closeRegister();
        },false);
        // Stop bubbling when clicking on the form
        _.byId('register-form').addEventListener('click',function(e){
          e.stopPropagation();
        },false);
    },
    closeRegister : function(){
		_.byId('register-overlay').classList.remove('active');
	},
	checkEmail : function(){
		_.byId('email-register').addEventListener('focusout',function(e){
			var email = this.value;
			if(email.length>1){
				var data=new FormData();
				data.append('email',email);
				_.async('POST','register/checkemail',data,function(xhr){
					_.byId('email-check').innerHTML=xhr.response;
				});
			}
		},false);
	},
	checkConfirm : function(){
		var input = _.selectorAll('input[type="password"]');
		for (var i = 0; i < input.length; i++) {
			input[i].addEventListener('keyup',function(e){			
				if(register.checkPwd()){
					_.byId('password-check').innerHTML='Le mot de passe ne correspond pas';
				}else{
					_.byId('password-check').innerHTML='Mot de passse valide';
				}
			},false);
		}
	},
	checkPwd : function(){
		var confirm = _.byId('confirm-register').value;
		var password = _.byId('password-register').value;
		if(confirm!=password || password.length<1){
			return true;
		}else{
			return false;
		}
	},
	registerSubmit : function(){
		_.byId('register-form').addEventListener('submit',function(e){
			if(register.checkPwd()){
				console.log('abort');
				e.preventDefault();
				return false;
			}else{
				// Go to app_controller.php
			}
		},false);
	}

};
module.exports = register;