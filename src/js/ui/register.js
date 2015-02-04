'use.strict'

var $ = require('../tools.js');

var register = {

	init : function(){
		register.checkEmail();
		register.checkConfirm();
		register.registerSubmit();
	},
	checkEmail : function(){
		$.byId('email-register').addEventListener('focusout',function(e){
			var email = this.value;
			if(email.length>1){
				var data=new FormData();
				data.append('email',email);
				$.async('POST','register/checkemail',data,function(xhr){
					$.byId('email-check').innerHTML=xhr.response;
				});
			}
		},false);
	},
	checkConfirm : function(){
		var input = $.selectorAll('input[type="password"]');
		for (var i = 0; i < input.length; i++) {
			input[i].addEventListener('focusout',function(e){			
				if(register.checkPwd()){
					$.byId('password-check').innerHTML='Le mot de passe ne correspond pas';
				}else{
					$.byId('password-check').innerHTML='Mot de passse valide';
				}
			},false);
		}
	},
	checkPwd : function(){
		var confirm = $.byId('confirm-register').value;
		var password = $.byId('password-register').value;
		if(confirm!=password || password.length<1){
			return true;
		}else{
			return false;
		}
	},
	registerSubmit : function(){
		$.byId('register-form').addEventListener('submit',function(e){
			if(register.checkPwd()){
				console.log('abort');
				return false;
			}else{
				// Go to app_controller.php
			}
		},false);
	}

};
module.exports = register;