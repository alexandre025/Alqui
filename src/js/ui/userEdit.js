'use.strict';

var _ = require('../tools.js');

var userEdit = {

	init : function(){
		userEdit.checkConfirm();
		userEdit.passwordSubmit();

		_.byId('editphoto').addEventListener('mouseover',function(){
			_.byId('file-fake').style.visibility = "visible";
		},false);
		_.byId('editphoto').addEventListener('mouseout',function(){
			_.byId('file-fake').style.visibility = "hidden";
		},false);
		_.byId('file-fake').addEventListener('mouseover',function(){
			_.byId('file-fake').style.visibility = "visible";
		},false);
		_.byId('file-fake').addEventListener('click',function(e){
			e.preventDefault();
			_.byId('input-file').click();
		},false);
		_.byId('input-file').addEventListener('change',function(e){
			e.preventDefault();
			_.byId('file-name').innerHTML = this.value.substr(12,this.value.length);
		},false);
	},
	checkConfirm : function(){
		var input = _.selectorAll('input[type="password"]');
		for (var i = 0; i < input.length; i++) {
			input[i].addEventListener('keyup',function(e){			
				if(userEdit.checkPwd()){
					_.byId('password-check').innerHTML='Le mot de passe ne correspond pas';
				}else{
					_.byId('password-check').innerHTML='Mot de passse valide';
				}
			},false);
		}
	},
	checkPwd : function(){
		var confirm = _.byId('password-confirm').value;
		var password = _.byId('password').value;
		if(confirm!=password || password.length<1){
			return true;
		}else{
			return false;
		}
	},
	passwordSubmit : function(){
		_.byId('password-form').addEventListener('submit',function(e){
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