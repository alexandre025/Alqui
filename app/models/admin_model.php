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
      return $this->getMapper('admin')->load(array('login=:login and password=:password',':login'=>$params['login'],':password'=>$params['password']));
    }

	public function log(){
		return $this->dB->log();
	}

	

}

?>