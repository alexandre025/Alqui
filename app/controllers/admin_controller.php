<?php

// Édition d'informations
// Création de compte, connexion, déconnexion
// Gestion de ses offres, commentaires, envies et réservations
namespace APP\CONTROLLERS;

class app_controller {

  	private $model;
  	private $tpl;  

  	function __construct(){
      $f3=\Base::instance();
      $this->tpl=array(
        'sync'=>'admin_login.html',
        'async'=>''
      );
      $this->model=new \APP\MODELS\app_model();
    	new \DB\SQL\Session($this->model->dB,'sess_handler',true);
      $pattern=explode('/',$f3->get('PATTERN'));
      $pattern=$pattern[1];
      if($pattern=='admin'&&!$f3->get('SESSION.id')){
        $f3->reroute('/');
      }
  	}



	function afterroute($f3){
    // API ENDPOINT
    if(isset($_GET['format'])&&$_GET['format']=='json'){
      if(isset($_GET['callback'])){ // JS OBJECT
        header('Content-Type: application/javascript');
        echo $_GET['callback'].'('.json_encode($this->result).')';
      }else{ // JSON
        header('Content-Type: application/json');
        echo json_encode($this->result);
      }
    }
    else{ // TEMPLATING RENDER
      $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
      echo \View::instance()->render($tpl);
    }
  }

}

?>