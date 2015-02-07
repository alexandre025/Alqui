<?php

namespace APP\CONTROLLERS;

class offer_controller {

	private $model;
	private $tpl;  

	function __construct(){
    $f3=\Base::instance();
    $this->tpl=array(
      'sync'=>'home.html',
      'async'=>''
    );
    $this->model=new \APP\MODELS\offer_model();
  	new \DB\SQL\Session($this->model->dB,'sess_handler',true); 	
	}

	function offerAdd($f3){
    if($f3->get('VERB')=='GET'){
      $f3->set('categories',$this->model->getCategories());
      $this->tpl['sync']='offerAdd.html';
    }else{
      // ADD
    }
  }

	function afterroute($f3){
    $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
    echo \View::instance()->render($tpl);
  }

}

?>