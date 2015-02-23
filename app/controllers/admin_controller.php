<?php

namespace APP\CONTROLLERS;

class admin_controller {

  	private $model;
  	private $tpl;  

  	function __construct(){
      $f3=\Base::instance();
      $this->tpl=array(
        'sync'=>'admin_dashboard.html',
        'async'=>''
      );
      $this->model=new \APP\MODELS\admin_model();
    	new \DB\SQL\Session($this->model->dB,'sess_handler',true);
      $pattern=explode('/',$f3->get('PATTERN'));
      $pattern=$pattern[1];
      if($pattern=='admin'&&!$f3->get('SESSION.admin_id')){
        $f3->reroute('/admin-login');
      }
  	}

    function admin($f3){
      
    }

    function login($f3){
      if($f3->get('VERB')=='POST'){
        $auth=$this->model->login($f3->get('POST'));
        if($auth){
          $auth=$auth[0];
          $admin=array(
            'admin_id'=>$auth['id'],
            'admin_name'=>$auth['login'],
          );
          $f3->set('SESSION',$admin);
          $f3->reroute('/admin');
        }
      }else{ // GET
        $this->tpl['sync']='admin_login.html';
      }
    }
    function getUsers($f3){
      $f3->set('users',$this->model->getUsers());
      $this->tpl['async']='partials/admin_users.html';
    }
    function refuseOffer($f3,$params){
      $this->model->refuseOffer($params['id']);
      $f3->set('offer',$this->model->getOffer($params['id']));
    }
    function acceptOffer($f3,$params){
      $this->model->acceptOffer($params['id']);
      $f3->set('offer',$this->model->getOffer($params['id']));
    }
    function getAllOffers($f3){
      $f3->set('offers',$this->model->getAllOffers());
      $this->tpl['async']='partials/admin_offers.html';
    }
    function getNewOffers($f3){
      $f3->set('offers',$this->model->getNewOffers());
      $this->tpl['async']='partials/admin_offers.html';
    }
    function getOffer($f3,$params){
      $f3->set('offer',$this->model->getOffer($params['id']));
      $this->tpl['async']='partials/admin_offer.html';
    }

    function logout($f3){
      $f3->clear('SESSION');
      $f3->reroute('/admin');
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