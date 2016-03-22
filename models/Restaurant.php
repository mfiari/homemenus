<?php

class Model_Restaurant extends Model_Template {
	
	private $id;
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
	private $distance;
	private $horaires;
	private $horaire;
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
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->id = -1;
		$this->horaires = array();
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
	
	public function save () {
		if ($this->id == -1) {
			$this->insert();
		}
	}
	
	public function insert () {
		$sql = "INSERT INTO restaurants (nom, rue, ville, code_postal, telephone, latitude, longitude, short_desc, long_desc, score)
		VALUES (:nom, :rue, :ville, :cp, :tel, :lat, :lon, :short, :long, :score)";
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
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
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
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		$sql = "INSERT INTO restaurant_format (id_restaurant, nom) VALUES (:id, :nom)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":nom", '');
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
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
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		$this->nom = $value['nom'];
		return $this;
	}
	
	public function getOne () {
		$sql = "SELECT nom, rue, ville, code_postal, telephone, short_desc, long_desc FROM restaurants WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
		
		$sql = "SELECT id_jour, nom, heure_debut, minute_debut, heure_fin, minute_fin 
		FROM restaurant_horaires
		JOIN days ON days.id = id_jour
		WHERE id_restaurant = :id
		ORDER BY id_jour, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
		$sql = "SELECT id, nom, rue, code_postal, ville, latitude, longitude FROM restaurants Order by ville, nom";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
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
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function filter ($filters) {
		$sql = "SELECT r.id, r.nom, r.rue, r.code_postal, r.ville, r.short_desc, r.latitude, r.longitude, rh.id_jour, rh.heure_debut, rh.minute_debut, rh.heure_fin, rh.minute_fin
		FROM restaurants r 
		JOIN restaurant_horaires rh ON rh.id_restaurant = r.id AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR (rh.heure_debut < HOUR(CURRENT_TIME) AND rh.heure_fin >= HOUR(CURRENT_TIME)))
		GROUP BY r.id";
		if (isset($filters['tagsFilter']) && count($filters['tagsFilter']) > 0) {
			$sql .= ' JOIN restaurant_tag rt ON rt.id_restaurant = r.id AND rt.id_tag IN ('.implode(',', $filters['tagsFilter']).')';
		}
		$link = "WHERE";
		foreach ($filters as $key => $filter) {
			if ($key == "distanceKm" || $key == "search_ardresse" || $key == "tags" || $key == "tagsFilter") continue;
			$sql .= " ".$link." $key = :$key";
			$link = "AND";
		}
		$sql .= " Order by r.score, r.nom";
		$stmt = $this->db->prepare($sql);
		foreach ($filters as $key => $filter) {
			if ($key == "distanceKm" || $key == "search_ardresse" || $key == "tags" || $key == "tagsFilter") continue;
			$stmt->bindValue(":$key", $filter);
		}
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
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
			$list[] = $restaurant;
		}
		return $list;
	}
	
	public function load () {
		$sql = "SELECT r.id, r.nom, r.rue, r.code_postal, r.ville, r.short_desc, r.latitude, r.longitude, rh.id_jour, rh.heure_debut, rh.minute_debut, rh.heure_fin, rh.minute_fin
		FROM restaurants r 
		JOIN restaurant_horaires rh ON rh.id_restaurant = r.id AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR ((rh.heure_debut < HOUR(CURRENT_TIME) OR (rh.heure_debut = HOUR(CURRENT_TIME) AND rh.minute_debut <= MINUTE(CURRENT_TIME))) 
		AND (rh.heure_fin > HOUR(CURRENT_TIME) OR (rh.heure_fin = HOUR(CURRENT_TIME) AND rh.minute_fin > MINUTE(CURRENT_TIME)))))
		WHERE r.id = :id
		GROUP BY r.id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $value['id'];
		$this->nom = $value['nom'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->short_desc = $value['short_desc'];
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
		resto.rue AS resto_rue, resto.ville AS resto_ville, resto.code_postal AS resto_cp, resto.telephone AS resto_tel
		FROM users user 
		JOIN user_restaurant ur ON ur.uid = user.uid
		JOIN restaurants resto ON resto.id = ur.id_restaurant
		WHERE user.uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, null, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_WARNING, null, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->id = $value['id_resto'];
		$this->nom = $value['resto_nom'];
		$this->rue = $value['resto_rue'];
		$this->ville = $value['resto_ville'];
		$this->code_postal = $value['resto_cp'];
		$this->telephone = $value['resto_tel'];
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
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id_restaurant = :id AND parent_categorie = 0 ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
		$sql = "SELECT id, nom FROM menus WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
		$sql = "SELECT id, nom FROM restaurant_format WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
		$sql = "SELECT id, nom FROM restaurant_formule WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
		$sql = "SELECT id, nom, prix, commentaire FROM supplement WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
			var_dump($stmt->errorInfo());
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
	
	public function loadOptions () {
		$sql = "SELECT opt.id, opt.nom FROM restaurant_option opt WHERE opt.id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
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
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getTopRestaurant () {
		$sql = "SELECT id, nom, ville, short_desc FROM restaurants WHERE is_top = true";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
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
			var_dump($stmt->errorInfo());
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