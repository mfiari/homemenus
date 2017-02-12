<?php

class Model_Categorie extends Model_Template {
	
	protected $id;
	private $parent_categorie;
	private $id_restaurant;
	private $nom;
	private $quantite;
	private $logo;
	private $ordre;
	private $contenus;
	private $nb_child;
	private $nb_carte;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->id = -1;
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
		if ($this->id == -1) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	public function insert () {
		$sql = "INSERT INTO restaurant_categorie (parent_categorie, id_restaurant, nom, ordre) 
		VALUES (:parent, :restaurant, :nom, :ordre)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":parent", $this->parent_categorie);
		$stmt->bindValue(":restaurant", $this->id_restaurant);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":ordre", $this->ordre);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function update () {
		$sql = "UPDATE restaurant_categorie SET nom = :nom WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":nom", $this->nom);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function deleted () {
		$sql = "UPDATE restaurant_categorie SET deleted = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function remove () {
		$sql = "DELETE FROM restaurant_categorie WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function load () {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE id = :id";
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
	
	public function getParentContenu ($id_restaurant, $directory = "default") {
		$sql = "SELECT rc.id, rc.nom, COUNT(rc2.id) AS nb_child, COUNT(carte.id) AS nb_carte
		FROM restaurant_categorie rc
		LEFT JOIN restaurant_categorie rc2 ON rc2.parent_categorie = rc.id
		LEFT JOIN carte ON carte.id_categorie = rc.id AND carte.is_visible = 1
		WHERE rc.id_restaurant = :id AND rc.parent_categorie = 0 
		GROUP BY rc.id
		ORDER BY rc.ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$categories = $stmt->fetchAll();
		$list = array();
		foreach ($categories as $c) {
			$categorie = new Model_Categorie(false);
			$categorie->id = $c["id"];
			$categorie->nom = $c["nom"];
			$categorie->nb_child = $c["nb_child"];
			$categorie->nb_carte = $c["nb_carte"];
			$categorie->getLogo($id_restaurant, $directory);
			$list[] = $categorie;
		}
		return $list;
	}
	
	public function getChildren () {
		$sql = "SELECT id, nom FROM restaurant_categorie WHERE parent_categorie = :id ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$categories = $stmt->fetchAll();
		$list = array();
		foreach ($categories as $c) {
			$categorie = new Model_Categorie(true, $this->db);
			$categorie->id = $c["id"];
			$categorie->nom = $c["nom"];
			$list[] = $categorie;
		}
		return $list;
	}
	
	public function loadContenu ($id_restaurant, $directory = "default") {
		$sql = "SELECT carte.id, carte.nom, carte.commentaire, (SELECT MIN(cf.prix) FROM carte_format cf WHERE cf.id_carte = carte.id) AS prix,
		(SELECT (SUM(note) / COUNT(*)) FROM commentaire_carte WHERE id_carte = carte.id) AS note,
		(SELECT COUNT(*) FROM commentaire_carte WHERE id_carte = carte.id) AS vote,
		(SELECT COUNT(*) FROM commentaire_carte WHERE id_carte = carte.id AND validation = 1) AS nb_commentaire
		FROM carte
		WHERE id_categorie = :id AND is_visible = 1 GROUP BY carte.id ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$contenus = $stmt->fetchAll();
		foreach ($contenus as $c) {
			$contenu = new Model_Contenu(false);
			$contenu->id = $c["id"];
			$contenu->nom = $c["nom"];
			$contenu->prix = $c["prix"];
			$contenu->commentaire = $c["commentaire"];
			$contenu->getLogo($id_restaurant, $directory);
			
			$commentaire = new Model_Commentaire();
			$commentaire->note = $c['note'];
			$commentaire->vote = $c['vote'];
			$commentaire->commentaire = $c['nb_commentaire'];
			
			$contenu->supplement = $commentaire;
			
			$this->contenus[] = $contenu;
		}
		return $this;
	}
	
	public function getContenu ($id_restaurant) {
		$sql = "SELECT id, nom, stock FROM carte WHERE id_categorie = :id AND deleted = 0 ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$contenus = $stmt->fetchAll();
		foreach ($contenus as $c) {
			$contenu = new Model_Contenu(false);
			$contenu->id = $c["id"];
			$contenu->nom = $c["nom"];
			$contenu->stock = $c["stock"];
			$contenu->getLogo($id_restaurant);
			$this->contenus[] = $contenu;
		}
		return $this;
	}
	
	private function getLogo ($id_restaurant, $directory = "default") {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$id_restaurant)) {
			if (file_exists($logoDirectory.$id_restaurant.'/categories/'.$directory.'/'.$this->id.'.png')) {
				$this->logo = $imgPath.$id_restaurant.'/categories/'.$directory.'/'.$this->id.'.png';
			} else if (file_exists($logoDirectory.$id_restaurant.'/categories/'.$directory.'/'.$this->id.'.jpg')) {
				$this->logo = $imgPath.$id_restaurant.'/categories/'.$directory.'/'.$this->id.'.jpg';
			} else if (file_exists($logoDirectory.$id_restaurant.'/categories/'.$directory.'/'.$this->id.'.gif')) {
				$this->logo = $imgPath.$id_restaurant.'/categories/'.$directory.'/'.$this->id.'.gif';
			} else {
				$this->logo = $imgPath.'default/cloche.jpg';
			}
		} else {
			$this->logo = $imgPath.'default/cloche.jpg';
		}
	}
}