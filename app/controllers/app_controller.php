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
    $pattern=explode('/',$f3->get('PATTERN'));
    $pattern=$pattern[1];
    if($pattern=='account'&&!$f3->get('SESSION.id')){
      $f3->reroute('/');
    }
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
            $f3->set('home_counter',$this->model->homeCounter());
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
        'photo'=>$auth->photo,
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
      $f3->set('offers',$this->model->getOwnOffers($f3->get('SESSION.id')));
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

    public function infoEdit($f3){
      if($f3->get('VERB')=='POST'){
        $params=$this->model->infoEdit($f3->get('POST'),$f3->get('SESSION.id'));
        $f3->set('edited','info');
        $f3->set('SESSION.address',$params['address']);
        $f3->set('SESSION.postal',$params['postal']);
        $f3->set('SESSION.city',$params['city']);
        $f3->set('SESSION.country',$params['country']);
        $this->tpl['sync']="userEdit.html";
      }      
    }

    public function photoEdit($f3){
      if($f3->get('VERB')=='POST'){
        //$params=$this->model->photoEdit($fileName,$f3->get('SESSION.id'));
        $succes=\Web::instance()->receive(function($file,$formFieldName){
          $type = explode('/',$file['type']);
          if($file['size'] < (2 * 1024 * 1024) && $type[0] == 'image'){
            return true;
          }
          return false;
        },true,function($fileBaseName, $formFieldName){
          $ext=explode('.',$fileBaseName);
          $ext='.'.end($ext);
          $name='profil_'.time().'_'.rand().$ext;
          return $name;
        });
        $fileName=array_keys($succes)[0];
        if($succes[$fileName]){
          $this->model->photoEdit($fileName,$f3->get('SESSION.id'));
          $f3->clear('failed');
          $f3->set('edited','photo');
          $f3->set('SESSION.photo',$fileName);
        }else{
          $f3->clear('edited');
          $f3->set('failed','photo');
        }
        $this->tpl['sync']="userEdit.html";
      }      
    }

	function afterroute($f3){
    if(isset($_GET['format'])&&$_GET['format']=='json'){
      
      if(isset($_GET['callback'])){
        header('Content-Type: application/javascript');
        echo $_GET['callback'].'('.json_encode($this->result).')';
      }else{
        header('Content-Type: application/json');
        echo json_encode($this->result);
      }
    }
    else{
      $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
      echo \View::instance()->render($tpl);
    }
  }

}

?>