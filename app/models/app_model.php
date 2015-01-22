<?php

class app_model {
	
	private var $dB;

	function __constructor(){
		$this->dB = new DB\SQL('mysql:host=localhost;port=3306;dbname=location','location','location');
	}
}

?>