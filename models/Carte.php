<?php

class Model_Carte extends Model_Template {
	
	private $id;
	private $id_categorie;
	private $nom;
	private $prix;
	private $quantite;
	private $ordre;
	private $commentaire;
	private $is_visible;
	private $categorie;
	private $horaires;
	private $formats;
	private $limite_supplement;
	private $supplements;
	private $accompagnements;
	private $options;
	private $logo;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->id = -1;
		$this->horaires = array();
		$this->formats = array();
		$this->supplements = array();
		$this->accompagnements = array();
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
	
	public function addFormat ($format) {
		$this->formats[] = $format;
	}
	
	public function addHoraire ($horaire) {
		$this->horaires[] = $horaire;
	}
	
	public function addSupplement ($supplement) {
		$this->supplements[] = $supplement;
	}
	
	public function addAccompagnement ($accompagnement) {
		$this->accompagnements[] = $accompagnement;
	}
	
	public function addOption ($option) {
		$this->options[] = $option;
	}
	
	public function save () {
		if ($this->id == -1) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	private function insert () {
		$sql = "INSERT INTO carte (nom, id_categorie, is_visible, ordre, commentaire) 
		VALUES (:nom, :categorie, :visible, :ordre, :commentaire)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":categorie", $this->id_categorie);
		$stmt->bindValue(":visible", $this->is_visible);
		$stmt->bindValue(":ordre", $this->ordre);
		$stmt->bindValue(":commentaire", $this->commentaire);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$this->id = $this->db->lastInsertId();
		foreach ($this->formats as $format) {
			$sql = "INSERT INTO carte_format (id_carte, id_format, prix, temps_preparation) 
			VALUES (:carte, :format, :prix, :temps_preparation)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":format", $format->id);
			$stmt->bindValue(":prix", $format->prix);
			$stmt->bindValue(":temps_preparation", $format->temps_preparation);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		foreach ($this->accompagnements as $accompagnement) {
			$sql = "INSERT INTO carte_accompagnement (id_carte, limite, id_categorie) VALUES (:carte, :limite, :categorie)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":limite", $accompagnement->limite);
			$stmt->bindValue(":categorie", $accompagnement->id_categorie);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$accompagnement->id = $this->db->lastInsertId();
			foreach ($accompagnement->cartes as $carte) {
				$sql = "INSERT INTO carte_accompagnement_contenu (id_carte_accompagnement, id_accompagnement) VALUES (:id, :carte)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $accompagnement->id);
				$stmt->bindValue(":carte", $carte->id);
				if (!$stmt->execute()) {
					var_dump($stmt->errorInfo());
					return false;
				}
			}
		}
		foreach ($this->supplements as $supplement) {
			$sql = "INSERT INTO carte_supplement (id_carte, id_supplement) VALUES (:carte, :supplement)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":supplement", $supplement->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		foreach ($this->options as $option) {
			$sql = "INSERT INTO carte_option (id_carte, id_option) VALUES (:carte, :option)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":option", $option->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		foreach ($this->horaires as $horaire) {
			$sql = "INSERT INTO carte_disponibilite (id_carte, id_horaire) VALUES (:carte, :horaire)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":horaire", $horaire->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		return true;
	}
	
	private function update () {
		$sql = "UPDATE carte SET nom = :nom, is_visible = :visible, ordre = :ordre, commentaire = :commentaire WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":visible", $this->is_visible);
		$stmt->bindValue(":ordre", $this->ordre);
		$stmt->bindValue(":commentaire", $this->commentaire);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$sql = "DELETE FROM carte_format WHERE id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		foreach ($this->formats as $format) {
			$sql = "INSERT INTO carte_format (id_carte, id_format, prix, temps_preparation) 
			VALUES (:carte, :format, :prix, :temps_preparation)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":format", $format->id);
			$stmt->bindValue(":prix", $format->prix);
			$stmt->bindValue(":temps_preparation", $format->temps_preparation);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		$sql = "SELECT id FROM carte_accompagnement WHERE id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$accompagnements = $stmt->fetchAll();
		foreach ($accompagnements as $acc) {
			$sql = "DELETE FROM carte_accompagnement_contenu WHERE id_carte_accompagnement = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $acc["id"]);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		$sql = "DELETE FROM carte_accompagnement WHERE id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		foreach ($this->accompagnements as $accompagnement) {
			$sql = "INSERT INTO carte_accompagnement (id_carte, limite, id_categorie) VALUES (:carte, :limite, :categorie)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":limite", $accompagnement->limite);
			$stmt->bindValue(":categorie", $accompagnement->id_categorie);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$accompagnement->id = $this->db->lastInsertId();
			foreach ($accompagnement->cartes as $carte) {
				$sql = "INSERT INTO carte_accompagnement_contenu (id_carte_accompagnement, id_accompagnement) VALUES (:id, :carte)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $accompagnement->id);
				$stmt->bindValue(":carte", $carte->id);
				if (!$stmt->execute()) {
					var_dump($stmt->errorInfo());
					return false;
				}
			}
		}
		foreach ($this->supplements as $supplement) {
			$sql = "INSERT INTO carte_supplement (id_carte, id_supplement) VALUES (:carte, :supplement)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":supplement", $supplement->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		foreach ($this->options as $option) {
			$sql = "INSERT INTO carte_option (id_carte, id_option) VALUES (:carte, :option)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":option", $option->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		foreach ($this->horaires as $horaire) {
			$sql = "INSERT INTO carte_disponibilite (id_carte, id_horaire) VALUES (:carte, :horaire)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":carte", $this->id);
			$stmt->bindValue(":horaire", $horaire->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
		}
		return true;
	}
	
	public function load () {
		$sql = "SELECT nom, is_visible, commentaire, limite_supplement FROM carte WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_Carte : load", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		$this->commentaire = $value['commentaire'];
		$this->is_visible = $value['is_visible'];
		$this->limite_supplement = $value['limite_supplement'];
		
		$sql = "SELECT format.id, format.nom, cf.prix, cf.temps_preparation 
		FROM carte_format cf
		JOIN restaurant_format format ON format.id = cf.id_format
		WHERE cf.id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			echo "formats  : ";
			var_dump($stmt->errorInfo());
			return false;
		}
		$formats = $stmt->fetchAll();
		foreach ($formats as $form) {
			$format = new Model_Format();
			$format->id = $form["id"];
			$format->nom = $form["nom"];
			$format->prix = $form["prix"];
			$format->temps_preparation = $form["temps_preparation"];
			$this->formats[] = $format;
		}
		
		$sql = "SELECT id, limite, id_categorie FROM carte_accompagnement WHERE id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$accompagnements = $stmt->fetchAll();
		foreach ($accompagnements as $acc) {
			$accompagnement = new Model_Accompagnement();
			$accompagnement->id = $acc["id"];
			$accompagnement->limite = $acc["limite"];
			$accompagnement->id_categorie = $acc["id_categorie"];
			
			$sql = "SELECT carte.id, carte.nom FROM carte JOIN carte_accompagnement_contenu cac WHERE carte.id = cac.id_accompagnement";
			$stmt = $this->db->prepare($sql);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$cartes = $stmt->fetchAll();
			foreach ($cartes as $c) {
				$carte = new Model_Carte();
				$carte->id = $c["id"];
				$carte->nom = $c["nom"];
				$accompagnement->addCarte($carte);
			}
			
			$this->accompagnements[] = $accompagnement;
		}
		
		$sql = "SELECT supp.id, supp.nom, supp.prix, supp.commentaire 
		FROM carte_supplement cs
		JOIN supplements supp ON supp.id = cs.id_supplement
		WHERE cs.id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			echo "supplements  : ";
			var_dump($stmt->errorInfo());
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
		
		$sql = "SELECT ro.id, ro.nom 
		FROM carte_option co
		JOIN restaurant_option ro ON ro.id = co.id_option
		WHERE co.id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$options = $stmt->fetchAll();
		foreach ($options as $opt) {
			$option = new Model_Option();
			$option->id = $opt["id"];
			$option->nom = $opt["nom"];
			
			$sql = "SELECT id, nom FROM restaurant_option_value WHERE id_option = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $option->id);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$values = $stmt->fetchAll();
			foreach ($values as $value) {
				$optionValue = new Model_Option_Value();
				$optionValue->id = $value["id"];
				$optionValue->nom = $value["nom"];
				$option->addValue($optionValue);
			}
			
			$this->options[] = $option;
		}
		return $this;
	}
	
	public function getSupplements () {
		$sql = "SELECT supp.id, supp.nom, supp.prix, supp.commentaire 
		FROM carte_supplement cs
		JOIN supplements supp ON supp.id = cs.id_supplement
		WHERE cs.id_carte = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
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
	
	public function getLogo ($id_restaurant) {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$id_restaurant)) {
			if (file_exists($logoDirectory.$id_restaurant.'/contenus/'.$this->id.'.png')) {
				$this->logo = $imgPath.$id_restaurant.'/contenus/'.$this->id.'.png';
			} else if (file_exists($logoDirectory.$id_restaurant.'/contenus/'.$this->id.'.jpg')) {
				$this->logo = $imgPath.$id_restaurant.'/contenus/'.$this->id.'.jpg';
			} else if (file_exists($logoDirectory.$id_restaurant.'/contenus/'.$this->id.'.gif')) {
				$this->logo = $imgPath.$id_restaurant.'/contenus/'.$this->id.'.gif';
			} else {
				$this->logo = $imgPath.'default/cloche.jpg';
			}
		} else {
			$this->logo = $imgPath.'default/cloche.jpg';
		}
	}
}