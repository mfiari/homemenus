<?php

class Model_Menu extends Model_Template {
	
	private $nom;
	private $id_restaurant;
	private $id_formule;
	private $id_categorie;
	private $ordre;
	private $prix;
	private $quantite;
	private $temps_preparation;
	private $commentaire;
	private $formats;
	private $formules;
	private $contenus;
	private $horaires;
	private $logo;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->id = -1;
		$this->formats = array();
		$this->formules = array();
		$this->contenus = array();
		$this->horaires = array();
		$this->_tableName = "menus";
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
	
	public function addFormat ($format) {
		$this->formats[] = $format;
	}
	
	public function getFormat ($indice) {
		return $this->formats[$indice];
	}
	
	public function addFormule ($formule) {
		$this->formules[] = $formule;
	}
	
	public function getFormule ($indice) {
		return $this->formules[$indice];
	}
	
	public function addHoraire ($horaire) {
		$this->horaires[] = $horaire;
	}
	
	public function save () {
		if ($this->id == -1) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	public function insert () {
		$sql = "INSERT INTO menus (nom, id_restaurant, ordre, commentaire) 
		VALUES (:nom, :restaurant, :ordre, :commentaire)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":restaurant", $this->id_restaurant);
		$stmt->bindValue(":ordre", $this->ordre);
		$stmt->bindValue(":commentaire", $this->commentaire);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		foreach ($this->formats as $format) {
			$sql = "INSERT INTO menu_format (id_menu, id_format, prix, temps_preparation) 
			VALUES (:menu, :format, :prix, :temps_preparation)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":menu", $this->id);
			$stmt->bindValue(":format", $format->id);
			$stmt->bindValue(":prix", $format->prix);
			$stmt->bindValue(":temps_preparation", $format->temps_preparation);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		foreach ($this->horaires as $horaire) {
			$sql = "INSERT INTO menu_disponibilite (id_menu, id_horaire) VALUES (:menu, :horaire)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":menu", $this->id);
			$stmt->bindValue(":horaire", $horaire->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		foreach ($this->formules as $formule) {
			$sql = "INSERT INTO menu_formule (id_menu, id_formule) VALUES (:menu, :formule)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":menu", $this->id);
			$stmt->bindValue(":formule", $formule->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		return true;
	}
	
	public function update () {
		$sql = "UPDATE menus SET nom = :nom WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":nom", $this->nom);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function load ($id_restaurant = false) {
		$sql = "SELECT nom, commentaire FROM menus WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		$this->commentaire = $value['commentaire'];
		
		$sql = "SELECT format.id, format.nom, mf.prix, mf.temps_preparation 
		FROM menu_format mf
		JOIN restaurant_format format ON format.id = mf.id_format
		WHERE mf.id_menu = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$formats = $stmt->fetchAll();
		foreach ($formats as $form) {
			$format = new Model_Format(false);
			$format->id = $form["id"];
			$format->nom = $form["nom"];
			$format->prix = $form["prix"];
			$format->temps_preparation = $form["temps_preparation"];
			$this->formats[] = $format;
		}
		
		$sql = "SELECT rf.id, rf.nom
		FROM menu_formule mf
		JOIN restaurant_formule rf ON rf.id = mf.id_formule
		WHERE id_menu = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$formules = $stmt->fetchAll();
		foreach ($formules as $form) {
			$formule = new Model_Formule(false);
			$formule->id = $form["id"];
			$formule->nom = $form["nom"];
			
			$sql = "SELECT id, nom, quantite
			FROM menu_categorie
			WHERE id_formule = :id_formule AND id_menu = :id_menu";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_formule", $formule->id);
			$stmt->bindValue(":id_menu", $this->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$categories = $stmt->fetchAll();
			foreach ($categories as $cat) {
				$categorie = new Model_Categorie(false);
				$categorie->id = $cat["id"];
				$categorie->nom = $cat["nom"];
				$categorie->quantite = $cat["quantite"];
				
				$sql = "SELECT id, id_carte, obligatoire, limite_supplement, limite_accompagnement, commentaire
					FROM menu_contenu
					WHERE id_menu_categorie = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $categorie->id);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
				$contenus = $stmt->fetchAll();
				foreach ($contenus as $cont) {
					$contenu = new Model_Contenu(false);
					$contenu->id = $cont['id'];
					$contenu->obligatoire = $cont['obligatoire'];
					$contenu->limite_supplement = $cont['limite_supplement'];
					$contenu->commentaire = $cont['commentaire'];
					
					$modelCarte = new Model_Carte(true, $this->db);
					$modelCarte->id = $cont['id_carte'];
					$contenu->carte = $modelCarte->load($id_restaurant);
					
					$categorie->addContenu($contenu);
				}
				
				$formule->addCategorie($categorie);
			}
			$this->formules[] = $formule;
		}
		return $this;
	}
	
	public function getRestaurant () {
		$sql = "SELECT id_restaurant FROM menus WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		return $value['id_restaurant'];
	}
	
	public function getByRestaurant ($id_restaurant) {
		$sql = "SELECT id, nom, prix, temps_preparation, commentaire FROM menus WHERE id_restaurant = :id Order by nom";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$restaurants = $stmt->fetchAll();
		$list = array();
		foreach ($restaurants as $key => $value) {
			$menu = new Model_Menu(false);
			$menu->id = $value['id'];
			$menu->nom = $value['nom'];
			$menu->prix = $value['prix'];
			$menu->temps_preparation = $value['temps_preparation'];
			$menu->commentaire = $value['commentaire'];
			$list[] = $menu;
		}
		return $list;
	}
	
	public function getCategories ($id_formule) {
		$sql = "SELECT id, nom, quantite
			FROM menu_categorie
			WHERE id_formule = :id_formule AND id_menu = :id_menu";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_formule", $id_formule);
		$stmt->bindValue(":id_menu", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function addCategorie ($categorie) {
		$sql = "INSERT INTO menu_categorie (id_menu, id_formule, nom, quantite) VALUES (:menu, :formule, :categorie, :quantite)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":menu", $this->id);
		$stmt->bindValue(":formule", $this->id_formule);
		$stmt->bindValue(":categorie", $categorie->nom);
		$stmt->bindValue(":quantite", $categorie->quantite);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$categorie->id = $this->db->lastInsertId();	
	}
	
	public function addContenuToMenu($id_categorie, $contenu, $obligatoire, $supplement, $accompagnement, $commentaire) {
		$sql = "INSERT INTO menu_contenu (id_menu_categorie, id_carte, obligatoire, limite_supplement, limite_accompagnement, commentaire)
		VALUES (:categorie, :carte, :obligatoire, :supplement, :accompagnement, :commentaire)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":categorie", $id_categorie);
		$stmt->bindValue(":carte", $contenu);
		$stmt->bindValue(":obligatoire", $obligatoire);
		$stmt->bindValue(":supplement", $supplement);
		$stmt->bindValue(":accompagnement", $accompagnement);
		$stmt->bindValue(":commentaire", $commentaire);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getLogo ($id_restaurant) {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$id_restaurant)) {
			if (file_exists($logoDirectory.$id_restaurant.'/menus/'.$this->id.'.png')) {
				$this->logo = $imgPath.$id_restaurant.'/menus/'.$this->id.'.png';
			} else if (file_exists($logoDirectory.$id_restaurant.'/menus/'.$this->id.'.jpg')) {
				$this->logo = $imgPath.$id_restaurant.'/menus/'.$this->id.'.jpg';
			} else if (file_exists($logoDirectory.$id_restaurant.'/menus/'.$this->id.'.gif')) {
				$this->logo = $imgPath.$id_restaurant.'/menus/'.$this->id.'.gif';
			} else {
				$this->logo = $imgPath.'default/cloche.jpg';
			}
		} else {
			$this->logo = $imgPath.'default/cloche.jpg';
		}
	}
}