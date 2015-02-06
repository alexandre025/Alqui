<?php

namespace APP\MODELS;

class app_model {
	
	public $dB;

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
}

?>