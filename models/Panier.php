<?php

class Model_Panier extends Model_Template {
	
	private $id;
	private $uid;
	private $adresse_ip;
	private $rue;
	private $ville;
	private $code_postal;
	private $latitude;
	private $longitude;
	private $telephone;
	private $distance;
	private $prix_livraison;
	private $prix_minimum;
	private $reduction_premium;
	private $heure_souhaite;
	private $minute_souhaite;
	private $restaurant;
	private $user;
	private $code_promo;
	private $id_restaurant;
	private $carteList;
	private $menuList;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->id = -1;
		$this->uid = -1;
		$this->id_restaurant = -1;
		$this->carteList = array();
		$this->menuList = array();
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
	
	public function init () {
		if ($this->uid == -1) {
			$sql = "SELECT id, id_restaurant FROM panier WHERE adresse_ip = :ip";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$sql = "SELECT id, id_restaurant FROM panier WHERE uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			$this->insert();
		} else {
			$this->id = $value['id'];
			$this->id_restaurant = $value['id_restaurant'];
		}
	}
	
	public function save () {
		if ($this->id == -1) {
			$this->insert();
		}
	}
	
	public function insert () {
		if ($this->uid == -1) {
			$sql = "INSERT INTO panier (adresse_ip) VALUES(:ip)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$sql = "INSERT INTO panier (uid) VALUES(:uid)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
	}
	
	public function update () {
		$sql = "UPDATE panier SET id_restaurant = :restaurant, rue = :rue, ville = :ville, code_postal = :code_postal, latitude = :latitude, 
		longitude = :longitude, telephone = :telephone, distance = :distance WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $this->id_restaurant);
		$stmt->bindValue(":rue", $this->rue);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":code_postal", $this->code_postal);
		$stmt->bindValue(":latitude", $this->latitude);
		$stmt->bindValue(":longitude", $this->longitude);
		$stmt->bindValue(":telephone", $this->telephone);
		$stmt->bindValue(":distance", $this->distance);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function associate ($user) {
		$sql = "UPDATE panier SET uid = :uid WHERE adresse_ip = :ip";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $user->id);
		$stmt->bindValue(":ip", $this->adresse_ip);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function setRestaurant ($id_restaurant) {
		$sql = "UPDATE panier SET id_restaurant = :restaurant WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $id_restaurant);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function setCodePromo ($id_code_promo) {
		$sql = "UPDATE panier SET id_code_promo = :code_promo WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":code_promo", $id_code_promo);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getNbArticle () {
		$total = 0;
		$sql = "SELECT SUM(pm.quantite) AS total FROM panier
		JOIN panier_menu pm ON pm.id_panier = panier.id";
		if ($this->uid == -1) {
			$sql .= " WHERE panier.adresse_ip = :ip";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$sql .= " WHERE panier.uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value != null) {
			$total += $value['total'];
		}
		$sql = "SELECT SUM(pc.quantite) AS total 
		FROM panier
		JOIN panier_carte pc ON pc.id_panier = panier.id";
		if ($this->uid == -1) {
			$sql .= " WHERE panier.adresse_ip = :ip";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$sql .= " WHERE panier.uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value != null) {
			$total += $value['total'];
		}
		return $total;
	}
	
	public function getRestaurant () {
		if ($this->uid == -1) {
			$sql = "SELECT id_restaurant FROM panier WHERE adresse_ip = :ip";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$sql = "SELECT id_restaurant FROM panier WHERE uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		return $value['id_restaurant'];
	}
	
	public function getAll () {
		$sql = "SELECT panier.id AS id, user.uid AS uid, user.nom AS nom, user.prenom AS prenom, resto.id AS id_restaurant, resto.nom AS nom_resto, adresse_ip 
		FROM panier
		LEFT JOIN users user ON user.uid = panier.uid
		JOIN restaurants resto ON resto.id = panier.id_restaurant";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$paniers = $stmt->fetchAll();
		$list = array();
		foreach ($paniers as $key => $value) {
			$panier = new Model_Panier(false);
			$panier->id = $value['id'];
			$panier->adresse_ip = $value['adresse_ip'];
		
			$user = new Model_User(false);
			$user->id = $value['uid'];
			$user->nom = $value['nom'];
			$user->prenom = $value['prenom'];
			
			$panier->user = $user;
		
			$restaurant = new Model_Restaurant(false);
			$restaurant->id = $value['id_restaurant'];
			$restaurant->nom = $value['nom_resto'];
			
			$panier->restaurant = $restaurant;
			$list[] = $panier;
		}
		return $list;
		
	}
	
	public function get () {
		$sql = "SELECT panier.id, panier.rue, panier.ville, panier.code_postal, panier.telephone, panier.heure_souhaite, panier.minute_souhaite,
			resto.id AS id_restaurant, resto.nom, rh.id_jour, rh.heure_debut, rh.minute_debut, rh.heure_fin, rh.minute_fin, pl.prix, pl.montant_min, 
			pl.reduction_premium
		FROM panier 
		JOIN prix_livraison pl ON panier.distance BETWEEN pl.distance_min AND pl.distance_max
		JOIN restaurants resto ON resto.id = panier.id_restaurant
		LEFT JOIN restaurant_horaires rh ON rh.id_restaurant = resto.id AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR (rh.heure_debut <= HOUR(CURRENT_TIME) AND rh.heure_fin > HOUR(CURRENT_TIME)))";
		if ($this->uid == -1) {
			$sql .= " WHERE adresse_ip = :ip";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$sql .= " WHERE uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->id = $value['id'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->telephone = $value['telephone'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->id_restaurant = $value['id_restaurant'];
		$this->prix_livraison = $value['prix'];
		$this->prix_minimum = $value['montant_min'];
		$this->reduction_premium = $value['reduction_premium'];
		
		$restaurant = new Model_Restaurant(false);
		$restaurant->id = $value['id_restaurant'];
		$restaurant->nom = $value['nom'];
		$horaire = new Model_Horaire(false);
		$horaire->id_jour = $value['id_jour'];
		$horaire->heure_debut = $value['heure_debut'];
		$horaire->minute_debut = $value['minute_debut'];
		$horaire->heure_fin = $value['heure_fin'];
		$horaire->minute_fin = $value['minute_fin'];
		$restaurant->horaire = $horaire;
		
		$this->restaurant = $restaurant;
		
		return $this;
	}
	
	public function loadPanier () {
		$sql = "SELECT panier.id, panier.rue, panier.ville, panier.code_postal, panier.telephone, panier.heure_souhaite, panier.minute_souhaite,
			resto.id AS id_restaurant, resto.nom, rh.id_jour, rh.heure_debut, rh.minute_debut, rh.heure_fin, rh.minute_fin, pl.prix, pl.montant_min, 
			pl.reduction_premium, promo.description, promo.type_reduc, promo.sur_prix_livraison, promo.valeur_prix_livraison, promo.sur_prix_total, 
			promo.valeur_prix_total, promo.pourcentage_prix_total
		FROM panier 
		JOIN prix_livraison pl ON panier.distance BETWEEN pl.distance_min AND pl.distance_max
		JOIN restaurants resto ON resto.id = panier.id_restaurant
		LEFT JOIN restaurant_horaires rh ON rh.id_restaurant = resto.id AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR (rh.heure_debut <= HOUR(CURRENT_TIME) AND rh.heure_fin > HOUR(CURRENT_TIME)))
		LEFT JOIN code_promo promo ON promo.id = panier.id_code_promo";
		if ($this->uid == -1) {
			$sql .= " WHERE adresse_ip = :ip";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$sql .= " WHERE uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->id = $value['id'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->telephone = $value['telephone'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->id_restaurant = $value['id_restaurant'];
		$this->prix_livraison = $value['prix'];
		$this->prix_minimum = $value['montant_min'];
		$this->reduction_premium = $value['reduction_premium'];
		
		$horaire = new Model_Horaire(false);
		$horaire->id_jour = $value['id_jour'];
		$horaire->heure_debut = $value['heure_debut'];
		$horaire->minute_debut = $value['minute_debut'];
		$horaire->heure_fin = $value['heure_fin'];
		$horaire->minute_fin = $value['minute_fin'];
		
		$restaurant = new Model_Restaurant(false);
		$restaurant->id = $value['id_restaurant'];
		$restaurant->nom = $value['nom'];
		$restaurant->horaire = $horaire;
		
		$this->restaurant = $restaurant;
		
		$codePromo = new Model_CodePromo(false);
		$codePromo->description = $value['description'];
		$codePromo->type_reduc = $value['type_reduc'];
		$codePromo->sur_prix_livraison = $value['sur_prix_livraison'];
		$codePromo->valeur_prix_livraison = $value['valeur_prix_livraison'];
		$codePromo->sur_prix_total = $value['sur_prix_total'];
		$codePromo->valeur_prix_total = $value['valeur_prix_total'];
		$codePromo->pourcentage_prix_total = $value['pourcentage_prix_total'];
		$this->code_promo = $codePromo;
		
		$sql = "SELECT pc.id, carte.nom, cf.prix, SUM(supp.prix) AS prix_supp, pc.quantite
		FROM panier_carte pc
		JOIN carte ON carte.id = pc.id_carte
		JOIN carte_format cf ON cf.id_format = pc.id_format AND cf.id_carte = carte.id
		LEFT JOIN panier_carte_supplement pcs ON pcs.id_panier_carte = pc.id
		LEFT JOIN supplements supp ON supp.id = pcs.id_supplement
		WHERE pc.id_panier = :id
		GROUP BY pc.id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCarte = $stmt->fetchAll();
		foreach ($listCarte as $c) {
			$carte = new Model_Carte();
			$carte->id = $c["id"];
			$carte->nom = $c["nom"];
			$carte->prix = ($c["prix"] + $c["prix_supp"]) * $c["quantite"];
			$carte->quantite = $c["quantite"];
			$this->carteList[] = $carte;
		}
		$sql = "SELECT pm.id, menus.nom, mf.prix, pm.quantite
		FROM panier_menu pm
		JOIN menus ON menus.id = pm.id_menu
		JOIN menu_format mf ON mf.id_format = pm.id_format AND mf.id_menu = menus.id
		WHERE pm.id_panier = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listMenu = $stmt->fetchAll();
		foreach ($listMenu as $m) {
			$menu = new Model_Menu();
			$menu->id = $m["id"];
			$menu->nom = $m["nom"];
			$menu->prix = $m["prix"] * $m["quantite"];
			$menu->quantite = $m["quantite"];
			$this->menuList[] = $menu;
		}
		return $this;
	}
	
	public function load () {
		$sql = "SELECT panier.id, panier.rue, panier.ville, panier.code_postal, panier.telephone, panier.heure_souhaite, panier.minute_souhaite, panier.distance,
			resto.id AS id_restaurant, resto.nom, resto.rue AS rue_restaurant, resto.ville AS ville_restaurant, resto.code_postal AS cp_restaurant, 
			rh.id_jour, rh.heure_debut, rh.minute_debut, rh.heure_fin, rh.minute_fin, pl.prix, pl.montant_min, pl.reduction_premium, promo.description,
			promo.type_reduc, promo.sur_prix_livraison, promo.valeur_prix_livraison, promo.sur_prix_total, promo.valeur_prix_total, promo.pourcentage_prix_total
		FROM panier 
		JOIN prix_livraison pl ON panier.distance BETWEEN pl.distance_min AND pl.distance_max
		JOIN restaurants resto ON resto.id = panier.id_restaurant
		LEFT JOIN restaurant_horaires rh ON rh.id_restaurant = resto.id AND rh.id_jour = WEEKDAY(CURRENT_DATE)+1 AND (rh.heure_debut > HOUR(CURRENT_TIME) 
		OR (rh.heure_debut <= HOUR(CURRENT_TIME) AND rh.heure_fin > HOUR(CURRENT_TIME)))
		LEFT JOIN code_promo promo ON promo.id = panier.id_code_promo
		WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->id = $value['id'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->telephone = $value['telephone'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->distance = $value['distance'];
		$this->id_restaurant = $value['id_restaurant'];
		$this->prix_livraison = $value['prix'];
		$this->prix_minimum = $value['montant_min'];
		$this->reduction_premium = $value['reduction_premium'];
		
		$horaire = new Model_Horaire(false);
		$horaire->id_jour = $value['id_jour'];
		$horaire->heure_debut = $value['heure_debut'];
		$horaire->minute_debut = $value['minute_debut'];
		$horaire->heure_fin = $value['heure_fin'];
		$horaire->minute_fin = $value['minute_fin'];
		
		$restaurant = new Model_Restaurant(false);
		$restaurant->id = $value['id_restaurant'];
		$restaurant->nom = $value['nom'];
		$restaurant->rue = $value['rue_restaurant'];
		$restaurant->ville = $value['ville_restaurant'];
		$restaurant->code_postal = $value['cp_restaurant'];
		$restaurant->horaire = $horaire;
		
		$this->restaurant = $restaurant;
		
		$codePromo = new Model_CodePromo(false);
		$codePromo->description = $value['description'];
		$codePromo->type_reduc = $value['type_reduc'];
		$codePromo->sur_prix_livraison = $value['sur_prix_livraison'];
		$codePromo->valeur_prix_livraison = $value['valeur_prix_livraison'];
		$codePromo->sur_prix_total = $value['sur_prix_total'];
		$codePromo->valeur_prix_total = $value['valeur_prix_total'];
		$codePromo->pourcentage_prix_total = $value['pourcentage_prix_total'];
		$this->code_promo = $codePromo;
		
		$sql = "SELECT pm.id AS id, menu.id AS id_menu, menu.nom AS nom_menu, format.id AS id_format, format.nom AS nom_format, pm.quantite, mf.prix
		FROM panier_menu pm
		JOIN menus menu ON menu.id = pm.id_menu
		JOIN restaurant_format format ON format.id = pm.id_format
		JOIN menu_format mf ON mf.id_menu = menu.id AND mf.id_format = format.id
		WHERE pm.id_panier = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listPanierMenu = $stmt->fetchAll();
		foreach ($listPanierMenu as $panierMenu) {
			$menu = new Model_Menu(false);
			$menu->id = $panierMenu['id_menu'];
			$menu->nom = $panierMenu['nom_menu'];
			$menu->quantite = $panierMenu['quantite'];
			$menu->prix = $panierMenu['prix'] * $panierMenu['quantite'];
			
			$format = new Model_Format();
			$format->id = $panierMenu['id_format'];
			$format->nom = $panierMenu['nom_format'];
			
			$menu->addFormat($format);
			
			$formule = new Model_Formule();
			$formule->id = 0;
			$formule->nom = 'formule';
			
			$menu->addFormule($formule);
			
			$sql = "SELECT pmc.id AS id, menu_cat.id AS id_categorie, menu_cat.nom AS nom_categorie, carte.id AS id_carte, carte.nom AS nom_carte
			FROM panier_menu_contenu pmc 
			JOIN menu_contenu mc ON mc.id = pmc.id_contenu
			JOIN menu_categorie menu_cat ON menu_cat.id = mc.id_menu_categorie
			JOIN carte ON carte.id = mc.id_carte
			WHERE pmc.id_panier_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierMenu['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierContenu = $stmt->fetchAll();
			foreach ($listPanierContenu as $panierContenu) {
				$categorie = new Model_Categorie(false);
				$categorie->id = $panierContenu['id_categorie'];
				$categorie->nom = $panierContenu['nom_categorie'];
				
				$contenu = new Model_Contenu(false);
				$contenu->id = $panierContenu['id_carte'];
				$contenu->nom = $panierContenu['nom_carte'];
				
				$categorie->addContenu($contenu);
				
				$formule->addCategorie($categorie);
			}
			$this->menuList[] = $menu;
		}
		
		$sql = "SELECT pc.id AS id, carte.id AS id_carte, carte.nom AS nom_carte, rf.id AS id_format, rf.nom AS nom_format, cf.prix, pc.quantite
		FROM panier_carte pc
		JOIN carte ON carte.id = pc.id_carte
		JOIN restaurant_format rf ON rf.id = pc.id_format
		JOIN carte_format cf ON cf.id_carte = carte.id AND cf.id_format = rf.id
		WHERE pc.id_panier = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listPanierCarte = $stmt->fetchAll();
		foreach ($listPanierCarte as $panierCarte) {
			$carte = new Model_Carte(false);
			$carte->id = $panierCarte['id_carte'];
			$carte->nom = $panierCarte['nom_carte'];
			$carte->prix = $panierCarte['prix'] * $panierCarte['quantite'];
			$carte->quantite = $panierCarte['quantite'];
			
			$format = new Model_Format();
			$format->id = $panierCarte['id_format'];
			$format->nom = $panierCarte['nom_format'];
			
			$carte->addFormat($format);
			
			$sql = "SELECT pcs.id AS id, supp.id AS id_supplement, supp.nom AS nom_supplement
			FROM panier_carte_supplement pcs 
			JOIN supplements supp ON supp.id = pcs.id_supplement
			WHERE pcs.id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierCarteSupplement = $stmt->fetchAll();
			foreach ($listPanierCarteSupplement as $panierCarteSupplement) {
				$supplement = new Model_Supplement(false);
				$supplement->id = $panierCarteSupplement['id_supplement'];
				$supplement->nom = $panierCarteSupplement['nom_supplement'];
				
				$carte->addSupplement($supplement);
			}
			
			$sql = "SELECT pco.id AS id, ro.id AS id_option, ro.nom AS nom_option, rov.id AS id_value, rov.nom AS nom_value
			FROM panier_carte_option pco 
			JOIN restaurant_option ro ON ro.id = pco.id_option
			JOIN restaurant_option_value rov ON rov.id = pco.id_value
			WHERE pco.id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierCarteOption = $stmt->fetchAll();
			foreach ($listPanierCarteOption as $panierCarteOption) {
				$option = new Model_Option(false);
				$option->id = $panierCarteOption['id_option'];
				$option->nom = $panierCarteOption['nom_option'];
				
				$optionValue = new Model_Option_Value(false);
				$optionValue->id = $panierCarteOption['id_value'];
				$optionValue->nom = $panierCarteOption['nom_value'];
				
				$option->addValue($optionValue);
				
				$carte->addOption($option);
			}
			
			$sql = "SELECT pca.id AS id, carte.id AS id_carte, carte.nom AS nom_carte
			FROM panier_carte_accompagnement pca 
			JOIN carte ON carte.id = pca.id_accompagnement
			WHERE pca.id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierCarteAccompagnement = $stmt->fetchAll();
			foreach ($listPanierCarteAccompagnement as $panierCarteAccompagnement) {
				$accompagnement = new Model_Accompagnement(false);
				$accompagnement->id = $panierCarteAccompagnement['id'];
				$accompagnementCarte = new Model_Carte(false);
				$accompagnementCarte->id = $panierCarteAccompagnement['id_carte'];
				$accompagnementCarte->nom = $panierCarteAccompagnement['nom_carte'];
				$accompagnement->addCarte($accompagnementCarte);
				$carte->addAccompagnement($accompagnement);
			}
			$this->carteList[] = $carte;
		}
		return $this;
	}
	
	public function loadById () {
		$sql = "SELECT panier.id, panier.rue, panier.ville, panier.code_postal, panier.telephone, panier.heure_souhaite, panier.minute_souhaite, panier.distance,
			resto.id AS id_restaurant, resto.nom AS nom_resto, resto.rue AS rue_restaurant, resto.ville AS ville_restaurant, resto.code_postal AS cp_restaurant, 
			pl.prix, pl.montant_min, pl.reduction_premium, promo.description, user.uid AS uid, user.nom AS nom, user.prenom AS prenom, user.email, 
			promo.type_reduc, promo.sur_prix_livraison, promo.valeur_prix_livraison, promo.sur_prix_total, promo.valeur_prix_total, promo.pourcentage_prix_total
		FROM panier 
		JOIN prix_livraison pl ON panier.distance BETWEEN pl.distance_min AND pl.distance_max
		JOIN restaurants resto ON resto.id = panier.id_restaurant
		LEFT JOIN users user ON user.uid = panier.uid
		LEFT JOIN code_promo promo ON promo.id = panier.id_code_promo
		WHERE panier.id = :id";
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
		$this->id = $value['id'];
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->telephone = $value['telephone'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->distance = $value['distance'];
		$this->id_restaurant = $value['id_restaurant'];
		$this->prix_livraison = $value['prix'];
		$this->prix_minimum = $value['montant_min'];
		$this->reduction_premium = $value['reduction_premium'];
		
		$user = new Model_User(false);
		$user->id = $value['uid'];
		$user->nom = $value['nom'];
		$user->prenom = $value['prenom'];
		$user->email = $value['email'];
		
		$this->user = $user;
		
		$restaurant = new Model_Restaurant(false);
		$restaurant->id = $value['id_restaurant'];
		$restaurant->nom = $value['nom_resto'];
		$restaurant->rue = $value['rue_restaurant'];
		$restaurant->ville = $value['ville_restaurant'];
		$restaurant->code_postal = $value['cp_restaurant'];
		
		$this->restaurant = $restaurant;
		
		$codePromo = new Model_CodePromo(false);
		$codePromo->description = $value['description'];
		$codePromo->type_reduc = $value['type_reduc'];
		$codePromo->sur_prix_livraison = $value['sur_prix_livraison'];
		$codePromo->valeur_prix_livraison = $value['valeur_prix_livraison'];
		$codePromo->sur_prix_total = $value['sur_prix_total'];
		$codePromo->valeur_prix_total = $value['valeur_prix_total'];
		$codePromo->pourcentage_prix_total = $value['pourcentage_prix_total'];
		$this->code_promo = $codePromo;
		
		$sql = "SELECT pm.id AS id, menu.id AS id_menu, menu.nom AS nom_menu, format.id AS id_format, format.nom AS nom_format, pm.quantite, mf.prix
		FROM panier_menu pm
		JOIN menus menu ON menu.id = pm.id_menu
		JOIN restaurant_format format ON format.id = pm.id_format
		JOIN menu_format mf ON mf.id_menu = menu.id AND mf.id_format = format.id
		WHERE pm.id_panier = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listPanierMenu = $stmt->fetchAll();
		foreach ($listPanierMenu as $panierMenu) {
			$menu = new Model_Menu(false);
			$menu->id = $panierMenu['id_menu'];
			$menu->nom = $panierMenu['nom_menu'];
			$menu->quantite = $panierMenu['quantite'];
			$menu->prix = $panierMenu['prix'] * $panierMenu['quantite'];
			
			$format = new Model_Format();
			$format->id = $panierMenu['id_format'];
			$format->nom = $panierMenu['nom_format'];
			
			$menu->addFormat($format);
			
			$formule = new Model_Formule();
			$formule->id = 0;
			$formule->nom = 'formule';
			
			$menu->addFormule($formule);
			
			$sql = "SELECT pmc.id AS id, menu_cat.id AS id_categorie, menu_cat.nom AS nom_categorie, carte.id AS id_carte, carte.nom AS nom_carte
			FROM panier_menu_contenu pmc 
			JOIN menu_contenu mc ON mc.id = pmc.id_contenu
			JOIN menu_categorie menu_cat ON menu_cat.id = mc.id_menu_categorie
			JOIN carte ON carte.id = mc.id_carte
			WHERE pmc.id_panier_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierMenu['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierContenu = $stmt->fetchAll();
			foreach ($listPanierContenu as $panierContenu) {
				$categorie = new Model_Categorie(false);
				$categorie->id = $panierContenu['id_categorie'];
				$categorie->nom = $panierContenu['nom_categorie'];
				
				$contenu = new Model_Contenu(false);
				$contenu->id = $panierContenu['id_carte'];
				$contenu->nom = $panierContenu['nom_carte'];
				
				$categorie->addContenu($contenu);
				
				$formule->addCategorie($categorie);
			}
			$this->menuList[] = $menu;
		}
		
		$sql = "SELECT pc.id AS id, carte.id AS id_carte, carte.nom AS nom_carte, rf.id AS id_format, rf.nom AS nom_format, cf.prix, pc.quantite
		FROM panier_carte pc
		JOIN carte ON carte.id = pc.id_carte
		JOIN restaurant_format rf ON rf.id = pc.id_format
		JOIN carte_format cf ON cf.id_carte = carte.id AND cf.id_format = rf.id
		WHERE pc.id_panier = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listPanierCarte = $stmt->fetchAll();
		foreach ($listPanierCarte as $panierCarte) {
			$carte = new Model_Carte(false);
			$carte->id = $panierCarte['id_carte'];
			$carte->nom = $panierCarte['nom_carte'];
			$carte->prix = $panierCarte['prix'] * $panierCarte['quantite'];
			$carte->quantite = $panierCarte['quantite'];
			
			$format = new Model_Format();
			$format->id = $panierCarte['id_format'];
			$format->nom = $panierCarte['nom_format'];
			
			$carte->addFormat($format);
			
			$sql = "SELECT pcs.id AS id, supp.id AS id_supplement, supp.nom AS nom_supplement
			FROM panier_carte_supplement pcs 
			JOIN supplements supp ON supp.id = pcs.id_supplement
			WHERE pcs.id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierCarteSupplement = $stmt->fetchAll();
			foreach ($listPanierCarteSupplement as $panierCarteSupplement) {
				$supplement = new Model_Supplement(false);
				$supplement->id = $panierCarteSupplement['id_supplement'];
				$supplement->nom = $panierCarteSupplement['nom_supplement'];
				
				$carte->addSupplement($supplement);
			}
			
			$sql = "SELECT pco.id AS id, ro.id AS id_option, ro.nom AS nom_option, rov.id AS id_value, rov.nom AS nom_value
			FROM panier_carte_option pco 
			JOIN restaurant_option ro ON ro.id = pco.id_option
			JOIN restaurant_option_value rov ON rov.id = pco.id_value
			WHERE pco.id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierCarteOption = $stmt->fetchAll();
			foreach ($listPanierCarteOption as $panierCarteOption) {
				$option = new Model_Option(false);
				$option->id = $panierCarteOption['id_option'];
				$option->nom = $panierCarteOption['nom_option'];
				
				$optionValue = new Model_Option_Value(false);
				$optionValue->id = $panierCarteOption['id_value'];
				$optionValue->nom = $panierCarteOption['nom_value'];
				
				$option->addValue($optionValue);
				
				$carte->addOption($option);
			}
			
			$sql = "SELECT pca.id AS id, carte.id AS id_carte, carte.nom AS nom_carte
			FROM panier_carte_accompagnement pca 
			JOIN carte ON carte.id = pca.id_accompagnement
			WHERE pca.id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierCarteAccompagnement = $stmt->fetchAll();
			foreach ($listPanierCarteAccompagnement as $panierCarteAccompagnement) {
				$accompagnement = new Model_Accompagnement(false);
				$accompagnement->id = $panierCarteAccompagnement['id'];
				$accompagnementCarte = new Model_Carte(false);
				$accompagnementCarte->id = $panierCarteAccompagnement['id_carte'];
				$accompagnementCarte->nom = $panierCarteAccompagnement['nom_carte'];
				$accompagnement->addCarte($accompagnementCarte);
				$carte->addAccompagnement($accompagnement);
			}
			$this->carteList[] = $carte;
		}
		return $this;
	}
	
	public function addCarte ($id_carte, $format, $quantite) {
		if ($this->id_restaurant == -1) {}
		$sql = "INSERT INTO panier_carte (id_panier, id_carte, id_format, quantite) VALUES(:panier, :carte, :format, :quantite)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id);
		$stmt->bindValue(":carte", $id_carte);
		$stmt->bindValue(":format", $format);
		$stmt->bindValue(":quantite", $quantite);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addCarteOption ($id_panier_carte, $id_option, $id_value) {
		$sql = "INSERT INTO panier_carte_option (id_panier_carte, id_option, id_value) VALUES(:panier_carte, :option, :value)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier_carte", $id_panier_carte);
		$stmt->bindValue(":option", $id_option);
		$stmt->bindValue(":value", $id_value);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addCarteAccompagnement ($id_panier_carte, $id_carte) {
		$sql = "INSERT INTO panier_carte_accompagnement (id_panier_carte, id_accompagnement) VALUES(:panier_carte, :accompagnement)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier_carte", $id_panier_carte);
		$stmt->bindValue(":accompagnement", $id_carte);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addCarteSupplement ($id_panier_carte, $id_supplement) {
		$sql = "INSERT INTO panier_carte_supplement (id_panier_carte, id_supplement) VALUES(:panier_carte, :supplement)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier_carte", $id_panier_carte);
		$stmt->bindValue(":supplement", $id_supplement);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addMenu ($id_menu, $id_format, $id_formule, $quantite) {
		$sql = "INSERT INTO panier_menu (id_panier, id_menu, id_format, id_formule, quantite) VALUES(:panier, :menu, :format, :formule, :quantite)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id);
		$stmt->bindValue(":menu", $id_menu);
		$stmt->bindValue(":format", $id_format);
		$stmt->bindValue(":formule", $id_formule);
		$stmt->bindValue(":quantite", $quantite);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addContenu($id_panier_menu, $id_contenu) {
		$sql = "INSERT INTO panier_menu_contenu (id_panier_menu, id_contenu) VALUES(:panier_menu, :contenu)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier_menu", $id_panier_menu);
		$stmt->bindValue(":contenu", $id_contenu);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function validate($rue, $ville, $code_postal, $telephone, $heure_souhaite = -1, $minute_souhaite = 0, $distance = 0) {
		$sql = "UPDATE panier SET rue = :rue, ville = :ville, code_postal = :code_postal, telephone = :telephone, heure_souhaite = :heure_souhaite, 
		minute_souhaite = :minute_souhaite, distance = :distance";
		if ($this->uid == -1) {
			$sql .= " WHERE adresse_ip = :ip";
		} else {
			$sql .= " WHERE uid = :uid";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":rue", $rue);
		$stmt->bindValue(":ville", $ville);
		$stmt->bindValue(":code_postal", $code_postal);
		$stmt->bindValue(":telephone", $telephone);
		$stmt->bindValue(":heure_souhaite", $heure_souhaite);
		$stmt->bindValue(":minute_souhaite", $minute_souhaite);
		$stmt->bindValue(":distance", $distance);
		if ($this->uid == -1) {
			$stmt->bindValue(":ip", $this->adresse_ip);
		} else {
			$stmt->bindValue(":uid", $this->uid);
		}
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function removePanierCarte ($panier_carte) {
		$sql = "DELETE FROM panier_carte_supplement WHERE id_panier_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier_carte);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "DELETE FROM panier_carte_option WHERE id_panier_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier_carte);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "DELETE FROM panier_carte_accompagnement WHERE id_panier_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier_carte);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "DELETE FROM panier_carte WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier_carte);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function removePanierMenu ($panier_menu) {
		$sql = "DELETE FROM panier_menu_contenu WHERE id_panier_menu = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier_menu);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$sql = "DELETE FROM panier_menu WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier_menu);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function remove () {
		$sql = "SELECT id FROM panier_carte WHERE id_panier = :panier";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$results = $stmt->fetchAll();
		foreach ($results as $result) {
			$sql = "DELETE FROM panier_carte_supplement WHERE id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $result['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$sql = "DELETE FROM panier_carte_option WHERE id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $result['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$sql = "DELETE FROM panier_carte_accompagnement WHERE id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $result['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		$sql = "DELETE FROM panier_carte WHERE id_panier = :panier";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		
		$sql = "SELECT id FROM panier_menu WHERE id_panier = :panier";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$results = $stmt->fetchAll();
		foreach ($results as $result) {
			$sql = "DELETE FROM panier_menu_contenu WHERE id_panier_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $result['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		$sql = "DELETE FROM panier_menu WHERE id_panier = :panier";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		
		$sql = "DELETE FROM panier WHERE id = :panier";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
	}
}