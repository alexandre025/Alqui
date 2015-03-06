'use.strict';

var _ = require('../tools.js');
var $ = require('jquery-browserify');

var offer = {
	init: function(){
		offer.pickdate();
		offer.sliderPhotos();
		offer.addMap();
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
				_.byId('slider-view-photo').querySelector('img').src = this.querySelector('img').src;
			},false);
		}
	},
	addMap: function(){
		var zip = _.byId('offer-location').getAttribute('data-location');
		var loc = {};
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'address': zip + ' France'},function(results,status){
			if(status == google.maps.GeocoderStatus.OK){
				loc.lat = results[0].geometry.location.lat();
				loc.lng = results[0].geometry.location.lng();
				loc.latLng = results[0].geometry.location;
			}
			else{
				offer.response({success:false});
			}

			var center =  new google.maps.LatLng(loc.lat,loc.lng);	
			var settings = {
				zoom : 		12,
				center : 	center,
				mapTypeId : google.maps.MapTypeId.ROADMAP,
				mapTypeCOntrol: false,
				streetViewControl: false,
				panControl: false
			};

			this.map = new google.maps.Map(_.byId('offer-location'),settings);
		
			new google.maps.Marker({position:loc.latLng,map:this.map});
			this.map.panTo(loc.latLng);
		});
	},
	response: function(obj){
		console.log(obj);
	}
}
module.exports = offer;