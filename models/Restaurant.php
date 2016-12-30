<?php

class Model_Restaurant extends Model_Template {
	
	protected $id;
	private $logo;
	private $nom;
	private $nom_modification;
	private $nom_modification_id;
	private $rue;
	private $ville;
	private $code_postal;
	private $telephone;
	private $telephone_modification;
	private $latitude;
	private $longitude;
	private $short_desc;
	private $long_desc;
	private $distance;
	private $pourcentage;
	private $virement;
	private $horaires;
	private $horaire;
	private $certificats;
	private $categories;
	private $formats;
	private $formules;
	private $menus;
	private $supplements;
	private $accompagnements;
	private $tags;
	private $options;
	private $carte;
	private $user;
	private $has_livreur_dispo;
	private $carteImg;
	private $menuImg;
	private $is_enable;
	private $note;
	private $nb_note;
	private $commentaire;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->id = -1;
		$this->horaires = array();
		$this->certificats = array();
		$this->categories = array();
		$this->formats = array();
		$this->formules = array();
		$this->carte = array();
		$this->menus = array();
		$this->supplements = array();
		$this->tags = array();
		$this->options = array();
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
	
	public function addHoraire ($horaire) {
		$this->horaires[] = $horaire;
	}
	
	public function addCategorie ($categorie) {
		$this->categories[] = $categorie;
	}
	
	public function addCertificat ($certificat) {
		$this->certificats[] = $certificat;
	}
	
	public function save () {
		if ($this->id == -1) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	public function insert () {
		$sql = "INSERT INTO restaurants (nom, rue, ville, code_postal, telephone, latitude, longitude, short_desc, long_desc, score, pourcentage)
		VALUES (:nom, :rue, :ville, :cp, :tel, :lat, :lon, :short, :long, :score, :pourcentage)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":rue", $this->rue);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":cp", $this->code_postal);
		$stmt->bindValue(":tel", $this->telephone);
		$stmt->bindValue(":lat", $this->latitude);
		$stmt->bindValue(":lon", $this->longitude);
		$stmt->bindValue(":short", $this->short_desc);
		$stmt->bindValue(":long", $this->long_desc);
		$stmt->bindValue(":score", 0);
		$stmt->bindValue(":pourcentage", $this->pourcentage);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		foreach ($this->horaires as $horaire) {
			$sql = "INSERT INTO restaurant_horaires (id_restaurant, id_jour, heure_debut, minute_debut, heure_fin, minute_fin) 
			VALUES(:id_restaurant, :id_jour, :heure_debut, :minute_debut, :heure_fin, :minute_fin)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_restaurant", $this->id);
			$stmt->bindValue(":id_jour", $horaire->id_jour);
			$stmt->bindValue(":heure_debut", $horaire->heure_debut);
			$stmt->bindValue(":minute_debut", $horaire->minute_debut);
			$stmt->bindValue(":heure_fin", $horaire->heure_fin);
			$stmt->bindValue(":minute_fin", $horaire->minute_fin);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		$sql = "INSERT INTO restaurant_format (id_restaurant, nom) VALUES (:id, :nom)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":nom", '');
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
	}
	
	public function update () {
		$sql = "UPDATE restaurants SET nom = :nom, rue = :rue, ville = :ville, code_postal = :cp, telephone = :tel, latitude = :lat, longitude = :lon, 
		short_desc = :short, long_desc = :long, score = :score, pourcentage = :pourcentage WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":rue", $this->rue);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":cp", $this->code_postal);
		$stmt->bindValue(":tel", $this->telephone);
		$stmt->bindValue(":lat", $this->latitude);
		$stmt->bindValue(":lon", $this->longitude);
		$stmt->bindValue(":short", $this->short_desc);
		$stmt->bindValue(":long", $this->long_desc);
		$stmt->bindValue(":score", 0);
		$stmt->bindValue(":pourcentage", $this->pourcentage);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "DELETE FROM restaurant_horaires WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		foreach ($this->horaires as $horaire) {
			$sql = "INSERT INTO restaurant_horaires (id_restaurant, id_jour, heure_debut, minute_debut, heure_fin, minute_fin) 
			VALUES(:id_restaurant, :id_jour, :heure_debut, :minute_debut, :heure_fin, :minute_fin)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_restaurant", $this->id);
			$stmt->bindValue(":id_jour", $horaire->id_jour);
			$stmt->bindValue(":heure_debut", $horaire->heure_debut);
			$stmt->bindValue(":minute_debut", $horaire->minute_debut);
			$stmt->bindValue(":heure_fin", $horaire->heure_fin);
			$stmt->bindValue(":minute_fin", $horaire->minute_fin);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
	}
	
	public function get ($fields) {
		$sql = "SELECT id";
		foreach ($fields as $field) {
			$sql .= ', '.$field;
		}
		$sql .= " FROM restaurants WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		foreach ($fields as $field) {
			$this->$field = $value[$field];
		}
		return $this;
	}
	
	public function getByUser ($fields, $uid) {
		$sql = "SELECT r.id";
		foreach ($fields as $field) {
			$sql .= ', r.'.$field;
		}
		$sql .= " FROM restaurants r
		JOIN user_restaurant ur ON ur.id_restaurant = r.id
		WHERE ur.uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		foreach ($fields as $field) {
			$this->$field = $value[$field];
		}
		return $this;
	}
	
	public function loadModifications () {
		$sql = "SELECT id, field, new_value FROM modifications WHERE tables = :table AND types = 'UPDATE'	AND id_column = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":table", "restaurants");
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$modifications = $stmt->fetchAll();
		foreach ($modifications as $modification) {
			switch ($modification['field']) {
				case 'nom' :
					$this->nom_modification = $modification['new_value'];
					$this->nom_modification_id = $modification['id'];
					break;
			}
		}
	}
	
	public function loadMinInformation () {
		$sql = "SELECT id, nom FROM restaurants WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		$this->nom = $value['nom'];
		return $this;
	}
	
	public function getOne () {
		$sql = "SELECT nom, rue, ville, code_postal, telephone, short_desc, long_desc, pourcentage, virement FROM restaurants WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->telephone = $value['telephone'];
		$this->short_desc = $value['short_desc'];
		$this->long_desc = $value['long_desc'];
		$this->pourcentage = $value['pourcentage'];
		$this->virement = $value['virement'];
		
		$sql = "SELECT id_jour, nom, heure_debut, minute_debut, heure_fin, minute_fin 
		FROM restaurant_horaires
		JOIN days ON days.id = id_jour
		WHERE id_restaurant = :id
		ORDER BY id_jour, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$horaires = $stmt->fetchAll();
		foreach ($horaires as $hor) {
			$horaire = new Model_Horaire();
			$horaire->id_jour = $hor["id_jour"];
			$horaire->name = $hor["nom"];
			$horaire->heure_debut = $hor["heure_debut"];
			$horaire->minute_debut = $hor["minute_debut"];
			$horaire->heure_fin = $hor["heure_fin"];
			$horaire->minute_fin = $hor["minute_fin"];
			$this->addHoraire($horaire);
		}
		return $this;
	}
	
	public function getAll () {
		$sql = "SELECT id, nom, rue, code_postal, ville, latitude, longitude, enabled FROM restaurants WHERE deleted = 0 Order by ville, nom";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $key => $value) {
			$restaurant = new Model_Restaurant(false);
			$restaurant->id = $value['id'];
			$restaurant->nom = $value['nom'];
			$restaurant->rue = $value['rue'];
			$restaurant->code_postal = $value['code_postal'];
			$restaurant->ville = $value['ville'];
			$restaurant->latitude = $value['latitude'];
			$restaurant->longitude = $value['longitude'];
			$restaurant->is_enable = $value['enabled'];
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function getAllRestaurantEnable () {
		$sql = "SELECT id, nom, rue, code_postal, ville, latitude, longitude, enabled FROM restaurants WHERE enabled = true Order by ville, nom";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $key => $value) {
			$restaurant = new Model_Restaurant(false);
			$restaurant->id = $value['id'];
			$restaurant->nom = $value['nom'];
			$restaurant->rue = $value['rue'];
			$restaurant->code_postal = $value['code_postal'];
			$restaurant->ville = $value['ville'];
			$restaurant->latitude = $value['latitude'];
			$restaurant->longitude = $value['longitude'];
			$restaurant->is_enable = $value['enabled'];
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function enable () {
		$sql = "UPDATE restaurants SET enabled = true WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function disable () {
		$sql = "UPDATE restaurants SET enabled = false WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function deleted () {
		$sql = "UPDATE restaurants SET deleted = true WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function getPrixLivraison () {
		$sql = "SELECT prix FROM prix_livraison WHERE :distance BETWEEN distance_min AND distance_max";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":distance", $this->distance);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$prixLivraison = $value['prix'];
		return $prixLivraison;
	}
	
	public function filter ($filters) {
		$sql = "SELECT r.id, r.nom, r.rue, r.code_postal, r.ville, r.short_desc, r.latitude, r.longitude, rh.id_jour, rh.heure_debut, rh.minute_debut, 
		rh.heure_fin, rh.minute_fin
		FROM restaurants r 
		LEFT JOIN restaurant_horaires rh ON rh.id_restaurant = r.id";
		if (isset($filters['search_date'])) {
			$sql .= " AND rh.id_jour = WEEKDAY('".$filters['search_date']."')+1";
		} else {
			$sql .= " AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1";
		}
		if (isset($filters['search_hour']) && isset($filters['search_minute'])) {
			$sql .= ' AND (rh.heure_debut >= '.$filters['search_hour'].' OR (rh.heure_debut < '.$filters['search_hour'].' AND rh.heure_fin >= '.$filters['search_hour'].'))';
		} else {
			$sql .= " AND (rh.heure_debut >= HOUR(CURRENT_TIME) OR (rh.heure_debut < HOUR(CURRENT_TIME) AND rh.heure_fin >= HOUR(CURRENT_TIME)))";
		}
		if (isset($filters['tagsFilter']) && count($filters['tagsFilter']) > 0) {
			$sql .= ' JOIN restaurant_tag rt ON rt.id_restaurant = r.id AND rt.id_tag IN ('.implode(',', $filters['tagsFilter']).')';
		}
		$sql .= ' WHERE r.enabled = 1'; 
		$link = "AND";
		foreach ($filters as $key => $filter) {
			if ($key == "distanceKm" || $key == "search_adresse" || $key == "tags" || $key == "tagsFilter" || $key == "search_date" 
			|| $key == "search_hour" || $key == "search_minute") continue;
			$sql .= " ".$link." $key = :$key";
		}
		$sql .= " GROUP BY r.id Order by r.score, r.nom";
		$stmt = $this->db->prepare($sql);
		foreach ($filters as $key => $filter) {
			if ($key == "distanceKm" || $key == "search_adresse" || $key == "tags" || $key == "tagsFilter") continue;
			$stmt->bindValue(":$key", $filter);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $key => $value) {
			$restaurant = new Model_Restaurant(false);
			$restaurant->id = $value['id'];
			$restaurant->nom = $value['nom'];
			$restaurant->rue = $value['rue'];
			$restaurant->code_postal = $value['code_postal'];
			$restaurant->ville = $value['ville'];
			$restaurant->short_desc = $value['short_desc'];
			$restaurant->latitude = $value['latitude'];
			$restaurant->longitude = $value['longitude'];
			$horaire = new Model_Horaire(false);
			$horaire->id_jour = $value['id_jour'];
			$horaire->heure_debut = $value['heure_debut'];
			$horaire->minute_debut = $value['minute_debut'];
			$horaire->heure_fin = $value['heure_fin'];
			$horaire->minute_fin = $value['minute_fin'];
			$restaurant->horaire = $horaire;
			
			$sql = "SELECT certif.id, certif.nom, certif.description AS description_certif, certif.logo, rc.url, rc.description AS description
			FROM certificats certif
			JOIN restaurant_certificat rc ON rc.id_certificat = certif.id
			WHERE rc.id_restaurant = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $restaurant->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$certificats = $stmt->fetchAll();
			foreach ($certificats as $certif) {
				$certificat = new Model_Certificat();
				$certificat->id = $certif["id"];
				$certificat->nom = $certif["nom"];
				if ($certif["description"] != '') {
					$certificat->description = $certif["description"];
				} else {
					$certificat->description = $certif["description_certif"];
				}
				$certificat->logo = $certif["logo"];
				$certificat->url = $certif["url"];
				$restaurant->addCertificat($certificat);
			}
			
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function loadAll () {
		$sql = "SELECT r.id, r.nom, r.rue, r.code_postal, r.ville, r.short_desc, r.long_desc, r.latitude, r.longitude, rh.id_jour, rh.heure_debut, rh.minute_debut, 
		rh.heure_fin, rh.minute_fin,
		(SELECT (SUM(note) / COUNT(*)) FROM commentaire_restaurant WHERE id_restaurant = :id) AS note,
		(SELECT COUNT(*) FROM commentaire_restaurant WHERE id_restaurant = :id) AS nb_note,
		(SELECT COUNT(*) FROM commentaire_restaurant WHERE id_restaurant = :id AND validation = 1) AS nb_commentaire
		FROM restaurants r 
		LEFT JOIN restaurant_horaires rh ON rh.id_restaurant = r.id AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR ((rh.heure_debut < HOUR(CURRENT_TIME) OR (rh.heure_debut = HOUR(CURRENT_TIME) AND rh.minute_debut <= MINUTE(CURRENT_TIME))) 
		AND (rh.heure_fin > HOUR(CURRENT_TIME) OR (rh.heure_fin = HOUR(CURRENT_TIME) AND rh.minute_fin > MINUTE(CURRENT_TIME)))))
		WHERE r.id = :id
		ORDER BY rh.heure_debut ASC
		LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		$this->nom = $value['nom'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->short_desc = $value['short_desc'];
		$this->long_desc = $value['long_desc'];
		$this->latitude = $value['latitude'];
		$this->longitude = $value['longitude'];
		$this->note = $value['note'];
		$this->nb_note = $value['nb_note'];
		$this->commentaire = $value['nb_commentaire'];
		
		$horaire = new Model_Horaire(false);
		$horaire->id_jour = $value['id_jour'];
		$horaire->heure_debut = $value['heure_debut'];
		$horaire->minute_debut = $value['minute_debut'];
		$horaire->heure_fin = $value['heure_fin'];
		$horaire->minute_fin = $value['minute_fin'];
		
		$this->horaire = $horaire;
		
		$sql = "SELECT id_jour, nom, heure_debut, minute_debut, heure_fin, minute_fin 
		FROM restaurant_horaires
		JOIN days ON days.id = id_jour
		WHERE id_restaurant = :id AND id_jour = WEEKDAY(CURRENT_DATE)+1
		ORDER BY id_jour, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$horaires = $stmt->fetchAll();
		foreach ($horaires as $hor) {
			$horaire = new Model_Horaire();
			$horaire->id_jour = $hor["id_jour"];
			$horaire->name = $hor["nom"];
			$horaire->heure_debut = $hor["heure_debut"];
			$horaire->minute_debut = $hor["minute_debut"];
			$horaire->heure_fin = $hor["heure_fin"];
			$horaire->minute_fin = $hor["minute_fin"];
			$this->addHoraire($horaire);
		}
		
		$sql = "SELECT certif.id, certif.nom, certif.description AS description_certif, certif.logo, rc.url, rc.description AS description
		FROM certificats certif
		JOIN restaurant_certificat rc ON rc.id_certificat = certif.id
		WHERE rc.id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$certificats = $stmt->fetchAll();
		foreach ($certificats as $certif) {
			$certificat = new Model_Certificat();
			$certificat->id = $certif["id"];
			$certificat->nom = $certif["nom"];
			if ($certif["description"] != '') {
				$certificat->description = $certif["description"];
			} else {
				$certificat->description = $certif["description_certif"];
			}
			$certificat->logo = $certif["logo"];
			$certificat->url = $certif["url"];
			$this->certificats[] = $certificat;
		}
		
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id_restaurant = :id AND parent_categorie = 0 ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$categories = $stmt->fetchAll();
		foreach ($categories as $c) {
			$categorie = new Model_Categorie();
			$categorie->id = $c["id"];
			$categorie->nom = $c["nom"];
						
			$childrens = $categorie->getChildren();
			if (count($childrens) > 0) {
				foreach ($childrens as $children) {
					$children->loadContenu($this->id);
					$children->parent_categorie = $categorie;
					$this->categories[] = $children;
				}
			} else {
				$categorie->loadContenu($this->id);
				$this->categories[] = $categorie;
			}
		}
		$sql = "SELECT id, nom, commentaire, (SELECT MIN(mf.prix) FROM menu_format mf WHERE mf.id_menu = menus.id) AS prix 
		FROM menus WHERE id_restaurant = :id ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$menus = $stmt->fetchAll();
		foreach ($menus as $m) {
			$menu = new Model_Menu();
			$menu->id = $m["id"];
			$menu->nom = $m["nom"];
			$menu->prix = $m["prix"];
			$menu->commentaire = $m["commentaire"];
			$menu->getLogo($this->id);
			$this->menus[] = $menu;
		}
		return $this;
	}
	
	public function load () {
		$sql = "SELECT r.id, r.nom, r.rue, r.code_postal, r.ville, r.short_desc, r.long_desc, r.latitude, r.longitude, rh.id_jour, rh.heure_debut, rh.minute_debut, 
		rh.heure_fin, rh.minute_fin
		FROM restaurants r 
		LEFT JOIN restaurant_horaires rh ON rh.id_restaurant = r.id AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR ((rh.heure_debut < HOUR(CURRENT_TIME) OR (rh.heure_debut = HOUR(CURRENT_TIME) AND rh.minute_debut <= MINUTE(CURRENT_TIME))) 
		AND (rh.heure_fin > HOUR(CURRENT_TIME) OR (rh.heure_fin = HOUR(CURRENT_TIME) AND rh.minute_fin > MINUTE(CURRENT_TIME)))))
		WHERE r.id = :id
		GROUP BY r.id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		$this->nom = $value['nom'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->short_desc = $value['short_desc'];
		$this->long_desc = $value['long_desc'];
		$this->latitude = $value['latitude'];
		$this->longitude = $value['longitude'];
		$horaire = new Model_Horaire(false);
		$horaire->id_jour = $value['id_jour'];
		$horaire->heure_debut = $value['heure_debut'];
		$horaire->minute_debut = $value['minute_debut'];
		$horaire->heure_fin = $value['heure_fin'];
		$horaire->minute_fin = $value['minute_fin'];
		$this->horaire = $horaire;
		return $this;
	}
	
	public function loadByUser ($uid) {
		$sql = "SELECT user.nom AS user_nom, user.prenom AS user_prenom, user.email AS user_email, resto.id AS id_resto, resto.nom AS resto_nom, 
		resto.rue AS resto_rue, resto.ville AS resto_ville, resto.code_postal AS resto_cp, resto.telephone AS resto_tel, resto.short_desc, 
		resto.long_desc, resto.latitude, resto.longitude, resto.pourcentage, resto.virement
		FROM users user 
		JOIN user_restaurant ur ON ur.uid = user.uid
		JOIN restaurants resto ON resto.id = ur.id_restaurant
		WHERE user.uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_WARNING, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->id = $value['id_resto'];
		$this->nom = $value['resto_nom'];
		$this->rue = $value['resto_rue'];
		$this->ville = $value['resto_ville'];
		$this->code_postal = $value['resto_cp'];
		$this->telephone = $value['resto_tel'];
		$this->short_desc = $value['short_desc'];
		$this->long_desc = $value['long_desc'];
		$this->latitude = $value['latitude'];
		$this->longitude = $value['longitude'];
		$this->pourcentage = $value['pourcentage'];
		$this->virement = $value['virement'];
		$user = new Model_User();
		$user->uid = $uid;
		$user->nom = $value['user_nom'];
		$user->prenom = $value['user_prenom'];
		$user->login = $value['user_email'];
		$this->user = $user;
		
		$sql = "SELECT id_jour, nom, heure_debut, minute_debut, heure_fin, minute_fin 
		FROM restaurant_horaires
		JOIN days ON days.id = id_jour
		WHERE id_restaurant = :id
		ORDER BY id_jour, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$horaires = $stmt->fetchAll();
		foreach ($horaires as $hor) {
			$horaire = new Model_Horaire();
			$horaire->id_jour = $hor["id_jour"];
			$horaire->name = $hor["nom"];
			$horaire->heure_debut = $hor["heure_debut"];
			$horaire->minute_debut = $hor["minute_debut"];
			$horaire->heure_fin = $hor["heure_fin"];
			$horaire->minute_fin = $hor["minute_fin"];
			$this->addHoraire($horaire);
		}
		return $this;
	}
	
	public function loadCategories () {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id_restaurant = :id AND parent_categorie = 0 AND deleted = 0 ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$categories = $stmt->fetchAll();
		foreach ($categories as $c) {
			$categorie = new Model_Categorie();
			$categorie->id = $c["id"];
			$categorie->nom = $c["nom"];
			$this->categories[] = $categorie;
		}
		return $this;
	}
	
	public function loadCarte () {
		$sql = "SELECT nom FROM restaurants WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		
		$sql = "SELECT carte.id AS id_carte, carte.nom AS carte, rc.id AS id_categorie, rc.nom AS categorie
		FROM carte
		JOIN restaurant_categorie rc ON rc.id = carte.id_categorie
		WHERE rc.id_restaurant = :id
		AND carte.is_visible = 1";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$cartes = $stmt->fetchAll();
		foreach ($cartes as $c) {
			$categorie = $c["categorie"];
			if (!isset($this->carte[$categorie])) {
				$this->carte[$categorie] = array();
				/*$this->carte[$categorie]["id"] = $c["id_categorie"];*/
				//$this->carte[$categorie]["carte"] = array();
			}
			$carte = new Model_Carte();
			$carte->id = $c["id_carte"];
			$carte->nom = $c["carte"];
			$this->carte[$categorie][] = $carte;
		}
		return $this;
	}
	
	public function loadAllContenu () {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id_restaurant = :id ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$categories = $stmt->fetchAll();
		foreach ($categories as $c) {
			$categorie = new Model_Categorie();
			$categorie->id = $c["id"];
			$categorie->nom = $c["nom"];
			$categorie->getContenu($this->id);
			$this->categories[] = $categorie;
		}
		return $this;
	}
	
	public function loadMenus () {
		$sql = "SELECT id, nom FROM menus WHERE id_restaurant = :id AND deleted = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$menus = $stmt->fetchAll();
		foreach ($menus as $m) {
			$menu = new Model_Menu();
			$menu->id = $m["id"];
			$menu->nom = $m["nom"];
			$menu->getLogo($this->id);
			$this->menus[] = $menu;
		}
		return $this;
	}
	
	public function loadFormat () {
		$sql = "SELECT id, nom FROM restaurant_format WHERE id_restaurant = :id AND deleted = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$formats = $stmt->fetchAll();
		foreach ($formats as $f) {
			$format = new Model_Format();
			$format->id = $f["id"];
			$format->nom = $f["nom"];
			$this->formats[] = $format;
		}
		return $this;
	}
	
	public function loadFormule () {
		$sql = "SELECT id, nom FROM restaurant_formule WHERE id_restaurant = :id AND deleted = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$formules = $stmt->fetchAll();
		foreach ($formules as $f) {
			$formule = new Model_Formule();
			$formule->id = $f["id"];
			$formule->nom = $f["nom"];
			$this->formules[] = $formule;
		}
		return $this;
	}
	
	public function loadAccompagnements () {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$categories = $stmt->fetchAll();
		foreach ($categories as $cat) {
			$categorie = new Model_Categorie();
			$categorie->id = $cat["id"];
			$categorie->nom = $cat["nom"];
			
			$sql = "SELECT id, nom FROM carte WHERE id_categorie = :id_categorie";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_categorie", $categorie->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$contenus = $stmt->fetchAll();
			foreach ($contenus as $cont) {
				$contenu = new Model_Contenu();
				$contenu->id = $cont['id'];
				$contenu->nom = $cont['nom'];
				
				$categorie->addContenu($contenu);
			}
			$this->categories[] = $categorie;
		}
		return $this;
	}
	
	public function loadSupplements () {
		$sql = "SELECT id, nom, prix, commentaire FROM supplements WHERE id_restaurant = :id AND deleted = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$supplements = $stmt->fetchAll();
		foreach ($supplements as $sup) {
			$supplement = new Model_Supplement();
			$supplement->id = $sup["id"];
			$supplement->nom = $sup["nom"];
			$supplement->prix = $sup["prix"];
			$supplement->commentaire = $sup["commentaire"];
			$this->supplements[] = $supplement;
		}
		return $this;
	}
	
	public function loadHoraires () {
		$sql = "SELECT rh.id, rh.id_jour, days.nom, rh.heure_debut, rh.minute_debut, rh.heure_fin, rh.minute_fin 
		FROM restaurant_horaires rh
		JOIN days ON days.id = rh.id_jour
		WHERE rh.id_restaurant = :id
		ORDER BY rh.id_jour, rh.heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$horaires = $stmt->fetchAll();
		foreach ($horaires as $hor) {
			$horaire = new Model_Horaire();
			$horaire->id = $hor["id"];
			$horaire->id_jour = $hor["id_jour"];
			$horaire->name = $hor["nom"];
			$horaire->heure_debut = $hor["heure_debut"];
			$horaire->minute_debut = $hor["minute_debut"];
			$horaire->heure_fin = $hor["heure_fin"];
			$horaire->minute_fin = $hor["minute_fin"];
			$this->addHoraire($horaire);
		}
		return $this;
	}
	
	public function loadTags () {
		$sql = "SELECT tag.id, tag.nom FROM tags tag JOIN restaurant_tag rt ON rt.id_tag = tag.id WHERE rt.id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$tags = $stmt->fetchAll();
		foreach ($tags as $t) {
			$tag = new Model_Tag();
			$tag->id = $t["id"];
			$tag->nom = $t["nom"];
			$this->tags[] = $tag;
		}
		return $this;
	}
	
	public function filterTags ($filtre, $restaurants) {
		$sql = "SELECT tag.id, tag.nom FROM tags tag 
		JOIN restaurant_tag rt ON rt.id_tag = tag.id 
		WHERE nom LIKE '%".$filtre."%' AND rt.id_restaurant IN (".implode(',', $restaurants).")
		GROUP BY tag.id";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$list = array();
		$tags = $stmt->fetchAll();
		foreach ($tags as $value) {
			$tag = new Model_Tag();
			$tag->id = $value["id"];
			$tag->nom = $value["nom"];
			$list[] = $tag;
		}
		return $list;
	}
	
	public function filterRestaurant ($filtre, $restaurants) {
		$sql = "SELECT id, nom FROM restaurants WHERE nom LIKE '%".$filtre."%' AND id IN (".implode(',', $restaurants).")";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$list = array();
		$tags = $stmt->fetchAll();
		foreach ($tags as $value) {
			$tag = new Model_Tag();
			$tag->id = $value["id"];
			$tag->nom = $value["nom"];
			$list[] = $tag;
		}
		return $list;
	}
	
	public function loadOptions () {
		$sql = "SELECT opt.id, opt.nom FROM restaurant_option opt WHERE opt.id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$options = $stmt->fetchAll();
		foreach ($options as $opt) {
			$option = new Model_Option();
			$option->id = $opt["id"];
			$option->nom = $opt["nom"];
			$this->options[] = $option;
		}
		return $this;
	}
	
	public function getById ($id) {
		$sql = "SELECT id, url_logo, nom, rue, ville, code_postal, telephone FROM restaurant WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getTopRestaurant () {
		$sql = "SELECT id, nom, ville, short_desc FROM restaurants WHERE is_top = true";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $key => $value) {
			$restaurant = new Model_Restaurant(false);
			$restaurant->id = $value['id'];
			$restaurant->nom = $value['nom'];
			$restaurant->ville = $value['ville'];
			$restaurant->short_desc = $value['short_desc'];
			$restaurant->getLogo();
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function getTags () {
		$sql = "SELECT id, nom FROM tags";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$tags = $stmt->fetchAll();
		$list = array();
		foreach ($tags as $key => $value) {
			$tag = new Model_Tag();
			$tag->id = $value['id'];
			$tag->nom = $value['nom'];
			$list[] = $tag;
		}
		return $list;
	}
	
	public function getRestaurantsCalculeDistance () {
		$sql = "SELECT resto.id, resto.latitude, resto.longitude 
		FROM restaurants resto 
		JOIN update_distance_restaurant udr ON udr.id_restaurant = resto.id";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $resto) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $resto['id'];
			$restaurant->latitude = $resto['latitude'];
			$restaurant->longitude = $resto['longitude'];
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function hasMenu () {
		$sql = "SELECT COUNT(*) AS nb_menu FROM menus WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		return $value !== false && $value['nb_menu'] > 0;
	}
	
	public function getAllCommentaire () {
		$sql = "SELECT resto.id AS id_restaurant, resto.nom, id_user, nom_user, prenom_user, com.id AS id_commentaire, com.note, com.commentaire, com.validation
		FROM restaurants resto
		JOIN commande_history history ON history.id_restaurant = resto.id
		JOIN commentaire_restaurant com ON com.id_restaurant = resto.id AND com.uid = history.id_user
		WHERE resto.enabled = 1";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $resto) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $resto['id_restaurant'];
			$restaurant->nom = $resto['nom'];
			
			$client = new Model_User();
			$client->id = $resto["id_user"];
			$client->nom = $resto["nom_user"];
			$client->prenom = $resto["prenom_user"];
			
			$restaurant->user = $client;
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $resto['id_commentaire'];
			$commentaire->note = $resto['note'];
			$commentaire->commentaire = $resto['commentaire'];
			$commentaire->validation = $resto['validation'];
			
			$restaurant->commentaire = $commentaire;
			
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function getCommentaireRestaurant () {
		$sql = "SELECT user.uid AS id_user, user.nom AS nom_user, user.prenom AS prenom_user, com.id AS id_commentaire, com.note, com.commentaire, com.commentaire_anonyme,
		com.date_commentaire
		FROM commentaire_restaurant com
		JOIN users user ON user.uid = com.uid
		WHERE com.validation = 1 AND com.id_restaurant = :id
		ORDER BY com.date_commentaire DESC";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$commentaires = $stmt->fetchAll();
		$list = array();
		foreach ($commentaires as $com) {
			
			$user = new Model_User();
			$user->id = $com["id_user"];
			$user->nom = $com["nom_user"];
			$user->prenom = $com["prenom_user"];
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $com['id_commentaire'];
			$commentaire->note = $com['note'];
			$commentaire->commentaire = $com['commentaire'];
			$commentaire->anonyme = $com['commentaire_anonyme'];
			$commentaire->date = $com['date_commentaire'];
			
			$commentaire->user = $user;
			
			$list[] = $commentaire;
		}
		return $list;
	}
	
	public function disableCommentaire () {
		$sql = "UPDATE commentaire_restaurant SET validation = 0 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function enableCommentaire () {
		$sql = "UPDATE commentaire_restaurant SET validation = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getAllCommentairePlats () {
		$sql = "SELECT resto.id AS id_restaurant, resto.nom AS restaurant, id_user, nom_user, prenom_user, carte.id AS id_carte, carte.nom AS carte, 
		cch.id_categorie, cch.nom_categorie, com.id AS id_commentaire, com.note, com.commentaire, com.validation
		FROM restaurants resto
		JOIN commande_history history ON history.id_restaurant = resto.id
		JOIN commande_carte_history cch ON cch.id_commande = history.id
		JOIN carte ON carte.id = cch.id_carte
		JOIN commentaire_carte com ON com.id_carte = carte.id AND com.uid = history.id_user
		WHERE resto.enabled = 1 AND carte.is_visible = 1
		GROUP BY id_carte
		ORDER BY restaurant, nom_categorie";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $resto) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $resto['id_restaurant'];
			$restaurant->nom = $resto['restaurant'];
			
			$client = new Model_User();
			$client->id = $resto["id_user"];
			$client->nom = $resto["nom_user"];
			$client->prenom = $resto["prenom_user"];
			
			$restaurant->user = $client;
			
			$categorie = new Model_Categorie();
			$categorie->id = $resto["id_categorie"];
			$categorie->nom = $resto["nom_categorie"];
			
			$contenu = new Model_Contenu();
			$contenu->id = $resto["id_carte"];
			$contenu->nom = $resto["carte"];
			$contenu->getLogo($restaurant->id);
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $resto['id_commentaire'];
			$commentaire->note = $resto['note'];
			$commentaire->commentaire = $resto['commentaire'];
			$commentaire->validation = $resto['validation'];
			
			$contenu->commentaire = $commentaire;
			
			$categorie->addContenu($contenu);
			
			$restaurant->addCategorie($categorie);
			
			$list[] = $restaurant;
		}
		$sql = "SELECT resto.id AS id_restaurant, resto.nom AS restaurant, id_user, nom_user, prenom_user, menus.id AS id_menu, menus.nom AS menu, 
		com.id AS id_commentaire, com.note, com.commentaire, com.validation
		FROM restaurants resto
		JOIN commande_history history ON history.id_restaurant = resto.id
		JOIN commande_menu_history cmh ON cmh.id_commande = history.id
		JOIN menus ON menus.id = cmh.id_menu
		JOIN commentaire_menu com ON com.id_menu = menus.id AND com.uid = history.id_user
		WHERE resto.enabled = 1
		GROUP BY id_menu
		ORDER BY restaurant";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		foreach ($restaurants as $resto) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $resto['id_restaurant'];
			$restaurant->nom = $resto['restaurant'];
			
			$client = new Model_User();
			$client->id = $resto["id_user"];
			$client->nom = $resto["nom_user"];
			$client->prenom = $resto["prenom_user"];
			
			$restaurant->user = $client;
			
			$menu = new Model_Menu();
			$menu->id = $resto["id_menu"];
			$menu->nom = $resto["menu"];
			$menu->getLogo($restaurant->id);
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $resto['id_commentaire'];
			$commentaire->note = $resto['note'];
			$commentaire->commentaire = $resto['commentaire'];
			$commentaire->validation = $resto['validation'];
			
			$menu->commentaire = $commentaire;
			
			$restaurant->addMenu($menu);
			
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function getCommentaireCarte () {
		$sql = "SELECT user.uid AS id_user, user.nom AS nom_user, user.prenom AS prenom_user, com.id AS id_commentaire, com.note, com.commentaire, com.commentaire_anonyme,
		com.date_commentaire
		FROM commentaire_carte com
		JOIN users user ON user.uid = com.uid
		WHERE com.validation = 1 AND com.id_carte = :id
		ORDER BY com.date_commentaire DESC";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$commentaires = $stmt->fetchAll();
		$list = array();
		foreach ($commentaires as $com) {
			
			$user = new Model_User();
			$user->id = $com["id_user"];
			$user->nom = $com["nom_user"];
			$user->prenom = $com["prenom_user"];
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $com['id_commentaire'];
			$commentaire->note = $com['note'];
			$commentaire->commentaire = $com['commentaire'];
			$commentaire->anonyme = $com['commentaire_anonyme'];
			$commentaire->date = $com['date_commentaire'];
			
			$commentaire->user = $user;
			
			$list[] = $commentaire;
		}
		return $list;
	}
	
	public function disableCommentaireCarte () {
		$sql = "UPDATE commentaire_carte SET validation = 0 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function enableCommentaireCarte () {
		$sql = "UPDATE commentaire_carte SET validation = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function disableCommentaireMenu () {
		$sql = "UPDATE commentaire_menu SET validation = 0 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function enableCommentaireMenu () {
		$sql = "UPDATE commentaire_menu SET validation = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getCommentaireByUser () {
		$sql = "SELECT resto.id AS id_restaurant, resto.nom, resto.rue, resto.ville, resto.code_postal, 
		com.id AS id_commentaire, com.note
		FROM restaurants resto
		JOIN commande_history history ON history.id_restaurant = resto.id AND history.id_user = :uid
		LEFT JOIN commentaire_restaurant com ON com.id_restaurant = resto.id AND com.uid = :uid
		WHERE resto.enabled = 1
		GROUP BY id_restaurant";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->user->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $resto) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $resto['id_restaurant'];
			$restaurant->nom = $resto['nom'];
			$restaurant->rue = $resto['rue'];
			$restaurant->ville = $resto['ville'];
			$restaurant->code_postal = $resto['code_postal'];
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $resto['id_commentaire'];
			$commentaire->note = $resto['note'];
			
			$restaurant->commentaire = $commentaire;
			
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function noter () {
		$sql = "INSERT INTO commentaire_restaurant (uid, id_restaurant, note, commentaire, commentaire_anonyme, date_commentaire)
		VALUES (:uid, :id, :note, :commentaire, :anonyme, now())";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->user->id);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":note", $this->commentaire->note);
		$stmt->bindValue(":commentaire", $this->commentaire->commentaire);
		$stmt->bindValue(":anonyme", $this->commentaire->anonyme);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getCommentaireCarteByUser () {
		$sql = "SELECT resto.id AS id_restaurant, resto.nom AS restaurant, carte.id AS id_carte, carte.nom AS carte, cch.id_categorie, cch.nom_categorie, 
		com.id AS id_commentaire, com.note
		FROM restaurants resto
		JOIN commande_history history ON history.id_restaurant = resto.id AND history.id_user = :uid
		JOIN commande_carte_history cch ON cch.id_commande = history.id
		JOIN carte ON carte.id = cch.id_carte
		LEFT JOIN commentaire_carte com ON com.id_carte = carte.id AND com.uid = :uid
		WHERE resto.enabled = 1 AND carte.is_visible = 1
		GROUP BY id_carte
		ORDER BY restaurant, nom_categorie";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->user->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $resto) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $resto['id_restaurant'];
			$restaurant->nom = $resto['restaurant'];
			
			$categorie = new Model_Categorie();
			$categorie->id = $resto["id_categorie"];
			$categorie->nom = $resto["nom_categorie"];
			
			$contenu = new Model_Contenu();
			$contenu->id = $resto["id_carte"];
			$contenu->nom = $resto["carte"];
			$contenu->getLogo($restaurant->id);
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $resto['id_commentaire'];
			$commentaire->note = $resto['note'];
			
			$contenu->commentaire = $commentaire;
			
			$categorie->addContenu($contenu);
			
			$restaurant->addCategorie($categorie);
			
			$list[] = $restaurant;
		}
		$sql = "SELECT resto.id AS id_restaurant, resto.nom AS restaurant, menus.id AS id_menu, menus.nom AS menu, com.id AS id_commentaire, com.note
		FROM restaurants resto
		JOIN commande_history history ON history.id_restaurant = resto.id AND history.id_user = :uid
		JOIN commande_menu_history cmh ON cmh.id_commande = history.id
		JOIN menus ON menus.id = cmh.id_menu
		LEFT JOIN commentaire_menu com ON com.id_menu = menus.id AND com.uid = :uid
		WHERE resto.enabled = 1
		GROUP BY id_menu
		ORDER BY restaurant";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->user->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		foreach ($restaurants as $resto) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $resto['id_restaurant'];
			$restaurant->nom = $resto['restaurant'];
			
			$menu = new Model_Menu();
			$menu->id = $resto["id_menu"];
			$menu->nom = $resto["menu"];
			$menu->getLogo($restaurant->id);
			
			$commentaire = new Model_Commentaire();
			$commentaire->id = $resto['id_commentaire'];
			$commentaire->note = $resto['note'];
			
			$menu->commentaire = $commentaire;
			
			$restaurant->addMenu($menu);
			
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function noterCarte () {
		$sql = "INSERT INTO commentaire_carte (uid, id_carte, note, commentaire, commentaire_anonyme, date_commentaire)
		VALUES (:uid, :id, :note, :commentaire, :anonyme, now())";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->user->id);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":note", $this->commentaire->note);
		$stmt->bindValue(":commentaire", $this->commentaire->commentaire);
		$stmt->bindValue(":anonyme", $this->commentaire->anonyme);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function noterMenu () {
		$sql = "INSERT INTO commentaire_menu (uid, id_menu, note, commentaire, commentaire_anonyme, date_commentaire)
		VALUES (:uid, :id, :note, :commentaire, :anonyme, now())";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->user->id);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":note", $this->commentaire->note);
		$stmt->bindValue(":commentaire", $this->commentaire->commentaire);
		$stmt->bindValue(":anonyme", $this->commentaire->anonyme);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function isOpen () {
		return true;
	}
	
	public function getOpenHour () {
		return $this->horaires[0];
	}
	
	public function nextOpenHour () {
		return $this->horaires[0];
	}
	
	private function getLogo () {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$this->id)) {
			if (file_exists($logoDirectory.$this->id.'/logo.png')) {
				$this->logo = $imgPath.$this->id.'/logo.png';
			} else if (file_exists($logoDirectory.$this->id.'/logo.jpg')) {
				$this->logo = $imgPath.$this->id.'/logo.jpg';
			} else if (file_exists($logoDirectory.$this->id.'/logo.gif')) {
				$this->logo = $imgPath.$this->id.'/logo.gif';
			} else {
				$this->logo = $imgPath.'default/logo.jpg';
			}
		} else {
			$this->logo = $imgPath.'default/logo.jpg';
		}
	}
	
	private function getCarteImg () {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$this->id)) {
			if (file_exists($logoDirectory.$this->id.'/logo.png')) {
				$this->logo = $imgPath.$this->id.'/logo.png';
			} else if (file_exists($logoDirectory.$this->id.'/logo.jpg')) {
				$this->logo = $imgPath.$this->id.'/logo.jpg';
			} else if (file_exists($logoDirectory.$this->id.'/logo.gif')) {
				$this->logo = $imgPath.$this->id.'/logo.gif';
			} else {
				$this->logo = $imgPath.'default/logo.jpg';
			}
		} else {
			$this->logo = $imgPath.'default/logo.jpg';
		}
	}
	
	public function getMenuImg () {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$this->id)) {
			if (file_exists($logoDirectory.$this->id.'/menu.png')) {
				return $imgPath.$this->id.'/menu.png';
			} else if (file_exists($logoDirectory.$this->id.'/menu.jpg')) {
				return $imgPath.$this->id.'/menu.jpg';
			} else if (file_exists($logoDirectory.$this->id.'/menu.gif')) {
				return $imgPath.$this->id.'/menu.gif';
			} else {
				return $imgPath.'default/menu.jpg';
			}
		} else {
			return $imgPath.'default/menu.jpg';
		}
	}
	
	
}