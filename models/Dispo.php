<?php

class Model_Dispo extends Model_Template {
	
	protected $id;
	private $id_livreur;
	private $livreur;
	private $rue;
	private $ville;
	private $code_postal;
	private $latitude;
	private $longitude;
	private $perimetre;
	private $vehicule;
	private $id_jour;
	private $jour;
	private $date;
	private $heure_debut;
	private $minute_debut;
	private $heure_fin;
	private $minute_fin;
	private $commande;
	private $restaurants;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->restaurants = array();
	}
	
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
	
	public function addRestaurant ($restaurant) {
		$this->restaurants[] = $restaurant;
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
	
	public function getUpdateDispoLivreur () {
		$sql = "SELECT uld.id, user.uid, uld.rue, uld.ville, uld.code_postal, uld.latitude, uld.longitude
		FROM users user
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		JOIN update_distance_dispo udd ON udd.id_dispo = uld.id
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
	
	public function getDispoByDay ($day) {
		$sql = "SELECT dispo.id, user.uid, user.nom, user.prenom, user.login, user.email, dispo.rue, dispo.ville, dispo.code_postal, dispo.latitude, 
		dispo.longitude, dispo.perimetre, dispo.vehicule, dispo.heure_debut, dispo.minute_debut, dispo.heure_fin, dispo.minute_fin
		FROM user_livreur_dispo dispo
		JOIN users user ON user.uid = dispo.uid
		WHERE dispo.id_jour = :day";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":day", $day);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$resultats = $stmt->fetchAll();
		$list = array();
		foreach ($resultats as $resultat) {
			$dispo = new Model_Dispo(false);
			$dispo->id = $resultat['id'];
			$dispo->rue = $resultat['rue'];
			$dispo->ville = $resultat['ville'];
			$dispo->code_postal = $resultat['code_postal'];
			$dispo->latitude = $resultat['latitude'];
			$dispo->longitude = $resultat['longitude'];
			$dispo->perimetre = $resultat['perimetre'];
			$dispo->vehicule = $resultat['vehicule'];
			$dispo->heure_debut = $resultat['heure_debut'];
			$dispo->minute_debut = $resultat['minute_debut'];
			$dispo->heure_fin = $resultat['heure_fin'];
			$dispo->minute_fin = $resultat['minute_fin'];
			
			$livreur = new Model_User(false);
			$livreur->id = $resultat['uid'];
			$livreur->nom = $resultat['nom'];
			$livreur->prenom = $resultat['prenom'];
			$livreur->login = $resultat['login'];
			$livreur->email = $resultat['email'];
			
			$dispo->livreur = $livreur;
			
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
	
	public function addUpdateRestaurant ($id_restaurant) {
		$sql = "INSERT INTO update_distance_restaurant (id_restaurant) VALUES (:id_restaurant)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_restaurant", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function removeUpdateRestaurant ($id_restaurant) {
		$sql = "DELETE FROM update_distance_restaurant WHERE id_restaurant = :id_restaurant";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_restaurant", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function addUpdateDispo ($id_dispo) {
		$sql = "INSERT INTO update_distance_dispo (id_dispo) VALUES (:id_dispo)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_dispo", $id_dispo);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function removeUpdateDispo ($id_dispo) {
		$sql = "DELETE FROM update_distance_dispo WHERE id_dispo = :id_dispo";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_dispo", $id_dispo);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function remove () {
		$sql = "DELETE FROM distance_livreur_resto WHERE id_dispo = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "DELETE FROM user_livreur_dispo WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getLivreurDispo () {
		$sql = "SELECT uld.id, rue, ville, code_postal, latitude, longitude, perimetre, vehicule, id_jour, heure_debut, minute_debut, heure_fin, minute_fin, 
		days.nom 
		FROM user_livreur_dispo uld
		JOIN days ON days.id = id_jour
		WHERE uid = :id
		ORDER BY id_jour, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_livreur);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$dispos = array();
		$results = $stmt->fetchAll();
		foreach ($results as $result) {
			$dispo = new Model_Dispo(false);
			$dispo->id = $result['id'];
			$dispo->rue = $result['rue'];
			$dispo->ville = $result['ville'];
			$dispo->code_postal = $result['code_postal'];
			$dispo->latitude = $result['latitude'];
			$dispo->longitude = $result['longitude'];
			$dispo->perimetre = $result['perimetre'];
			$dispo->vehicule = $result['vehicule'];
			$dispo->id_jour = $result['id_jour'];
			$dispo->jour = $result['nom'];
			$dispo->heure_debut = $result['heure_debut'];
			$dispo->minute_debut = $result['minute_debut'];
			$dispo->heure_fin = $result['heure_fin'];
			$dispo->minute_fin = $result['minute_fin'];
			$dispos[] = $dispo;
		}
		return $dispos;
	}
	
	public function getHistoryLivreurDispo ($date_debut, $date_fin) {
		$sql = "SELECT id, rue_dispo AS rue, ville_dispo AS ville, code_postal_dispo AS code_postal, latitude_dispo AS latitude, longitude_dispo AS longitude, 
		perimetre, vehicule, date_dispo, heure_debut, minute_debut, heure_fin, minute_fin
		FROM historique_dispo_livreur
		WHERE uid = :id AND date_dispo BETWEEN '$date_debut' AND '$date_fin' 
		ORDER BY date_dispo, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_livreur);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$dispos = array();
		$results = $stmt->fetchAll();
		foreach ($results as $result) {
			$dispo = new Model_Dispo(false);
			$dispo->id = $result['id'];
			$dispo->rue = $result['rue'];
			$dispo->ville = $result['ville'];
			$dispo->code_postal = $result['code_postal'];
			$dispo->latitude = $result['latitude'];
			$dispo->longitude = $result['longitude'];
			$dispo->perimetre = $result['perimetre'];
			$dispo->vehicule = $result['vehicule'];
			$dispo->date = $result['date_dispo'];
			$dispo->heure_debut = $result['heure_debut'];
			$dispo->minute_debut = $result['minute_debut'];
			$dispo->heure_fin = $result['heure_fin'];
			$dispo->minute_fin = $result['minute_fin'];
			$dispos[] = $dispo;
		}
		return $dispos;
	}
}