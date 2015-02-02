<?php

namespace APP\MODELS;

class app_model {
	
	public $dB;

	function __construct(){
		$f3=\Base::instance();
    	$this->dB=new \DB\SQL('mysql:host='.$f3->get('db_host').';port='.$f3->get('db_port').';dbname='.$f3->get('db_name'),$f3->get('db_login'),$f3->get('db_password'));
	}

	private function getMapper($table){
		return new \DB\SQL\Mapper($this->dB,$table);
	}
  
    public function login($params){
      return $this->getMapper('user')->load(array('email=:email and password=:pwd',':email'=>$params['email'],':pwd'=>$params['pwd']));
    }

	public function log(){
		return $this->dB->log();
	}
}

?>