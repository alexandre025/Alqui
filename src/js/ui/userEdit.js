'use.strict'

var $ = require('../tools.js');

var userEdit = {

	init : function(){
		userEdit.checkConfirm();
		userEdit.passwordSubmit()
	},
	checkConfirm : function(){
		var input = $.selectorAll('input[type="password"]');
		for (var i = 0; i < input.length; i++) {
			input[i].addEventListener('keyup',function(e){			
				if(userEdit.checkPwd()){
					$.byId('password-check').innerHTML='Le mot de passe ne correspond pas';
				}else{
					$.byId('password-check').innerHTML='Mot de passse valide';
				}
			},false);
		}
	},
	checkPwd : function(){
		var confirm = $.byId('password-confirm').value;
		var password = $.byId('password').value;
		if(confirm!=password || password.length<1){
			return true;
		}else{
			return false;
		}
	},
	passwordSubmit : function(){
		$.byId('password-form').addEventListener('submit',function(e){
			if(userEdit.checkPwd()){
				e.preventDefault();
				return false;
			}else{
				// Go to app_controller.php
			}
		},false);
	}	
};
module.exports = userEdit;