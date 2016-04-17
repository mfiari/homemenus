<?php

class Model_Pre_Commande extends Model_Template {
	
	private $id;
	private $uid;
	private $client;
	private $rue;
	private $ville;
	private $code_postal;
	private $latitude;
	private $longitude;
	private $telephone;
	private $prix;
	private $prix_livraison;
	private $distance;
	private $note;
	private $carte;
	private $cartes;
	private $menus;
	private $date_commande;
	private $heure_souhaite;
	private $minute_souhaite;
	private $restaurant;
	private $is_modif;
	private $id_restaurant;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->id = -1;
		$this->id_restaurant = -1;
		$this->cartes = array();
		$this->menus = array();
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
		$sql = "SELECT id, id_restaurant FROM pre_commande WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
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
	
	public function insert () {
		$sql = "INSERT INTO pre_commande (uid) VALUES(:uid)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$this->id = $this->db->lastInsertId();
	}
	
	public function update () {
		$sql = "UPDATE pre_commande SET id_restaurant = :restaurant, rue = :rue, ville = :ville, code_postal = :code_postal, latitude = :latitude, 
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
			return false;
		}
		return true;
	}
	
	public function addCarte ($id_carte, $format, $quantite) {
		if ($this->id_restaurant == -1) {}
		$sql = "INSERT INTO pre_commande_carte (id_commande, id_carte, id_format, quantite) VALUES(:commande, :carte, :format, :quantite)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":carte", $id_carte);
		$stmt->bindValue(":format", $format);
		$stmt->bindValue(":quantite", $quantite);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addCarteOption ($id_commande_carte, $id_option, $id_value) {
		$sql = "INSERT INTO pre_commande_carte_option (id_commande_carte, id_option, id_value) VALUES(:commande_carte, :option, :value)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande_carte", $id_commande_carte);
		$stmt->bindValue(":option", $id_option);
		$stmt->bindValue(":value", $id_value);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addCarteAccompagnement ($id_commande_carte, $id_carte) {
		$sql = "INSERT INTO pre_commande_carte_accompagnement (id_commande_carte, id_accompagnement) VALUES(:commande_carte, :accompagnement)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande_carte", $id_commande_carte);
		$stmt->bindValue(":accompagnement", $id_carte);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addCarteSupplement ($id_commande_carte, $id_supplement) {
		$sql = "INSERT INTO pre_commande_carte_supplement (id_commande_carte, id_supplement) VALUES(:commande_carte, :supplement)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande_carte", $id_commande_carte);
		$stmt->bindValue(":supplement", $id_supplement);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function getCommandeInMonth ($month) {
		$sql = "SELECT id, date_commande FROM pre_commande WHERE uid = :uid AND MONTH(date_commande) = :month ORDER BY date_commande ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		$stmt->bindValue(":month", $month);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Pre_Commande(false);
			$commande->id = $c["id"];
			$commande->date_commande = $c["date_commande"];
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function getCommandeDay ($month, $day) {
		$sql = "SELECT com.id, date_commande, resto.id AS id_resto, resto.nom 
		FROM pre_commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		WHERE com.uid = :uid AND MONTH(date_commande) = :month AND DAYOFMONTH(date_commande) = :day";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		$stmt->bindValue(":month", $month);
		$stmt->bindValue(":day", $day);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Pre_Commande(false);
			$commande->id = $c["id"];
			$commande->date_commande = $c["date_commande"];
			$commande->restaurant = new Model_Restaurant(false);
			$commande->restaurant->id = $c['id_resto'];
			$commande->restaurant->nom = $c['nom'];
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function get () {
		$sql = "SELECT id, rue, ville, code_postal, latitude, longitude, telephone, date_commande, heure_souhaite, minute_souhaite
		FROM pre_commande
		WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->latitude = $value['latitude'];
		$this->longitude = $value['longitude'];
		$this->telephone = $value['telephone'];
		$this->date_commande = $value['date_commande'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		return $this;
	}
	
	public function load () {
		$sql = "SELECT com.id AS id_commande, com.rue, com.ville, com.code_postal, com.latitude, com.longitude, com.telephone, resto.id AS id_resto, 
			resto.nom AS nom_resto, resto.rue AS rue_resto, resto.ville AS ville_resto, resto.code_postal AS cp_resto, date_commande, heure_souhaite, 
			minute_souhaite, prix, prix_livraison, distance
		FROM pre_commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		WHERE com.id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->rue = $value['rue'];
		$this->ville = $value['ville'];
		$this->code_postal = $value['code_postal'];
		$this->latitude = $value['latitude'];
		$this->longitude = $value['longitude'];
		$this->telephone = $value['telephone'];
		$this->restaurant = new Model_Restaurant(false);
		$this->restaurant->id = $value['id_resto'];
		$this->restaurant->nom = $value['nom_resto'];
		$this->restaurant->rue = $value['rue_resto'];
		$this->restaurant->ville = $value['ville_resto'];
		$this->restaurant->code_postal = $value['cp_resto'];
		$this->date_commande = $value['date_commande'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->prix = $value['prix'];
		$this->prix_livraison = $value['prix_livraison'];
		$this->distance = $value['distance'];
		$this->cartes = array();
		
		$sql = "SELECT carte.id, carte.nom, carte.id_categorie, pcc.quantite, cf.prix
		FROM pre_commande_carte pcc 
		JOIN carte ON carte.id = pcc.id_carte
		JOIN carte_format cf ON cf.id_carte = carte.id AND cf.id_format = pcc.id_format
		WHERE pcc.id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$result = $stmt->fetchAll();
		foreach ($result as $c) {
			$carte = new Model_Carte(false);
			$carte->id = $c['id'];
			$carte->nom = $c['nom'];
			$carte->quantite = $c['quantite'];
			$carte->prix = $c['prix'] * $c['quantite'];
			
			$sql = "SELECT supp.id, supp.nom, supp.prix
			FROM pre_commande_carte_supplement pcss
			JOIN supplements supp ON supp.id = pcss.id_supplement
			WHERE pcss.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $this->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$supplements = $stmt->fetchAll();
			foreach ($supplements as $sup) {
				$supplement = new Model_Supplement(false);
				$supplement->id = $sup["id"];
				$supplement->nom = $sup["nom"];
				$supplement->prix = $sup["prix"];
				$carte->addSupplement($supplement);
			}
			$this->cartes[] = $carte;
		}
		
		$sql = "SELECT menu.id, menu.nom, pcm.quantite
		FROM pre_commande_menu pcm 
		JOIN menus menu ON menu.id = pcm.id_menu
		WHERE pcm.id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$menus = $stmt->fetchAll();
		foreach ($menus as $m) {
			$menu = new Model_Menu(false);
			$menu->id = $m['id'];
			$menu->nom = $m['nom'];
			$menu->quantite = $m['quantite'];
			
			$sql = "SELECT carte.id, carte.nom, mc.limite_supplement
			FROM pre_commande_menu_contenu pcmc
			JOIN menu_contenu mc ON mc.id = pcmc.id_contenu
			JOIN carte ON carte.id = mc.id_carte
			WHERE pcmc.id_commande_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $this->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$contenus = $stmt->fetchAll();
			foreach ($contenus as $cont) {
				$contenu = new Model_Contenu(false);
				$contenu->id = $cont["id"];
				$contenu->nom = $cont["nom"];
				$contenu->supplement = $cont["limite_supplement"];
				$menu->addContenu($contenu);
			}
			$this->menus[] = $menu;
		}
		
		return $this;
	}
	
	
	public function getClient () {
		$sql = "SELECT client.uid, client.nom, client.prenom, client.gcm_token, client.is_login FROM commande
		JOIN users client ON client.uid = commande.uid
		WHERE commande.id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->client = new Model_User();
		$this->client->id = $value['uid'];
		$this->client->nom = $value['nom'];
		$this->client->prenom = $value['prenom'];
		$this->client->gcm_token = $value['gcm_token'];
		$this->client->is_login = $value['is_login'];
		return $this->client;
	}
		

	
	public function remove () {
		$sql = "SELECT id FROM commande_menu WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$result = $stmt->fetchAll();
		foreach ($result as $value) {
			$sql = "DELETE FROM commande_contenu WHERE id_commande_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$sql = "DELETE FROM commande_menu WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		$sql = "SELECT id FROM commande_carte WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$result = $stmt->fetchAll();
		foreach ($result as $value) {
			$sql = "DELETE FROM commande_carte_supplement WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$sql = "DELETE FROM commande_carte WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		$sql = "DELETE FROM commande WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return true;
	}
}