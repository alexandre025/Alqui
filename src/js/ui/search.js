'use.strict'

var $ = require('../tools.js');
var Masonry = require('masonry-layout');

var search = {
	init : function(){
		search.selectOrder();
		search.initMasonry();
		search.rangePrice();
	},
	initMasonry: function(){
		var container = $.selector('#search-display-result-all');
		if(container){
			var msnry = new Masonry(container,{
				columnWidth: 10,
				itemSelector: '.search-display-result-single'
			});
		}
	},
	rangePrice : function(){
		$.byId('input-price').addEventListener('change',function(e){
			$.byId('value-price').innerHTML=this.value + '€';
		},false);
	},
	selectOrder : function(){
		$.byId('select-order').addEventListener('change',function(e){
			$.byId('search-form').submit();
		},false);
	}
};
module.exports = search;