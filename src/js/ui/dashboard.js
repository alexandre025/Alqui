'use strict';

var _ = require('../tools.js');

var dashboard = {
	init: function(){
		dashboard.displayCat();
	},
	displayCat: function(){
		var notifs = _.byId('notifications');
		var locations = _.byId('locations');
		var reservations = _.byId('reservations');
		var wishlists = _.byId('wishlists');

		notifs.addEventListener('click',function(){
			_.byId('dashboard-notifs').style.display = 'block';
			_.byId('dashboard-locations').style.display = 'none';
			_.byId('dashboard-reservations').style.display = 'none';
			_.byId('dashboard-wishlists').style.display = 'none';
		},false);
		locations.addEventListener('click',function(){
			_.byId('dashboard-locations').style.display = 'block';
			_.byId('dashboard-notifs').style.display = 'none';
			_.byId('dashboard-reservations').style.display = 'none';
			_.byId('dashboard-wishlists').style.display = 'none';
		},false);
		reservations.addEventListener('click',function(){
			_.byId('dashboard-reservations').style.display = 'block';
			_.byId('dashboard-locations').style.display = 'none';
			_.byId('dashboard-notifs').style.display = 'none';
			_.byId('dashboard-wishlists').style.display = 'none';
		},false);
		wishlists.addEventListener('click',function(){
			_.byId('dashboard-wishlists').style.display = 'block';
			_.byId('dashboard-locations').style.display = 'none';
			_.byId('dashboard-reservations').style.display = 'none';
			_.byId('dashboard-notifs').style.display = 'none';
		},false);

		var add_offer_button = _.byClass('add-offer-button');
		for(var i = 0; i < add_offer_button.length; i++){
			add_offer_button[i].addEventListener('click',function(){
				_.byId('own-offer').style.display = 'none';
				_.byId('add-offer').style.display = 'block';
			},false);
		}
		_.byId('return-offer-location').addEventListener('click',function(){
			_.byId('own-offer').style.display = 'block';
			_.byId('add-offer').style.display = 'none';
		},false);


		_.byId('add-picture-1-fake').addEventListener('click',function(e){
			e.preventDefault();
			_.byId('add-picture-1').click();
		},false);
		_.byId('add-picture-2-fake').addEventListener('click',function(e){
			e.preventDefault();
			_.byId('add-picture-2').click();
		},false);
		_.byId('add-picture-3-fake').addEventListener('click',function(e){
			e.preventDefault();
			_.byId('add-picture-3').click();
		},false);

		_.byId('add-picture-1').addEventListener('change',function(){
			_.byId('add-picture-1-fake').innerHTML = this.value.substr(12,this.value.length - 12);
		},false);
		_.byId('add-picture-2').addEventListener('change',function(){
			_.byId('add-picture-2-fake').innerHTML = this.value.substr(12,this.value.length - 12);
		},false);
		_.byId('add-picture-3').addEventListener('change',function(){
			_.byId('add-picture-3-fake').innerHTML = this.value.substr(12,this.value.length - 12);
		},false);
	}
}
module.exports = dashboard;