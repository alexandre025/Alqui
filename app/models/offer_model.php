<?php

namespace APP\MODELS;

class offer_model {
	
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

	public function getCategories(){
		return $this->dB->exec('SELECT * FROM category');
	}

	public function getCategoryName($id){
		return $this->dB->exec("SELECT name FROM category WHERE id='".$id."'")[0]['name'];
	}

	public function offerAdd($params,$id){
		$query='INSERT INTO offer (name,price_per_day,location,content,id_category,id_user,created_at,availability) VALUES (:name,:price,:location,:content,:id_category,:id_user,:created_at,:availability)';  
		$timestamp=time();
		$availability=1; // L'offre est publié
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

	public function search($f3){
		$query="SELECT * FROM offer LEFT JOIN photo ON offer.id=photo.id_offer";
		if($f3->get('search.name')){
			$name="offer.name LIKE '%".$f3->get('search.name')."%'";
		}else{
			$name="offer.name IS NOT NULL";
		}
		if($f3->get('search.location')){
			$location="offer.location LIKE '%".$f3->get('search.location')."%'";
		}else{
			$location="offer.location IS NOT NULL";
		}
		if($f3->get('search.price')){
			$price="offer.price_per_day < '".$f3->get('search.price')."'";
		}else{
			$price="price_per_day IS NOT NULL";
		}
		if($f3->get('search.category')!='all'){
			$category="offer.id_category = '".$f3->get('search.category')."'";
		}else{
			$category="offer.id_category IS NOT NULL";
		}
		if($f3->get('search.order')){
			$order="ORDER BY '".$f3->get('search.order')."'";
		}else{
			$order="ORDER BY offer.created_at DESC";
		}

		$query .= " WHERE ".$name." AND ".$location." AND ".$price." AND ".$category." AND offer.availability='1' GROUP BY offer.id ".$order;
		return $this->dB->exec($query);
	}

	public function newReservation($params,$id_offer,$id_user){
		$query="INSERT INTO reservation (id_user,id_offer,status,date_start,date_end,created_at) VALUES (:id_user,:id_offer,;status,:date_start,:date_end,:created_at)";
		$status=0;
		$timestamp=time();
		$val=array(
			':id_user'=>$id_user,
			':id_offer'=>$id_offer,
			':status'=>$status,
			':date_start'=>$params['date_start'],
			':date_end'=>$params['date_end'],
			':created_at'=>$timestamp
		);
		$this->dB->exec($query,$val);
	}

	public function log(){
		return $this->dB->log();
	}

}

?>