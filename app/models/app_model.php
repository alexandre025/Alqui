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
			'offer'=>$this->getMapper('offer')->count('disabled_at=0'),
			'user'=>$this->getMapper('user')->count(),
			'reservation'=>$this->getMapper('reservation')->count()
		);
	}
  
    public function login($params){
      $params['password']=sha1($params['password']);
      return $this->getMapper('user')->load(array('email=:email and password=:password',':email'=>$params['email'],':password'=>$params['password']));
    }

    public function getWish($id){
    	$query="SELECT id_offer FROM wish WHERE id_user=:id_user";
    	$val=array(':id_user'=>$id);
    	$result=$this->dB->exec($query,$val);
    	$wishlist=array();
    	foreach($result as $value){
    		array_push($wishlist, $value['id_offer']);
    	}
    	return $wishlist;
    }
    
	public function getCategories(){
		return $this->dB->exec('SELECT * FROM category');
	}

	public function offerAdd($params,$id){
		$query='INSERT INTO offer (name,price_per_day,location,content,id_category,id_user,created_at,availability) VALUES (:name,:price,:location,:content,:id_category,:id_user,:created_at,:availability)';  
		$timestamp=time();
		$availability=0; // L'offre est publié
		$val=array(
			':name'=>$params['name'],
			':price'=>$params['price'],
			':location'=>$params['location'],
			':content'=>$params['content'],
			':id_category'=>$params['category'],
			':id_user'=>$id,
			':created_at'=>$timestamp,
			':availability'=>$availability
		);
		$this->dB->exec($query,$val);
		$query='SELECT id FROM offer WHERE id_user=:id_user AND created_at=:created_at';
		$val=array(
			':id_user'=>$id,
			':created_at'=>$timestamp
		);
		return $this->dB->exec($query,$val);
	}
	public function offerAddPhoto($fileName,$id_offer){
		$query='INSERT INTO photo (photo_name,id_offer,created_at) VALUES (:photo_name,:id_offer,:created_at)';
		$timestamp=time();
		$val=array(
			':photo_name'=>$fileName,
			':id_offer'=>$id_offer,
			':created_at'=>$timestamp
		);
		$this->dB->exec($query,$val);
	}

	public function log(){
		return $this->dB->log();
	}

	public function checkEmail($params){
		return $this->getMapper('user')->load(array('email=:email',':email'=>$params['email']));
	}

	public function register($params){
		$query='INSERT INTO user (email,password,firstname,lastname,photo,mark,created_at) VALUES (:email,:password,:firstname,:lastname,:photo,:mark,:created_at)';  
		$mark=-1;
		$timestamp=time();
		$password=sha1($params['password']);
		$photo='img/profil_default.png';
		$val=array(
			':email'=>$params['email'],
			':password'=>$password,
			':firstname'=>$params['firstname'],
			':lastname'=>$params['lastname'],
			':mark'=>$mark,
			':photo'=>$photo,
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
		$query="SELECT 
			offer.id, 
			offer.name, 
			offer.price_per_day,
			offer.availability,
			photo.photo_name
			FROM offer,photo 
			WHERE offer.id_user=:id_user
			AND photo.id_offer=offer.id
			AND offer.disabled_at='O'
			AND offer.availability!='0'
			GROUP BY offer.id
			";
		$val=array(
			':id_user'=>$id
		);

		$ownOffers = $this->dB->exec($query,$val);
		$result=array();
		foreach ($ownOffers as $offer) {
			$query="SELECT 
				reservation.id AS reservation_id, 
				reservation.date_start, 
				reservation.date_end, 
				reservation.created_at,
				reservation.status,
				user.id AS user_id,
				user.firstname AS user_name, 
				user.email AS user_email,
				user.photo AS user_photo, 
				user.mark AS user_mark
				FROM reservation LEFT JOIN user 
				ON reservation.id_user=user.id 
				WHERE reservation.id_offer=:offer_id 
				AND reservation.status!='2'
				AND reservation.disabled_at='0'
				ORDER BY reservation.created_at DESC
				";
				$val=array(':offer_id'=>$offer['id']);
				$reservations = $this->dB->exec($query,$val);
				$offer['reservations']=$reservations;
				array_push($result,$offer);
		}
		return $result;
	}

	public function getOwnReserv($id){
		$query="SELECT
			reservation.id AS reservation_id,
			reservation.date_start,
			reservation.date_end,
			reservation.status,
			reservation.commented,
			offer.name AS offer_name,
			offer.id AS offer_id,
			photo.photo_name,
			user.firstname AS user_name, 
			user.email AS user_email,
			user.id AS user_id
			FROM reservation, offer, user, photo
			WHERE reservation.id_user=:id
			AND reservation.id_offer=offer.id
			AND photo.id_offer=offer.id
			AND offer.id_user=user.id
			AND reservation.disabled_at='0'
			AND offer.disabled_at='0'
			GROUP BY reservation.id
			ORDER BY reservation.created_at DESC
		";
		$val=array(':id'=>$id);
		return $this->dB->exec($query,$val);
	}

	public function selectNotifications($id_user){
		$query="SELECT 
			reservation.id AS reservation_id, 
			reservation.date_start, 
			reservation.date_end, 
			reservation.created_at,
			reservation.status,
			offer.name AS offer_name,
			offer.id AS offer_id,
			user.firstname AS user_name, 
			user.photo AS user_photo
			FROM reservation,offer,user 
			WHERE reservation.id_user=user.id
			AND reservation.id_offer=offer.id
			AND offer.id_user=:id_user 
			AND reservation.status='0'
			AND reservation.disabled_at='0'
			ORDER BY reservation.created_at DESC";
		$val=array(':id_user'=>$id_user);
		$second_query="SELECT 
			reservation.id AS reservation_id, 
			reservation.date_start, 
			reservation.date_end, 
			reservation.created_at,
			reservation.status,
			offer.id AS offer_id,
			user.firstname AS user_name, 
			user.photo AS user_photo
			FROM reservation,user,offer
			WHERE reservation.id_user=:id_user
			AND reservation.id_offer=offer.id
			AND offer.id_user=user.id
			AND reservation.status!='0'
			AND reservation.disabled_at='0'
			AND offer.disabled_at='0'
			ORDER BY reservation.created_at DESC";
		$result=array(
			'new_reserv'=>$this->dB->exec($query,$val), /* Les nouvelles demandes sur NOS produits */
			'own_reserv'=>$this->dB->exec($second_query,$val) /* Demande accepté sur une offre d'un autre */
		);
		return $result;
	}

	public function getWishlist($id){
		$query="SELECT
			wish.id AS wish_id,
			offer.name AS offer_name,
			offer.id AS offer_id,
			offer.price_per_day AS offer_price,
			user.firstname AS user_name,
			photo.photo_name
			FROM user,offer,wish,photo
			WHERE wish.id_user=:id
			AND wish.id_offer=offer.id
			AND photo.id_offer=offer.id
			AND user.id=offer.id_user
			GROUP BY wish.id
			ORDER BY wish.id DESC
		";
		$val=array(':id'=>$id);
		return $this->dB->exec($query,$val);
	}

	public function refuseReservation($id_reservation){
		return $this->dB->exec("UPDATE reservation SET status='2', created_at='".time()."' WHERE id='".$id_reservation."'");	
	}

	public function acceptReservation($id_reservation){
		return $this->dB->exec("UPDATE reservation SET status='1', created_at='".time()."' WHERE id='".$id_reservation."'");	
	}

	public function deleteReservation($id_reservation){
		$timestamp=time();
		return $this->dB->exec("UPDATE reservation SET disabled_at='".$timestamp."' WHERE id='".$id_reservation."'");
	}

	public function deleteOffer($id_offer){
		$timestamp=time();
		return $this->dB->exec("UPDATE offer SET disabled_at='".$timestamp."',availability='2' WHERE id='".$id_offer."'");
	}

	public function unavailableOffer($id_offer){
		return $this->dB->exec("UPDATE offer SET availability='2' WHERE id='".$id_offer."'");
	}
	public function availableOffer($id_offer){
		return $this->dB->exec("UPDATE offer SET availability='1' WHERE id='".$id_offer."'");
	}

	public function deleteWish($id){
		return $this->dB->exec("DELETE FROM wish WHERE id='".$id."'");
	}

	public function addComment($id,$id_to,$form){
		$query="INSERT INTO comment (id_from,id_to,content,mark,created_at) VALUES (:id_from,:id_to,:content,:mark,:created_at)";
		$timestamp=time();
		$val=array(
			':id_from'=>$id,
			':id_to'=>$id_to,
			'content'=>$form['content'],
			':mark'=>$form['mark'],
			':created_at'=>$timestamp
		);
		$this->dB->exec($query,$val);
		$allMark = $this->dB->exec("SELECT mark FROM comment WHERE id_to='".$id_to."'");
		$average=0;
		$count=0;
		foreach($allMark as $mark){
			$average=$average+$mark['mark'];
			$count++;
		}
		$average=intval(round($average/$count));
		$this->dB->exec("UPDATE user SET mark='".$average."' WHERE id='".$id_to."'");
		$this->dB->exec("UPDATE reservation SET commented='1' WHERE id='".$form['reservation_id']."'");
	}

}

?>