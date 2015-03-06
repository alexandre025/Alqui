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

			var center =	new google.maps.LatLng(loc.lat,loc.lng);

			var styles = [
				{
					"featureType": "water",
					"stylers": [
						{ "color": "#cccccc" },
						{ "lightness": 3 }
					]
				},{
					"featureType": "poi",
					"stylers": [
						{ "visibility": "off" }
					]
				},{
					"featureType": "road.highway",
					"elementType": "labels",
					"stylers": [
						{ "visibility": "off" }
					]
				},{
					"featureType": "road.highway",
					"stylers": [
						{ "color": "#0d9c51" }
					]
				},{
					"featureType": "landscape.natural",
					"stylers": [
						{ "visibility": "off" },
						{ "color": "#ffffff" }
					]
				},{
					"featureType": "landscape.natural",
					"stylers": [
						{ "visibility": "on" },
						{ "color": "#cccccc" },
						{ "lightness": 100 }
					]
				},{
					"featureType": "administrative.neighborhood",
					"elementType": "labels.text",
					"stylers": [
						{ "visibility": "off" }
					]
				},{
					"featureType": "road.arterial",
					"elementType": "labels",
					"stylers": [
						{ "visibility": "off" }
					]
				},{
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [
						{ "visibility": "on" }
					]
				},{
					"featureType": "road.highway.controlled_access",
					"elementType": "geometry",
					"stylers": [
						{ "visibility": "off" }
					]
				},{
					"featureType": "administrative.country",
					"stylers": [
						{ "weight": 0.1 },
						{ "visibility": "simplified" }
					]
				},{
					"featureType": "administrative.locality",
					"stylers": [
					{ "visibility": "simplified" }
					]
				}
			];

			var settings = {
				zoom : 		12,
				center : 	center,
				mapTypeId : google.maps.MapTypeId.ROADMAP,
				mapTypeCOntrol: false,
				streetViewControl: false,
				panControl: false,
				scrollwheel: false,
				// disableDefaultUI: true,
				// mapTypeId: 'Styled' 
			};

			var map = new google.maps.Map(_.byId('offer-location'),settings); 
			// var styledMapType = new google.maps.StyledMapType(styles, { name: 'Styled' });
			// map.mapTypes.set('Styled', styledMapType);
		
			var circleOptions = {
				strokeColor: 'rgba(24,168,92,1)',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: 'rgba(24,168,92,1)',
				fillOpacity: 0.35,
				map: map,
				center: loc.latLng,
				radius: 1500
			};
			cityCircle = new google.maps.Circle(circleOptions);
			// new google.maps.Marker({position:loc.latLng,map:this.map});
			// this.map.panTo(loc.latLng);
		});
	},
	response: function(obj){
		console.log(obj);
	}
}
module.exports = offer;