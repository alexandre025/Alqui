<?php

namespace APP\CONTROLLERS;

class app_controller {

	private $model;
	private $tpl;  

	function __construct(){
    	$this->tpl='home.html';
    	$this->model=new \APP\MODELS\app_model();
	}

	function home($f3){

	}

	function afterroute(){
    	echo \View::instance()->render($this->tpl);
  	}

}

?>