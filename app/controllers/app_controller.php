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
    $f3->set('home_counter',$this->model->homeCounter());
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
        $newUser = $this->model->register($f3->get('POST'));
          $user=array(
            'id'=>$newUser->id,
            'firstname'=>$newUser->firstname,
            'lastname'=>$newUser->lastname,
            'firstname'=>$newUser->firstname,
            'mark'=>$newUser->mark,
            'created_at'=>$newUser->created_at
          );
          $f3->set('SESSION',$user);
          $f3->reroute('/');
      }else{ // GET register page
        $this->tpl['sync']='register.html';
      }
    }
    public function checkEmail($f3){
      if($f3->get('VERB')=='POST'){
        $available=$this->model->checkEmail($f3->get('POST'));
        if($available){
          $f3->set('email_check','Cette adresse n\'est pas disponible');
        }else{
          $f3->set('email_check','Cette adresse est valide');
        }
      }
      $this->tpl['async']='partials/email-check.html';
    }

	function afterroute($f3){
    $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
    echo \View::instance()->render($tpl);
  }

}

?>