<?php

class Model_Categorie extends Model_Template {
	
	private $id;
	private $parent_categorie;
	private $id_restaurant;
	private $nom;
	private $quantite;
	private $logo;
	private $ordre;
	private $contenus;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->contenus = array();
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
	
	public function addContenu ($contenu) {
		$this->contenus[] = $contenu;
	}
	
	public function save () {
		$sql = "INSERT INTO restaurant_categorie (parent_categorie, id_restaurant, nom, ordre) 
		VALUES (:parent, :restaurant, :nom, :ordre)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":parent", $this->parent_categorie);
		$stmt->bindValue(":restaurant", $this->id_restaurant);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":ordre", $this->ordre);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$this->id = $this->db->lastInsertId();
	}
	
	public function remove () {
		$sql = "DELETE FROM restaurant_categorie WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return true;
	}
	
	public function load () {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		$this->nom = $value['nom'];
		return $this;
	}
	
	public function getParentContenu ($id_restaurant) {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id_restaurant = :id AND parent_categorie = 0 ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_restaurant);
		if (!$stmt->execute()) {
			return false;
		}
		$categories = $stmt->fetchAll();
		$list = array();
		foreach ($categories as $c) {
			$categorie = new Model_Categorie();
			$categorie->id = $c["id"];
			$categorie->nom = $c["nom"];
			$categorie->getLogo($id_restaurant);
			$list[] = $categorie;
		}
		return $list;
	}
	
	public function getChildren () {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE parent_categorie = :id ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			return false;
		}
		$categories = $stmt->fetchAll();
		$list = array();
		foreach ($categories as $c) {
			$categorie = new Model_Categorie();
			$categorie->id = $c["id"];
			$categorie->nom = $c["nom"];
			$list[] = $categorie;
		}
		return $list;
	}
	
	public function loadContenu ($id_restaurant) {
		/*$sql = "SELECT carte.id, carte.nom FROM carte
		JOIN carte_disponibilite cd ON cd.id_carte = carte.id
		JOIN restaurant_horaires rh ON rh.id = cd.id_horaire AND rh.id_jour = WEEKDAY(CURRENT_DATE)-1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR (rh.heure_debut < HOUR(CURRENT_TIME) AND rh.heure_fin > HOUR(CURRENT_TIME)))
		WHERE id_categorie = :id AND is_visible = 1 ORDER BY ordre";*/
		$sql = "SELECT carte.id, carte.nom FROM carte
		WHERE id_categorie = :id AND is_visible = 1 ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			return false;
		}
		$contenus = $stmt->fetchAll();
		foreach ($contenus as $c) {
			$contenu = new Model_Contenu();
			$contenu->id = $c["id"];
			$contenu->nom = $c["nom"];
			$contenu->getLogo($id_restaurant);
			$this->contenus[] = $contenu;
		}
		return $this;
	}
	
	public function getContenu ($id_restaurant) {
		$sql = "SELECT id, nom FROM carte WHERE id_categorie = :id ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			return false;
		}
		$contenus = $stmt->fetchAll();
		foreach ($contenus as $c) {
			$contenu = new Model_Contenu();
			$contenu->id = $c["id"];
			$contenu->nom = $c["nom"];
			$contenu->getLogo($id_restaurant);
			$this->contenus[] = $contenu;
		}
		return $this;
	}
	
	private function getLogo ($id_restaurant) {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$id_restaurant)) {
			if (file_exists($logoDirectory.$id_restaurant.'/categories/'.$this->id.'.png')) {
				$this->logo = $imgPath.$id_restaurant.'/categories/'.$this->id.'.png';
			} else if (file_exists($logoDirectory.$id_restaurant.'/categories/'.$this->id.'.jpg')) {
				$this->logo = $imgPath.$id_restaurant.'/categories/'.$this->id.'.jpg';
			} else if (file_exists($logoDirectory.$id_restaurant.'/categories/'.$this->id.'.gif')) {
				$this->logo = $imgPath.$id_restaurant.'/categories/'.$this->id.'.gif';
			} else {
				$this->logo = $imgPath.'default/cloche.jpg';
			}
		} else {
			$this->logo = $imgPath.'default/cloche.jpg';
		}
	}
}