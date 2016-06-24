<?php

class Model_CodePromo extends Model_Template {
	
	private $id;
	private $code;
	private $description;
	private $date_debut;
	private $date_fin;
	private $publique;
	private $sur_restaurant;
	private $type_reduc;
	private $sur_prix_livraison;
	private $valeur_prix_livraison;
	private $sur_prix_total;
	private $valeur_prix_total;
	private $pourcentage_prix_total;
	private $restaurants;
	private $users;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->id = -1;
		$this->restaurants = array();
		$this->users = array();
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
	
	public function addUser ($user) {
		$this->users[] = $user;
	}
	
	public function isPrivate () {
		return $this->publique == 0;
	}
	
	public function isPublic () {
		return $this->publique == 1;
	}
	
	public function surRestaurant () {
		return $this->sur_restaurant == 1;
	}
	
	public function surPrixLivraison () {
		return $this->sur_prix_livraison == 1;
	}
	
	public function surPrixTotal () {
		return $this->sur_prix_total == 1;
	}
	
	public function estGratuit () {
		return $this->type_reduc == "GRATUIT";
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
			$sql = "INSERT INTO recherche_detail (id_recherche, id_restaurant) VALUES(:id_recherche, :id_restaurant)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_recherche", $this->id);
			$stmt->bindValue(":id_restaurant", $restaurant->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
	}
	
	public function getAll () {
		$sql = "SELECT id, code, description, date_debut, date_fin, publique 
		FROM code_promo 
		ORDER BY date_debut DESC, date_fin DESC";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$resultats = $stmt->fetchAll();
		$list = array();
		foreach ($resultats as $resultat) {
			$codePromo = new Model_CodePromo(false);
			$codePromo->id = $resultat['id'];
			$codePromo->code = $resultat['code'];
			$codePromo->description = $resultat['description'];
			$codePromo->date_debut = $resultat['date_debut'];
			$codePromo->date_fin = $resultat['date_fin'];
			$codePromo->publique = $resultat['publique'];
			$list[] = $codePromo;
		}
		return $list;
	}
	
	public function loadByCode () {
		$sql = "SELECT id, code, description, date_debut, date_fin, publique, sur_restaurant, type_reduc, sur_prix_livraison, valeur_prix_livraison, sur_prix_total, 
		valeur_prix_total, pourcentage_prix_total
		FROM code_promo
		WHERE code = :code";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":code", $this->code);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $value['id'];
		$this->code = $value['code'];
		$this->description = $value['description'];
		$this->date_debut = $value['date_debut'];
		$this->date_fin = $value['date_fin'];
		$this->publique = $value['publique'];
		$this->sur_restaurant = $value['sur_restaurant'];
		$this->type_reduc = $value['type_reduc'];
		$this->sur_prix_livraison = $value['sur_prix_livraison'];
		$this->valeur_prix_livraison = $value['valeur_prix_livraison'];
		$this->sur_prix_total = $value['sur_prix_total'];
		$this->valeur_prix_total = $value['valeur_prix_total'];
		$this->pourcentage_prix_total = $value['pourcentage_prix_total'];
		
		if ($this->sur_restaurant == 1) {
			$sql = "SELECT resto.id, resto.nom
			FROM restaurants resto
			JOIN code_promo_restaurant cpr ON cpr.id_restaurant = resto.id
			WHERE cpr.id_code_promo = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $this->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$resultats = $stmt->fetchAll();
			$list = array();
			foreach ($resultats as $resultat) {
				$restaurant = new Model_Restaurant(false);
				$restaurant->id = $value['id'];
				$restaurant->nom = $value['nom'];
				$this->restaurants[] = $restaurant;
			}
		}
		
		if ($this->publique == 0) {
			$sql = "SELECT user.uid
			FROM users user
			JOIN code_promo_user cpu ON cpu.id_user = user.uid
			WHERE cpu.id_code_promo = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $this->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$resultats = $stmt->fetchAll();
			$list = array();
			foreach ($resultats as $resultat) {
				$user = new Model_User(false);
				$user->id = $value['uid'];
				$this->users[] = $restaurant;
			}
		}
		
		return $this;
	}
	
	public function getByCode () {
		$sql = "SELECT id, code, description, date_debut, date_fin, publique, sur_restaurant, type_reduc, sur_prix_livraison, valeur_prix_livraison, sur_prix_total, 
		valeur_prix_total, pourcentage_prix_total
		FROM code_promo
		WHERE code = :code";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":code", $this->code);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		$this->id = $value['id'];
		$this->code = $value['code'];
		$this->description = $value['description'];
		$this->date_debut = $value['date_debut'];
		$this->date_fin = $value['date_fin'];
		$this->publique = $value['publique'];
		$this->sur_restaurant = $value['sur_restaurant'];
		$this->type_reduc = $value['type_reduc'];
		$this->sur_prix_livraison = $value['sur_prix_livraison'];
		$this->valeur_prix_livraison = $value['valeur_prix_livraison'];
		$this->sur_prix_total = $value['sur_prix_total'];
		$this->valeur_prix_total = $value['valeur_prix_total'];
		$this->pourcentage_prix_total = $value['pourcentage_prix_total'];
		
		return $this;
	}
	
	public function isBoundToRestaurant ($id_restaurant) {
		$sql = "SELECT cpr.id
		FROM restaurants resto
		JOIN code_promo_restaurant cpr ON cpr.id_restaurant = resto.id
		WHERE cpr.id_code_promo = :id AND resto.id = :id_resto";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":id_resto", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		return true;
	}
	
	public function isBoundToUser ($id_user) {
		$sql = "SELECT cpu.id
			FROM users user
			JOIN code_promo_user cpu ON cpu.id_user = user.uid
			WHERE cpu.id_code_promo = :id AND user.uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":uid", $id_user);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		return true;
	}
	
	public function hasBeenUseByUser ($id_user) {
		return false;
	}
	
	
}