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

	public function search($f3){
		$query="SELECT offer.id,offer.id_category,offer.price_per_day,offer.name,offer.location,photo.photo_name FROM offer LEFT JOIN photo ON offer.id=photo.id_offer";
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
			$order="ORDER BY ".$f3->get('search.order');
		}else{
			$order="ORDER BY offer.created_at DESC";
		}
		if($f3->get('search.availability')){
			if($f3->get('search.availability')==1){
				$availability="offer.availability='".$f3->get('search.availability')."'";
			}else{
				$availability="offer.availability IS NOT NULL";
			}
		}else{
			$availability="offer.availability='1'";
		}
		$query .= " WHERE ".$name." AND ".$location." AND ".$price." AND ".$category." AND ".$availability." AND offer.disabled_at='0' GROUP BY offer.id ".$order;
		return $this->dB->exec($query);
	}

	public function getOffer($id_offer){
		$query="SELECT 
			category.name AS category_name,
			category.id AS category_id,
			offer.id,
			offer.location,
			offer.price_per_day,
			offer.availability,
			offer.disabled_at,
			offer.name,
			offer.content,
			user.firstname AS user_name,
			user.photo AS user_photo,
			user.mark AS user_rank,
			user.id AS user_id
			FROM offer,user,category 
			WHERE offer.id_category=category.id 
			AND offer.id_user=user.id
			AND offer.id='".$id_offer."'";
		$result=$this->dB->exec($query)[0];

		$query="SELECT
			comment.content,
			comment.created_at AS comment_date,
			user.photo AS user_photo,
			user.firstname AS user_name
			FROM comment,offer,user
			WHERE user.id=comment.id_from
			AND comment.id_to=:id_to
			GROUP BY comment.id
			ORDER BY comment.created_at DESC 
			LIMIT 5
		";
		$val=array(':id_to'=>$result['user_id']);
		$result['comments']=$this->dB->exec($query,$val);
		$result['user_nb_of_comments']=$this->dB->exec("SELECT COUNT(comment.id_to) AS count FROM comment WHERE comment.id_to=:id_to",$val)[0]['count'];
		return $result;
	}

	public function getPhotos($id_offer){
		$query="SELECT photo_name FROM photo WHERE id_offer='".$id_offer."'";
		$result=$this->dB->exec($query);
		return $result;
	}

	public function newReservation($params,$id_offer,$id_user){
		$query="INSERT INTO reservation (id_user,id_offer,status,date_start,date_end,created_at) VALUES (:id_user,:id_offer,:status,:date_start,:date_end,:created_at)";
		list($day, $month, $year) = explode('/',$params['date_start']);
		$params['date_start'] = mktime(0, 0, 0, $month, $day, $year);
		list($day, $month, $year) = explode('/',$params['date_end']);
		$params['date_end'] = mktime(0, 0, 0, $month, $day, $year);
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

	public function addToWishlist($id_user,$id_offer){
		$query="INSERT INTO wish (id_user,id_offer) VALUES (:id_user,:id_offer)";
		$val=array(
			':id_user'=>$id_user,
			':id_offer'=>$id_offer
		);
		$this->dB->exec($query,$val);
	}

	public function isInWishlist($id_offer,$id_user){
		return $this->dB->exec("SELECT id FROM wish WHERE id_user='".$id_user."' AND id_offer='".$id_offer."'");
	}

	public function log(){
		return $this->dB->log();
	}

}

?>