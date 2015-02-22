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

	public function log(){
		return $this->dB->log();
	}

	

}

?>