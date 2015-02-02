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
    	new \DB\SQL\Session($this->model->dB,'sess_handler',true);
    	
	}

	public function home($f3){

	}
  
    public function login($f3){
      if($f3->get('VERB')=='POST'){
        $auth=$this->model->login($f3->get('POST'));
        if($auth){ // auth succes -> set SESSION and reroute
          $user=array(
            'id'=>$auth->id,
            'firstname'=>$auth->firstname,
            'lastname'=>$auth->lastname,
            'firstname'=>$auth->firstname,
            'mark'=>$auth->mark,
            'created_at'=>$auth->created_at
          );
          $f3->set('SESSION',$user);
          $f3->clear('login_error');
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
  
    public function register($f3){
      if($f3->get('VERB')=='POST'){ // Register form submited
        
      }else{ // GET
        $this->tpl['sync']='register.html';
      }
    }

	function afterroute($f3){
        $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
        echo \View::instance()->render($tpl);
  	}

}

?>