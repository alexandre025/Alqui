<?php

namespace APP\CONTROLLERS;

class app_controller {

	private $model;
	private $tpl;  

	function __construct(){
        $f3=\Base::instance();
        $this->tpl=array(
            'sync'=>'home.html',
            'async'=>''
        );
    	$this->model=new \APP\MODELS\app_model();
    	//new \DB\SQL\Session($this->model->dB,'sess_handler',true);
    	
	}

	function home($f3){

	}
  
    function login($f3){
      if($f3->get('VERB')=='POST'){
        //$auth=$this->model->login($f3->get('POST'));
        $auth = true;
        if($auth){ // auth succes -> set SESSION and reroute
          $user=array(
            'id'=>'test',
            'name'=>'Alexandre'
          );
          $f3->set('SESSION',$user);

            $f3->set('login_error','Connecté');
            $f3->reroute('/');
        }else{ // auth fail -> Show error
            $f3->set('login_error','Votre combinaison e-mail/mot de passe est incorrecte');
        }
      }
    }
    public function logout($f3){
      $f3->clear('SESSION');
      $f3->reroute('/');
    }

	function afterroute($f3){
        $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
        echo \View::instance()->render($tpl);
  	}

}

?>