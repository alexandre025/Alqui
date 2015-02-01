<?php

namespace APP\CONTROLLERS;

class app_controller {

	private $model;
	private $tpl;  

	function __construct(){
    	$this->tpl='home.html';
    	$this->model=new \APP\MODELS\app_model();
    	new \DB\SQL\Session($this->model->dB,'sess_handler',true);
    	$f3=\Base::instance();

	}

	function home($f3){

	}

	function afterroute(){
    	echo \View::instance()->render($this->tpl);
  	}

}

?>