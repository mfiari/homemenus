<?php

class Model_Dispo extends Model_Template {
	
	private $id;
	private $id_livreur;
	private $rue;
	private $ville;
	private $code_postal;
	private $latitude;
	private $longitude;
	private $perimetre;
	private $vehicule;
	private $id_jour;
	private $jour;
	private $heure_debut;
	private $minute_debut;
	private $heure_fin;
	private $minute_fin;
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}
	
	public function save () {
		$sql = "INSERT INTO user_livreur_dispo (uid, rue, ville, code_postal, latitude, longitude, perimetre, vehicule, id_jour, 
		heure_debut, minute_debut, heure_fin, minute_fin) VALUES (:uid, :rue, :ville, :code_postal, :latitude, :longitude, :perimetre, :vehicule, :id_jour, 
		:heure_debut, :minute_debut, :heure_fin, :minute_fin)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id_livreur);
		$stmt->bindValue(":rue", $this->rue);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":code_postal", $this->code_postal);
		$stmt->bindValue(":latitude", $this->latitude);
		$stmt->bindValue(":longitude", $this->longitude);
		$stmt->bindValue(":perimetre", $this->perimetre);
		$stmt->bindValue(":vehicule", $this->vehicule);
		$stmt->bindValue(":id_jour", $this->id_jour);
		$stmt->bindValue(":heure_debut", $this->heure_debut);
		$stmt->bindValue(":minute_debut", $this->minute_debut);
		$stmt->bindValue(":heure_fin", $this->heure_fin);
		$stmt->bindValue(":minute_fin", $this->minute_fin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function getAllActifLivreursDispo () {
		$sql = "SELECT uld.id, user.uid, uld.rue, uld.ville, uld.code_postal, uld.latitude, uld.longitude
		FROM users user
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		WHERE user.status = 'LIVREUR' AND user.is_enable = true";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$users = $stmt->fetchAll();
		$list = array();
		foreach ($users as $usr) {
			$dispo = new Model_Dispo(false);
			$dispo->id = $usr['id'];
			$dispo->id_livreur = $usr['uid'];
			$dispo->rue = $usr['rue'];
			$dispo->ville = $usr['ville'];
			$dispo->code_postal = $usr['code_postal'];
			$dispo->latitude = $usr['latitude'];
			$dispo->longitude = $usr['longitude'];
			$list[] = $dispo;
		}
		return $list;
	}
	
	public function addDistanceLivreurResto ($id_restaurant, $id_dispo, $perimetre) {
		$sql = "INSERT INTO distance_livreur_resto (id_restaurant, id_dispo, perimetre) 
		VALUES (:id_restaurant, :id_dispo, :perimetre)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_restaurant", $id_restaurant);
		$stmt->bindValue(":id_dispo", $id_dispo);
		$stmt->bindValue(":perimetre", $perimetre);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
}