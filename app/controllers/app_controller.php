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
    //ACCUEIL
  	public function home($f3){
      $f3->set('home_counter',$this->model->homeCounter());
  	}
  
    //LOGIN
    public function login($f3){
      if($f3->get('VERB')=='POST'){
        $auth=$this->model->login($f3->get('POST'));
        if($auth&&$auth->disabled_at==0){ // auth succes -> set SESSION and reroute
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
    // DECONNEXION
    public function logout($f3){
      $f3->clear('SESSION');
      $f3->reroute('/');
    }
    // INSCRIPTION
    public function register($f3){
      if($f3->get('VERB')=='POST'){ // Register form submited
        $auth = $this->model->register($f3->get('POST'));
        $user=$this->userArray($auth);
        $f3->set('SESSION',$user);
        $f3->reroute('/');
      }
    }
    // INITIALISATION DE LA CONNECTION
    private function userArray($auth){
      $notif=$this->model->selectNotifications($auth->id);
      $notif=count($notif['new_reserv'])+count($notif['own_reserv']);
      $wishlist=$this->model->getWish($auth->id);
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
        'created_at'=>$auth->created_at,
        'notif'=>$notif,
        'wishlist'=>$wishlist
      );
      return $user;
    }
    // TEST DE LA VALIDITE DE LEMAIL A LINSCRIPTION
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

    // PAGE COMPTE / DASHBOARD
    public function account($f3){
      $f3->set('offers',$this->model->getOwnOffers($f3->get('SESSION.id')));
      $f3->set('reservations',$this->model->getOwnReserv($f3->get('SESSION.id')));
      $f3->set('wishlist',$this->model->getWishlist($f3->get('SESSION.id')));
      $f3->set('categories',$this->model->getCategories());
      $notifs=$this->model->selectNotifications($f3->get('SESSION.id'));
      $f3->set('notifs',$notifs);
      $notifs_count=count($notifs['new_reserv'])+count($notifs['own_reserv']);
      $f3->set('notifs_count',$notifs_count);
      $this->result=array($f3->get('offers'),$f3->get('reservations'),$f3->get('wishlist'),$f3->get('notifs'),$f3->get('categories'));
      $this->tpl['sync']="account.html";
    }
    // AJOUT DUNE OFFRE
    function offerAdd($f3){
      if($f3->get('VERB')=='POST'){
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
        $f3->reroute('/account?view=offers');
      }
    }

    // SUPPRIMER DEFINITIVEMENT UNE OFFRE
    public function deleteOffer($f3,$params){
      $this->model->deleteOffer($params['offer']);
      $f3->reroute('/account?view=offers');
    }

    // RENDRE INDISPONIBLE UNE OFFRE
    public function unavailableOffer($f3,$params){
      $this->model->unavailableOffer($params['offer']);
      $f3->reroute('/account?view=offers');
    }

    // RENDRE DISPONIBLE UNE OFFRE
    public function availableOffer($f3,$params){
      $this->model->availableOffer($params['offer']);
      $f3->reroute('/account?view=offers');
    }

    // SUPPRIMER DEFINITVEMENT UNE RESERVATION
    public function deleteReservation($f3,$params){ 
      $this->model->deleteReservation($params['reserv']);
      $f3->reroute('/account?view=offers');
    }

    // REFUSER UNE OFFRE DE RESERVATION
    public function refuseReservation($f3,$params){
      $this->model->refuseReservation($params['reserv']);
      $f3->reroute('/account?view=offers');
    }

    // ACCEPTER UNE OFFRE DE RESERVATION
    public function acceptReservation($f3,$params){
      $this->model->acceptReservation($params['reserv']);
      $f3->reroute('/account?view=offers');   
    }

    // PAGE EDITION DU COMPTE
    public function userEdit($f3){
      $f3->clear('edited');
      $this->tpl['sync']="userEdit.html";
    }

    // MODIFIER LE MOT DE PASSE
    public function passwordEdit($f3){
      if($f3->get('VERB')=='POST'){
        $this->model->passwordEdit($f3->get('POST'),$f3->get('SESSION.id'));
        $f3->set('edited','password');
        $this->tpl['sync']="userEdit.html";
      }
    }

    // MODIFIER SES COORDONNEES
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

    // SUPPRIMER UN SOUHAIT
    public function deleteWish($f3,$params){
      $this->model->deleteWish($params['id']);
      $f3->set('SESSION.wishlist',$this->model->getWishlist($f3->get('SESSION.id')));
      $f3->reroute('/account?view=wishlist');
    }

    public function addComment($f3,$params){
      $this->model->addComment($f3->get('SESSION.id'),$params['id'],$f3->get('POST'));
      $f3->reroute('/account?view=reserv');
    }

    // MODIFIER SA PHOTO DE PROFIL
    public function photoEdit($f3){
      if($f3->get('VERB')=='POST'){
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