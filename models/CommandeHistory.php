<?php

class Model_Commande_History extends Model_Template {
	
	private $id;
	private $uid;
	private $client;
	private $rue;
	private $ville;
	private $code_postal;
	private $prix;
	private $note;
	private $carte;
	private $cartes;
	private $menus;
	private $date_commande;
	private $heure_souhaite;
	private $minute_souhaite;
	private $livreur;
	private $restaurant;
	private $etape;
	private $etapeLibelle;
	private $is_modif;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->id = -1;
		$this->carte = array();
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
	
	public function save ($commande) {
		$sql = "INSERT INTO commande_history (id_commande, id_user, nom_user, prenom_user, email_user, rue_user, ville_user, code_postal_user,
		rue_commande, ville_commande, code_postal_commande, latitude_commande, longitude_commande, telephone_commande, id_livreur, nom_livreur,
		prenom_livreur, login_livreur, id_restaurant, nom_restaurant, rue_restaurant, ville_restaurant, code_postal_restaurant, telephone_restaurant,
		latitude_restaurant, longitude_restaurant, date_commande, heure_souhaite, minute_souhaite, prix, prix_livraison, distance, 
		date_validation_restaurant, date_fin_preparation_restaurant, date_recuperation_livreur, date_livraison, etape, note, commentaire)
		VALUE (:id_commande, :id_user, :nom_user, :prenom_user, :email_user, :rue_user, :ville_user, :code_postal_user,
		:rue_commande, :ville_commande, :code_postal_commande, :latitude_commande, :longitude_commande, :telephone_commande, :id_livreur, :nom_livreur,
		:prenom_livreur, :login_livreur, :id_restaurant, :nom_restaurant, :rue_restaurant, :ville_restaurant, :code_postal_restaurant, :telephone_restaurant,
		:latitude_restaurant, :longitude_restaurant, :date_commande, :heure_souhaite, :minute_souhaite, :prix, :prix_livraison, :distance, 
		:date_validation_restaurant, :date_fin_preparation_restaurant, :date_recuperation_livreur, :date_livraison, :etape, :note, :commentaire)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_commande", $commande->id);
		$stmt->bindValue(":id_user", $commande->client->id);
		$stmt->bindValue(":nom_user", $commande->client->nom);
		$stmt->bindValue(":prenom_user", $commande->client->prenom);
		$stmt->bindValue(":email_user", $commande->client->email);
		$stmt->bindValue(":rue_user", $commande->client->rue);
		$stmt->bindValue(":ville_user", $commande->client->ville);
		$stmt->bindValue(":code_postal_user", $commande->client->code_postal);
		$stmt->bindValue(":rue_commande", $commande->rue);
		$stmt->bindValue(":ville_commande", $commande->ville);
		$stmt->bindValue(":code_postal_commande", $commande->code_postal);
		$stmt->bindValue(":latitude_commande", $commande->latitude);
		$stmt->bindValue(":longitude_commande", $commande->longitude);
		$stmt->bindValue(":telephone_commande", $commande->telephone);
		$stmt->bindValue(":id_livreur", $commande->livreur->id);
		$stmt->bindValue(":nom_livreur", $commande->livreur->nom);
		$stmt->bindValue(":prenom_livreur", $commande->livreur->prenom);
		$stmt->bindValue(":login_livreur", $commande->livreur->login);
		$stmt->bindValue(":id_restaurant", $commande->restaurant->id);
		$stmt->bindValue(":nom_restaurant", $commande->restaurant->nom);
		$stmt->bindValue(":rue_restaurant", $commande->restaurant->rue);
		$stmt->bindValue(":ville_restaurant", $commande->restaurant->ville);
		$stmt->bindValue(":code_postal_restaurant", $commande->restaurant->code_postal);
		$stmt->bindValue(":telephone_restaurant", $commande->restaurant->telephone);
		$stmt->bindValue(":latitude_restaurant", $commande->restaurant->latitude);
		$stmt->bindValue(":longitude_restaurant", $commande->restaurant->longitude);
		$stmt->bindValue(":date_commande", $commande->date_commande);
		$stmt->bindValue(":heure_souhaite", $commande->heure_souhaite);
		$stmt->bindValue(":minute_souhaite", $commande->minute_souhaite);
		$stmt->bindValue(":prix", $commande->prix);
		$stmt->bindValue(":prix_livraison", $commande->prix_livraison);
		$stmt->bindValue(":distance", $commande->distance);
		$stmt->bindValue(":date_validation_restaurant", $commande->date_validation_restaurant);
		$stmt->bindValue(":date_fin_preparation_restaurant", $commande->date_fin_preparation_restaurant);
		$stmt->bindValue(":date_recuperation_livreur", $commande->date_recuperation_livreur);
		$stmt->bindValue(":date_livraison", $commande->date_livraison);
		$stmt->bindValue(":etape", $commande->etape);
		$stmt->bindValue(":note", $commande->note);
		$stmt->bindValue(":commentaire", $commande->commentaire);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$id_commande_history = $this->db->lastInsertId();
		
		foreach ($commande->menus as $menu) {
			$sql = "INSERT INTO commande_menu_history (id_commande, id_commande_menu, id_menu, nom_menu, commentaire_menu, quantite)
			VALUES (:id_commande, :id_commande_menu, :id_menu, :nom_menu, :commentaire_menu, :quantite)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_commande", $id_commande_history);
			$stmt->bindValue(":id_commande_menu", $menu->id_commande);
			$stmt->bindValue(":id_menu", $menu->id);
			$stmt->bindValue(":nom_menu", $menu->nom);
			$stmt->bindValue(":commentaire_menu", $menu->commentaire);
			$stmt->bindValue(":quantite", $menu->quantite);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$id_commande_menu = $this->db->lastInsertId();
		
			foreach ($menu->contenus as $contenu) {
				$sql = "INSERT INTO commande_contenu_history (id_commande_menu, id_contenu, id_categorie, nom_categorie, parent_categorie, parent_nom, 
				id_carte, nom_carte, commentaire_carte, contenu_obligatoire, contenu_supplement, contenu_limite_supplement, commentaire_contenu)
				VALUES (:id_commande_menu, :id_contenu, :id_categorie, :nom_categorie, :parent_categorie, :parent_nom, 
				:id_carte, :nom_carte, :commentaire_carte, :contenu_obligatoire, :contenu_supplement, :contenu_limite_supplement, :commentaire_contenu)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id_commande_menu", $id_commande_menu);
				$stmt->bindValue(":id_contenu", $contenu->id);
				$stmt->bindValue(":id_categorie", $contenu->categorie->id);
				$stmt->bindValue(":nom_categorie", $contenu->categorie->nom);
				$stmt->bindValue(":parent_categorie", $contenu->categorie->parent_cat->id);
				$stmt->bindValue(":parent_nom", $contenu->categorie->parent_cat->nom);
				$stmt->bindValue(":id_carte", $contenu->carte->id);
				$stmt->bindValue(":nom_carte", $contenu->carte->nom);
				$stmt->bindValue(":commentaire_carte", $contenu->carte->commentaire);
				$stmt->bindValue(":contenu_obligatoire", $contenu->obligatoire);
				$stmt->bindValue(":contenu_supplement", $contenu->supplement);
				$stmt->bindValue(":contenu_limite_supplement", $contenu->limite_supplement);
				$stmt->bindValue(":commentaire_contenu", $contenu->commentaire);
				if (!$stmt->execute()) {
					var_dump($stmt->errorInfo());
					return false;
				}
			}
		}
		
		foreach ($commande->cartes as $carte) {
			$sql = "INSERT INTO commande_carte_history (id_commande, quantite, id_carte, nom_carte, id_categorie, nom_categorie, parent_categorie, parent_nom, 
			commentaire_carte)
			VALUES (:id_commande, :quantite, :id_carte, :nom_carte, :id_categorie, :nom_categorie, :parent_categorie, :parent_nom, :commentaire_carte)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_commande", $id_commande_history);
			$stmt->bindValue(":quantite", $carte->quantite);
			$stmt->bindValue(":id_carte", $carte->id);
			$stmt->bindValue(":nom_carte", $carte->nom);
			$stmt->bindValue(":id_categorie", $carte->categorie->id);
			$stmt->bindValue(":nom_categorie", $carte->categorie->nom);
			$stmt->bindValue(":parent_categorie", $carte->categorie->parent_categorie->id);
			$stmt->bindValue(":parent_nom", $carte->categorie->parent_categorie->nom);
			$stmt->bindValue(":commentaire_carte", $carte->commentaire);
			if (!$stmt->execute()) {
				var_dump($stmt->errorInfo());
				return false;
			}
			$id_commande_carte = $this->db->lastInsertId();
		
			foreach ($carte->supplements as $supplement) {
				$sql = "INSERT INTO commande_carte_supplement_history (id_commande_carte, id_supplement, nom_supplement, prix_supplement, commentaire_supplement)
				VALUES (:id_commande_carte, id_supplement, nom_supplement, prix_supplement, commentaire_supplement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id_commande_carte", $id_commande_carte);
				$stmt->bindValue(":id_supplement", $supplement->id);
				$stmt->bindValue(":nom_supplement", $supplement->nom);
				$stmt->bindValue(":prix_supplement", $supplement->prix);
				$stmt->bindValue(":commentaire_supplement", $supplement->commentaire);
				if (!$stmt->execute()) {
					var_dump($stmt->errorInfo());
					return false;
				}
			}
		}
		return true;
	}
	
	/*
	* Récupère les commandes livreur
	*/
	public function loadCommandeLivreur () {
		$sql = "SELECT id_commande, id_user, nom_user, prenom_user, date_commande, prix, id_restaurant, nom_restaurant, ville_restaurant, 
		code_postal_restaurant, note
		FROM commande_history
		WHERE id_livreur = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande();
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->prix = $c["prix"];
			$commande->note = $c["note"];
			$commande->client = new Model_User(false);
			$commande->client->id = $c['id_user'];
			$commande->client->nom = $c['nom_user'];
			$commande->client->prenom = $c['prenom_user'];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom_restaurant"];
			$restaurant->ville = $c["ville_restaurant"];
			$restaurant->code_postal = $c["code_postal_restaurant"];
			$commande->restaurant = $restaurant;
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function getTotal () {
		$sql = "SELECT COUNT(*) AS total_commande, SUM(prix) + SUM(prix_livraison) AS total_prix FROM commande_history";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		return $value;
	}
	
	public function getTotalByLivreur () {
		$sql = "SELECT id_livreur, login_livreur AS nom, COUNT(*) AS total_commande, SUM(prix) + SUM(prix_livraison) AS total_prix FROM commande_history
		GROUP BY id_livreur";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByRestaurant () {
		$sql = "SELECT id_restaurant, nom_restaurant AS nom, COUNT(*) AS total_commande, SUM(prix) + SUM(prix_livraison) AS total_prix FROM commande_history 
		GROUP BY id_restaurant";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByClient () {
		$sql = "SELECT id_user, nom_user AS nom, prenom_user AS prenom, COUNT(*) AS total_commande, SUM(prix) + SUM(prix_livraison) AS total_prix FROM commande_history 
		GROUP BY id_user";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByVille () {
		$sql = "SELECT ville_commande AS nom, code_postal_commande AS cp, COUNT(*) AS total_commande, SUM(prix) + SUM(prix_livraison) AS total_prix FROM commande_history
		GROUP BY ville_commande";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $stmt->fetchAll();;
	}
	
}