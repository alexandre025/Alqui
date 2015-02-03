'use.strict'

var $ = require('../tools.js');

var register = {

	init : function(){
		register.checkEmail();
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
	}

};
module.exports = register;