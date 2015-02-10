<?php

namespace APP\CONTROLLERS;

class offer_controller {

	private $model;
	private $tpl; 
  private $result; 

	function __construct(){
    $f3=\Base::instance();
    $this->tpl=array(
      'sync'=>'home.html',
      'async'=>''
    );
    $this->model=new \APP\MODELS\offer_model();
  	new \DB\SQL\Session($this->model->dB,'sess_handler',true); 	
    $pattern=explode('/',$f3->get('PATTERN'));
    $pattern=$pattern[1];
    if($pattern=='account'&&!$f3->get('SESSION.id')){
       $f3->reroute('/');
    }
	}

	function offerAdd($f3){
    if($f3->get('VERB')=='GET'){
      $f3->set('categories',$this->model->getCategories());
      $this->tpl['sync']='offerAdd.html';
    }else{
      $id=$this->model->offerAdd($f3->get('POST'),$f3->get('SESSION.id'));
      $id=$id[0]['id'];
      $files=\Web::instance()->receive(function($file,$formFieldName){
          $type = explode('/',$file['type']);
          if($file['size'] < (2 * 1024 * 1024) && $type[0] == 'image'){
            return true;
          }
          return false;
        },true,function($fileBaseName, $formFieldName){
          $ext=explode('.',$fileBaseName);
          $ext='.'.end($ext);
          $name='offer_'.time().'_'.rand().$ext;
          return $name;
      });
      foreach ($files as $file => $isUpload) {
        if($isUpload==1){
          $this->model->offerAddPhoto($file,$id);
        }
      }
      $this->tpl['sync']='account.html';
    }
  }

  function search($f3,$params){
    if($f3->get('VERB')=='POST'){
      $f3->set('search.name',$f3->get('POST.name'));
      $f3->set('search.location',$f3->get('POST.location'));

      if($f3->get('POST.order')){
        $f3->set('search.order',$f3->get('POST.order'));
      }
      if($f3->get('POST.price')){
        $f3->set('search.price',$f3->get('POST.price'));
      }
    }
    $f3->set('search.category',$params['cat']);
    if($params['cat']=='all'){
      $f3->set('category_name','Toute les catégories');
    }else{
      $f3->set('category_name',$this->model->getCategoryName($params['cat']));
    }
    

    $this->result=$this->model->search($f3);
    $f3->set('data',$this->result);
    $f3->set('categories',$this->model->getCategories());
    $this->tpl['sync']='search.html';

  }

  function showOffer($f3,$params){
    $this->result=$this->model->getOffer($params['offer'])[0];
    $photos=$this->model->getPhotos($params['offer']);
    $num=1;
    foreach ($photos as $photo => $value) {
      $key='photo_'.$num;
      $this->result[$key]=$value['photo_name'];
      $num = $num+1;
    }
    $f3->set('data',$this->result);
    $this->tpl['sync']='offer.html';
  }

  function newReservation($f3,$params){
    $this->model->newReservation($f3->get('POST'),$params['offer'],$f3->get('SESSION.id'));
    $this->reroute($f3->get('PATTERN'));
  }

  function deleteReservation($f3,$params){ 

  }

  function acceptReservation($f3,$params){

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