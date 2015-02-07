'use.strict'

var $ = require('../tools.js');

var userEdit = {

	init : function(){
		userEdit.checkConfirm();
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
		var password = $.byId('password-edit').value;
		if(confirm!=password || password.length<1){
			return true;
		}else{
			return false;
		}
	}
};
module.exports = userEdit;