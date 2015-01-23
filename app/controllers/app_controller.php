<?php

namespace APP\CONTROLLERS;

class app_controller {

	function __constructor(){
    	$this->tpl='home.html';
    	$this->model=new \APP\MODELS\app_model();
	}

	function home(){

	}

	function afterroute(){
    	echo \View::instance()->render($this->tpl);
  	}

}

?>