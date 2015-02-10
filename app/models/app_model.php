<?php

namespace APP\MODELS;

class app_model {
	
	public $dB;
	private $smtp;

	function __construct(){
		$f3=\Base::instance();
    	$this->dB=new \DB\SQL('mysql:host='.$f3->get('db_host').';port='.$f3->get('db_port').';dbname='.$f3->get('db_name'),$f3->get('db_login'),$f3->get('db_password'));
    	$this->smtp=new \smtp($f3->get('smtp_host'),$f3->get('smtp_port'),$f3->get('smtp_scheme'),$f3->get('smtp_user'),$f3->get('smtp_password'));
	}

	private function getMapper($table){
		return new \DB\SQL\Mapper($this->dB,$table);
	}

	public function homeCounter(){
		return $counter = array(
			'offer'=>$this->getMapper('offer')->count(),
			'user'=>$this->getMapper('user')->count(),
			'reservation'=>$this->getMapper('reservation')->count()
		);
	}
  
    public function login($params){
      $params['password']=sha1($params['password']);
      return $this->getMapper('user')->load(array('email=:email and password=:password',':email'=>$params['email'],':password'=>$params['password']));
    }

	public function log(){
		return $this->dB->log();
	}

	public function checkEmail($params){
		return $this->getMapper('user')->load(array('email=:email',':email'=>$params['email']));
	}

	public function register($params){
		$query='INSERT INTO user (email,password,firstname,lastname,mark,created_at) VALUES (:email,:password,:firstname,:lastname,:mark,:created_at)';  
		$mark=-1;
		$timestamp=time();
		$password=sha1($params['password']);
		$val=array(
			':email'=>$params['email'],
			':password'=>$password,
			':firstname'=>$params['firstname'],
			':lastname'=>$params['lastname'],
			':mark'=>$mark,
			':created_at'=>$timestamp
		);
		$this->dB->exec($query,$val);
		return $this->login($params);
	}

	public function passwordEdit($params,$id){
		$query='UPDATE user SET password=:password WHERE id=:id';
		$password=sha1($params['password']);
		$val=array(
			':password'=>$password,
			':id'=>$id
		);
		$this->dB->exec($query,$val);
		return;
	}
	public function infoEdit($params,$id){
		$query='UPDATE user SET address=:address, postal=:postal, city=:city, country=:country WHERE id=:id';
		$val=array(
			':address'=>$params['address'],
			':postal'=>$params['postal'],
			':city'=>$params['city'],
			':country'=>$params['country'],
			':id'=>$id
		);
		$this->dB->exec($query,$val);
		return $params;
	}
	public function photoEdit($fileName,$id){
		$query='UPDATE user SET photo=:photo WHERE id=:id';
		$val=array(
			':photo'=>$fileName,
			':id'=>$id
		);
		$this->dB->exec($query,$val);
		return;
	}

	public function getOwnOffers($id){
		$query="SELECT offer.id, offer.name, offer.price_per_day FROM offer WHERE offer.id_user='".$id."'";

		$ownOffers = $this->dB->exec($query);
		$result=array();
		foreach ($ownOffers as $offer) {
			$query="SELECT 
				reservation.id AS reservation_id, 
				reservation.date_start, 
				reservation.date_end, 
				reservation.created_at,
				reservation.status,
				user.firstname AS user_name, 
				user.photo AS user_photo, 
				user.mark AS user_mark
				FROM reservation LEFT JOIN user 
				ON reservation.id_user=user.id 
				WHERE reservation.id_offer=:offer_id 
				AND reservation.status!='2'
				AND reservation.disabled_at IS NOT NULL
				";
				$val=array(':offer_id'=>$offer['id']);
				$reservations = $this->dB->exec($query,$val);
				$offer['reservations']=$reservations;
				array_push($result,$offer);
		}
		return $result;
	}
}

?>