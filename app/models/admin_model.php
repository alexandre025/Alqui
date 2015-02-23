<?php

namespace APP\MODELS;

class admin_model {
	
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
  
    public function login($params){
    $params['password']=sha1($params['password']);
    $query="SELECT * FROM admin WHERE password=:password AND login=:login AND disabled_at='0'";
    $val=array(
    	':password'=>$params['password'],
    	':login'=>$params['login']
	);
    return $this->dB->exec($query,$val);
    }
    public function getUsers(){
        $query="SELECT
        user.firstname,
        user.lastname,
        user.created_at,
        user.id
        FROM user
        WHERE user.disabled_at='0'
        ORDER BY user.created_at DESC
        ";
        return $this->dB->exec($query);
    }
    public function getUser($id){
        $query="SELECT * FROM user WHERE id=:id";
        $val=array(':id'=>$id);
        return $this->dB->exec($query,$val)[0];
    }

    public function getAllOffers(){
    	$query="SELECT
    	offer.name AS offer_name,
    	offer.id AS offer_id,
    	offer.created_at AS offer_created_at,
    	offer.availability AS offer_availability,
    	user.id AS user_id,
    	user.firstname AS user_firstname,
    	user.lastname AS user_lastname
    	FROM user,offer
    	WHERE offer.id_user=user.id
    	AND offer.disabled_at='0'
    	AND offer.availability!='0'
    	ORDER BY offer.availability ASC, offer.created_at DESC
    	";
    	return $this->dB->exec($query);
    }
    public function getnewOffers(){
    	$query="SELECT
    	offer.name AS offer_name,
    	offer.id AS offer_id,
    	offer.created_at AS offer_created_at,
    	offer.availability AS offer_availability,
    	user.id AS user_id,
    	user.firstname AS user_firstname,
    	user.lastname AS user_lastname
    	FROM user,offer
    	WHERE offer.id_user=user.id
    	AND offer.disabled_at='0'
    	AND offer.availability='0'
    	ORDER BY offer.availability ASC, offer.created_at DESC
    	";
    	return $this->dB->exec($query);
    }
    public function getOffer($id_offer){
    	$query="SELECT 
			category.name AS category_name,
			offer.id,
			offer.location,
			offer.price_per_day,
			offer.availability,
			offer.name,
			offer.content,
            offer.created_at,
			user.id AS user_id,
			user.firstname AS user_firstname,
			user.lastname AS user_lastname
			FROM offer,user,category 
			WHERE offer.id_category=category.id 
			AND offer.id_user=user.id
			AND offer.id='".$id_offer."'";
		$offer=$this->dB->exec($query)[0];
		$photos=$this->dB->exec("SELECT photo_name FROM photo WHERE id_offer='".$id_offer."'");
		for ($i=0; $i < count($photos); $i++) { 
			$offer['photo_'.($i+1)]=$photos[$i]['photo_name'];
		}
		return $offer;
    }
    public function refuseOffer($id_offer){
    	$this->dB->exec("UPDATE offer SET disabled_at='".time()."' WHERE id='".$id_offer."'");
        $this->dB->exec("UPDATE reservation SET status='2' WHERE id_offer='".$id_offer."'");
    }
    public function banUser($id_user){
        $query="UPDATE reservation 
        SET reservation.status='2' 
        WHERE reservation.id_offer=offer.id
        AND offer.id_user=:id
        ";
        $val=array(':id'=>$id_user);
        $this->dB->exec($query,$val);

        $query="UPDATE offer
        SET offer.availability='2',
        offer.disabled_at=:time,
        WHERE offer.id_user=:id
        ";
        $val=array(
            ':id'=>$id_user,
            ':time'=>time()
        );
        $this->dB->exec($query,$val);

        $query="UPDATE user
        SET user.disabled_at=:time
        WHERE user.id=:id
        ";
        $this->dB->exec($query);
    }
    public function acceptOffer($id_offer){
    	$this->dB->exec("UPDATE offer SET created_at='".time()."', availability='1' WHERE id='".$id_offer."'");
    }
	public function log(){
		return $this->dB->log();
	}
}

?>