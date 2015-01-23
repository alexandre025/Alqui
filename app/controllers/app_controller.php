<?php

class app_controller {

	function __constructor(){

	}

	function home(){
		echo View::instance()->render('home.html');
	}
}

?>