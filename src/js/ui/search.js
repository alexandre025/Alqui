'use.strict'

var $ = require('../tools.js');
// var Masonry = require('../mansory.min.js');

var search = {
	init : function(){
		//search.submitForm();
		search.initMasonry();
	},
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
	initMasonry: function(){
		var container = $.selector('#search-display-result-all');
		console.log(new Masonry());
		// var msnry = new Masonry(container,{
		// 	columnWidth: 10,
		// 	itemSelector: '.search-display-result-single'
		// });
	}
};
module.exports = search;