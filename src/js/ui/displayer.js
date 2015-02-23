'use.strict'

var _ = require('../tools.js');

var displayer = {

	init : function(){
        if(_.byId('login-link')){
            displayer.loginPopup();
        }
        displayer.wish();
	},
	// Login popup toggle
	loginPopup : function(){

		// Click on login link      
        _.byId('login-link').addEventListener('click',function(e){
            e.preventDefault();
            _.byId('login-overlay').classList.add('active');
        },false);

		// Press escape 
		window.addEventListener('keyup', function(e) {
            if (e.keyCode === 27) {
            	displayer.closeLogin();
            }
        },false);

		// Click on overlay
        _.byId('login-overlay').addEventListener('click',function(e){
        	displayer.closeLogin();
        },false);
        // Stop bubbling when clicking on the form
        _.byClass('login-form')[0].addEventListener('click',function(e){
          e.stopPropagation();
        },false);

	},
	closeLogin : function(){
		_.byId('login-overlay').classList.remove('active');
	},
    wish : function(){
        var wished=_.byClass('wished'); // DEJA DANS LA LISTE
        for (var i = 0; i < wished.length; i++) {
            wished[i].addEventListener('click',function(e){
                e.preventDefault();
            },false);
        };

        var notLogged=_.byClass('wish-not-logged'); // PAS CONNECTE
        for (var i = 0; i < notLogged.length; i++) {
            notLogged[i].addEventListener('click',function(e){
                e.preventDefault();
            },false);
        };

        var wishable=_.byClass('not-wished');
        for (var i = 0; i < wishable.length; i++) {
            wishable[i].addEventListener('click',function(e){
                var self=this;
                e.preventDefault();
                var url=self.getAttribute('href');
                _.async('POST',url,'',function(xhr){
                    self.classList.remove('not-wished');
                    self.classList.add('wished');
                    self.setAttribute('data-title','Cette offre est déjà dans votre liste de souhaits');
                    self.addEventListener('click',function(e){
                        e.preventDefault();
                    },false);
                });
            },false);
        }
    }
};
module.exports = displayer;