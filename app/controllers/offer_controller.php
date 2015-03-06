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
      if($f3->get('POST.availability')){
        $f3->set('search.availability',$f3->get('POST.availability'));
      }
    }
    $f3->set('search.category',$params['cat']);
    if($params['cat']=='all'){
      $f3->set('category_name','Toutes les catégories');
    }else{
      $f3->set('category_name',$this->model->getCategoryName($params['cat']));
    }
    

    $this->result=$this->model->search($f3);
    $f3->set('data',$this->result);
    $f3->set('categories',$this->model->getCategories());
    $this->tpl['sync']='search.html';

  }

  function showOffer($f3,$params){
    $this->result=$this->model->getOffer($params['offer'],$f3);
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

  //AJOUTER UNE ENVIE
  public function addToWishlist($f3,$params){
    if(!$this->model->isInWishlist($params['id'],$f3->get('SESSION.id'))){
      $this->model->addToWishlist($f3->get('SESSION.id'),$params['id']);
      $wishlist=$f3->get('SESSION.wishlist');
      array_push($wishlist,$params['id']);
      $f3->set('SESSION.wishlist',$wishlist);
    }
    exit;
  }
  // CREER UNE NOUVELLE RESERVATION
  public function newReservation($f3,$params){
    $this->model->newReservation($f3->get('POST'),$params['offer'],$f3->get('SESSION.id'));
    $f3->reroute('/account?view=reserv');
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