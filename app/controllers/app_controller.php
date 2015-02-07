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
          $user=$this->userArray($auth);
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
        $auth = $this->model->register($f3->get('POST'));
        $user=$this->userArray($auth);
        $f3->set('SESSION',$user);
        $f3->reroute('/');
      }else{ // GET register page
        $this->tpl['sync']='register.html';
      }
    }
    private function userArray($auth){
      $user=array(
        'id'=>$auth->id,
        'email'=>$auth->email,
        'firstname'=>$auth->firstname,
        'lastname'=>$auth->lastname,
        'address'=>$auth->address,
        'postal'=>$auth->postal,
        'city'=>$auth->city,
        'country'=>$auth->country,
        'img'=>$auth->photo,
        'mark'=>$auth->mark,
        'created_at'=>$auth->created_at
      );
      return $user;
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

    public function account($f3){
      $this->tpl['sync']="account.html";
    }

    public function userEdit($f3){
      $f3->clear('edited');
      $this->tpl['sync']="userEdit.html";
    }

    public function passwordEdit($f3){
      if($f3->get('VERB')=='POST'){
        $this->model->passwordEdit($f3->get('POST'),$f3->get('SESSION.id'));
        $f3->set('edited','password');
        $this->tpl['sync']="userEdit.html";
      }
    }

	function afterroute($f3){
    $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
    echo \View::instance()->render($tpl);
  }

}

?>