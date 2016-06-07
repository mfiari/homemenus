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
			var_dump($stmt->errorInfo());
			return false;
		}
		$this->id = $this->db->lastInsertId();
		foreach ($this->restaurants as $restaurant) {
			$sql = "INSERT INTO recherche_detail (id_recherche, id_restaurant) VALUES(:id_recherche, :id_restaurant)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_recherche", $this->id);
			$stmt->bindValue(":id_restaurant", $restaurant->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
	}
	
	public function getAll () {
		$sql = "SELECT id, recherche, distance, ville, nb_restaurant, id_user, date_recherche FROM recherches ORDER BY date_recherche DESC";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
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
	
	
}