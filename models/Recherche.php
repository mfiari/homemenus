<?php

class Model_Recherche extends Model_Template {
	
	private $id;
	private $recherche;
	private $distance;
	private $ville;
	private $nbRestaurant;
	private $restaurants;
	private $user;
	private $date_recherche;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->id = -1;
		$this->restaurants = array();
		$this->user = NULL;
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
		if ($this->id == -1) {
			$this->insert();
		}
	}
	
	public function insert () {
		$sql = "INSERT INTO recherches (recherche, distance, ville, nb_restaurant, id_user)
		VALUES (:recherche, :distance, :ville, :nb_restaurant, :id_user)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":recherche", $this->recherche);
		$stmt->bindValue(":distance", $this->distance);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":nb_restaurant", count($this->restaurants));
		if (is_null($this->user)) {
			$stmt->bindValue(":id_user", NULL, PDO::PARAM_NULL);
		} else {
			$stmt->bindValue(":id_user", $this->user->id);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		foreach ($this->restaurants as $restaurant) {
			$sql = "INSERT INTO recherche_detail (id_recherche, id_restaurant, distance) VALUES(:id_recherche, :id_restaurant, :distance)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_recherche", $this->id);
			$stmt->bindValue(":id_restaurant", $restaurant->id);
			$stmt->bindValue(":distance", $restaurant->distance);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
	}
	
	public function getAll ($dateDebut, $dateFin) {
		$sql = "SELECT id, recherche, distance, ville, nb_restaurant, id_user, date_recherche FROM recherches 
		WHERE date_recherche BETWEEN :date_debut AND :date_fin
		ORDER BY date_recherche DESC";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$resultats = $stmt->fetchAll();
		$list = array();
		foreach ($resultats as $resultat) {
			$recherche = new Model_Recherche(false);
			$recherche->id = $resultat['id'];
			$recherche->recherche = $resultat['recherche'];
			$recherche->distance = $resultat['distance'];
			$recherche->ville = $resultat['ville'];
			$recherche->nbRestaurant = $resultat['nb_restaurant'];
			$recherche->date_recherche = $resultat['date_recherche'];
			$list[] = $recherche;
		}
		return $list;
	}
	
	public function load () {
		$sql = "SELECT recherche, distance, ville, nb_restaurant, id_user, date_recherche FROM recherches 
		WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->recherche = $value['recherche'];
		$this->distance = $value['distance'];
		$this->ville = $value['ville'];
		$this->nbRestaurant = $value['nb_restaurant'];
		$this->date_recherche = $value['date_recherche'];
		
		$sql = "SELECT restaurants.id, nom, distance FROM restaurants JOIN recherche_detail rd ON rd.id_restaurant = restaurants.id WHERE rd.id_recherche = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$resultats = $stmt->fetchAll();
		foreach ($resultats as $resultat) {
			$restaurant = new Model_Restaurant(false);
			$restaurant->id = $resultat['id'];
			$restaurant->nom = $resultat['nom'];
			$restaurant->distance = $resultat['distance'];
			$this->restaurants[] = $restaurant;
		}
		return $this;
	}
	
	
}