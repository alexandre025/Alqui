<?php

namespace APP\CONTROLLERS;

class app_controller {

	private $model;
	private $tpl;  

	function __construct(){
        $this->tpl=array(
            'sync'=>'home.html',
            'async'=>''
        );
    	$this->model=new \APP\MODELS\app_model();
    	new \DB\SQL\Session($this->model->dB,'sess_handler',true);
    	$f3=\Base::instance();

	}

	function home($f3){

	}
  
    function login($f3){
      
      if($f3->get('VERB')=='POST'){
//        $auth=$this->model->login($f3->get('POST'));
        $auth = false;
        if($auth){ // auth succes
          $f3->reroute('/');
        }else{ // auth fail
            $this->tpl['async']='partials/login-error.html';
            $f3->set('login_error','Votre combinaison e-mail/mot de passe est incorrecte');
        }
      }
    }

	function afterroute($f3){
        $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
        echo \View::instance()->render($tpl);
  	}

}

?>