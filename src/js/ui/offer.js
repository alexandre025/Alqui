'use.strict';

var _ = require('../tools.js');
var $ = require('jquery-browserify');

var offer = {
	init: function(){
		offer.pickdate();
		offer.sliderPhotos();
	},
	pickdate: function(){
		$('.pickadate').pickadate({
			monthsFull: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
			wekkdaysFull: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
			wekkdaysShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
			today: 'Aujourd\'hui',
			clear: 'Effacer',
			close: 'Fermer',
			labelMonthNext: 'Mois prochain',
			labelMonthPrev: 'Mois précédent',
			labelMonthSelect: 'Choisir un mois',
			labelYearSelect: 'Choisir une année',
			format: 'dd/mm/yyyy',
			selectYears: true,
			selectMonths: true,
			min: new Date()
		});
	},
	sliderPhotos: function(){
		var slider_photos = _.selectorAll('#slider > ul > li');
		for(var i = 0, l = slider_photos.length; i < l; i++){
			slider_photos[i].addEventListener('click',function(){
				offer.clickPhotos();
			},false);
		}
	},
	clickPhotos: function(){
		_.byId('slider-view-photo').querySelector('img').src = this.querySelector('img').src;
	}
}
module.exports = offer;