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
	}
}
module.exports = dashboard;