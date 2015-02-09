'use.strict'

var $ = require('../tools.js');

var search = {
	init : function(){
		//search.submitForm();
	}
	// submitForm : function(){
	// 	var form = $.byId('select-order');
	// 	form.addEventListener('submit',function(e){
	// 		e.preventDefault();
	// 		var order = this.value;
	// 		var data=new FormData();
	// 		data.append('order',order);
	// 		console.log(data);
	// 		$.async('POST','search/'+cat,data,function(xhr){
	// 			$.byId('display-result').innerHTML=xhr.response;
	// 		});
	// 		return false;
	// 	},false);
	// }
};
module.exports = search;