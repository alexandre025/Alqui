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
			// change header
			this.setAttribute('class','active');
			locations.removeAttribute('class');
			reservations.removeAttribute('class');
			wishlists.removeAttribute('class');

			// change content
			_.byId('notifications-container').setAttribute('class','active');
			_.byId('own-offer-container').setAttribute('class','dashboard-display');
			_.byId('add-offer-container').setAttribute('class','dashboard-display');
			_.byId('wishlist-container').setAttribute('class','dashboard-display');
			_.byId('reservations-container').setAttribute('class','dashboard-display');
		},false);
		locations.addEventListener('click',function(){
			// change header
			this.setAttribute('class','active');
			notifs.removeAttribute('class');
			reservations.removeAttribute('class');
			wishlists.removeAttribute('class');

			// change content
			_.byId('notifications-container').setAttribute('class','dashboard-display');
			_.byId('own-offer-container').setAttribute('class','active');
			_.byId('add-offer-container').setAttribute('class','dashboard-display');
			_.byId('wishlist-container').setAttribute('class','dashboard-display');
			_.byId('reservations-container').setAttribute('class','dashboard-display');
		},false);
		wishlists.addEventListener('click',function(){
			// change header
			this.setAttribute('class','active');
			locations.removeAttribute('class');
			reservations.removeAttribute('class');
			notifs.removeAttribute('class');

			// change content
			_.byId('notifications-container').setAttribute('class','dashboard-display');
			_.byId('own-offer-container').setAttribute('class','dashboard-display');
			_.byId('add-offer-container').setAttribute('class','dashboard-display');
			_.byId('wishlist-container').setAttribute('class','active');
			_.byId('reservations-container').setAttribute('class','dashboard-display');
		},false);
		reservations.addEventListener('click',function(){
			// change header
			this.setAttribute('class','active');
			locations.removeAttribute('class');
			notifs.removeAttribute('class');
			wishlists.removeAttribute('class');

			// change content
			_.byId('notifications-container').setAttribute('class','dashboard-display');
			_.byId('own-offer-container').setAttribute('class','dashboard-display');
			_.byId('add-offer-container').setAttribute('class','dashboard-display');
			_.byId('wishlist-container').setAttribute('class','dashboard-display');
			_.byId('reservations-container').setAttribute('class','active');
		},false);

		var add_offer_button = _.byClass('add-offer-button');
		for(var i = 0; i < add_offer_button.length; i++){
			add_offer_button[i].addEventListener('click',function(){
				_.byId('own-offer-container').setAttribute('class','dashboard-display');
				_.byId('add-offer-container').setAttribute('class','active');
			},false);
		}
		_.byId('return-offer-location').addEventListener('click',function(){
			_.byId('own-offer-container').setAttribute('class','active');
			_.byId('add-offer-container').setAttribute('class','dashboard-display');
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