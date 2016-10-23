<?php

class Model_Commande_History extends Model_Template {
	
	private $id;
	private $id_commande;
	private $uid;
	private $client;
	private $rue;
	private $ville;
	private $code_postal;
	private $prix;
	private $prix_livraison;
	private $note;
	private $commentaire;
	private $commentaire_anonyme;
	private $validation_commentaire;
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
	private $date_validation_livreur;
	private $date_validation_restaurant;
	private $date_fin_preparation_restaurant;
	private $date_recuperation_livreur;
	private $date_livraison;
	
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
		rue_commande, ville_commande, code_postal_commande, complement_commande, latitude_commande, longitude_commande, telephone_commande, id_livreur, 
		nom_livreur, prenom_livreur, login_livreur, id_restaurant, nom_restaurant, rue_restaurant, ville_restaurant, code_postal_restaurant, telephone_restaurant,
		latitude_restaurant, longitude_restaurant, date_commande, heure_souhaite, minute_souhaite, prix, prix_livraison, part_restaurant, distance, 
		date_validation_restaurant, date_fin_preparation_restaurant, date_recuperation_livreur, date_livraison, etape, note, commentaire, is_premium, 
		id_code_promo, code_promo, description_code_promo, date_debut_code_promo, date_fin_code_promo, publique_code_promo, sur_restaurant_code_promo, 
		type_reduc_code_promo, sur_prix_livraison_code_promo, valeur_prix_livraison_code_promo, sur_prix_total_code_promo, valeur_prix_total_code_promo, 
		pourcentage_prix_total_code_promo)
		VALUE (:id_commande, :id_user, :nom_user, :prenom_user, :email_user, :rue_user, :ville_user, :code_postal_user,
		:rue_commande, :ville_commande, :code_postal_commande, :complement_commande, :latitude_commande, :longitude_commande, :telephone_commande, :id_livreur, 
		:nom_livreur, :prenom_livreur, :login_livreur, :id_restaurant, :nom_restaurant, :rue_restaurant, :ville_restaurant, :code_postal_restaurant, 
		:telephone_restaurant, :latitude_restaurant, :longitude_restaurant, :date_commande, :heure_souhaite, :minute_souhaite, :prix, :prix_livraison, 
		:part_restaurant, :distance, :date_validation_restaurant, :date_fin_preparation_restaurant, :date_recuperation_livreur, :date_livraison, :etape, :note, 
		:commentaire, :is_premium, :id_code_promo, :code_promo, :description_code_promo, :date_debut_code_promo, :date_fin_code_promo, :publique_code_promo, 
		:sur_restaurant_code_promo, :type_reduc_code_promo, :sur_prix_livraison_code_promo, :valeur_prix_livraison_code_promo, :sur_prix_total_code_promo, 
		:valeur_prix_total_code_promo, :pourcentage_prix_total_code_promo)";
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
		$stmt->bindValue(":complement_commande", $commande->complement_commande);
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
		$stmt->bindValue(":part_restaurant", $commande->part_restaurant);
		$stmt->bindValue(":distance", $commande->distance);
		$stmt->bindValue(":date_validation_restaurant", $commande->date_validation_restaurant);
		$stmt->bindValue(":date_fin_preparation_restaurant", $commande->date_fin_preparation_restaurant);
		$stmt->bindValue(":date_recuperation_livreur", $commande->date_recuperation_livreur);
		$stmt->bindValue(":date_livraison", $commande->date_livraison);
		$stmt->bindValue(":etape", $commande->etape);
		$stmt->bindValue(":note", $commande->note);
		$stmt->bindValue(":commentaire", $commande->commentaire);
		$stmt->bindValue(":is_premium", $commande->is_premium);
		$stmt->bindValue(":id_code_promo", $commande->codePromo->id);
		$stmt->bindValue(":code_promo", $commande->codePromo->code);
		$stmt->bindValue(":description_code_promo", $commande->codePromo->description);
		$stmt->bindValue(":date_debut_code_promo", $commande->codePromo->date_debut);
		$stmt->bindValue(":date_fin_code_promo", $commande->codePromo->date_fin);
		$stmt->bindValue(":publique_code_promo", $commande->codePromo->publique);
		$stmt->bindValue(":sur_restaurant_code_promo", $commande->codePromo->sur_restaurant);
		$stmt->bindValue(":type_reduc_code_promo", $commande->codePromo->type_reduc);
		$stmt->bindValue(":sur_prix_livraison_code_promo", $commande->codePromo->sur_prix_livraison);
		$stmt->bindValue(":valeur_prix_livraison_code_promo", $commande->codePromo->valeur_prix_livraison);
		$stmt->bindValue(":sur_prix_total_code_promo", $commande->codePromo->sur_prix_total);
		$stmt->bindValue(":valeur_prix_total_code_promo", $commande->codePromo->valeur_prix_total);
		$stmt->bindValue(":pourcentage_prix_total_code_promo", $commande->codePromo->pourcentage_prix_total);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$id_commande_history = $this->db->lastInsertId();
		
		foreach ($commande->menus as $menu) {
			$sql = "INSERT INTO commande_menu_history 
			(id_commande, id_menu, nom_menu, commentaire_menu, id_format, nom_format, id_formule, nom_formule quantite, prix, temps_preparation)
			VALUES (:id_commande, :id_menu, :nom_menu, :commentaire_menu, :id_format, :nom_format, :id_formule, :nom_formule, :quantite, :prix, :temps_preparation)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_commande", $id_commande_history);
			$stmt->bindValue(":id_menu", $menu->id);
			$stmt->bindValue(":nom_menu", $menu->nom);
			$stmt->bindValue(":commentaire_menu", $menu->commentaire);
			$stmt->bindValue(":id_format", $menu->getFormat(0)->id);
			$stmt->bindValue(":nom_format", $menu->getFormat(0)->nom);
			$stmt->bindValue(":id_formule", $menu->getFormule(0)->id);
			$stmt->bindValue(":nom_formule", $menu->getFormule(0)->nom);
			$stmt->bindValue(":quantite", $menu->quantite);
			$stmt->bindValue(":prix", $menu->prix);
			$stmt->bindValue(":temps_preparation", $menu->temps_preparation);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_menu = $this->db->lastInsertId();
		
			foreach ($menu->contenus as $contenu) {
				$sql = "INSERT INTO commande_menu_contenu_history (id_commande_menu, id_categorie, nom_categorie, parent_categorie, parent_nom, 
				id_carte, nom_carte, commentaire_carte, contenu_obligatoire, contenu_limite_supplement, contenu_limite_accompagnement, commentaire_contenu)
				VALUES (:id_commande_menu, :id_categorie, :nom_categorie, :parent_categorie, :parent_nom, 
				:id_carte, :nom_carte, :commentaire_carte, :contenu_obligatoire, :contenu_limite_supplement, :contenu_limite_accompagnement, :commentaire_contenu)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id_commande_menu", $id_commande_menu);
				$stmt->bindValue(":id_categorie", $contenu->categorie->id);
				$stmt->bindValue(":nom_categorie", $contenu->categorie->nom);
				$stmt->bindValue(":parent_categorie", $contenu->categorie->parent_cat->id);
				$stmt->bindValue(":parent_nom", $contenu->categorie->parent_cat->nom);
				$stmt->bindValue(":id_carte", $contenu->carte->id);
				$stmt->bindValue(":nom_carte", $contenu->carte->nom);
				$stmt->bindValue(":commentaire_carte", $contenu->carte->commentaire);
				$stmt->bindValue(":contenu_obligatoire", $contenu->obligatoire);
				$stmt->bindValue(":contenu_limite_supplement", $contenu->limite_supplement);
				$stmt->bindValue(":contenu_limite_accompagnement", $contenu->limite_accompagnement);
				$stmt->bindValue(":commentaire_contenu", $contenu->commentaire);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		}
		
		foreach ($commande->cartes as $carte) {
			$sql = "INSERT INTO commande_carte_history (id_commande, quantite, id_format, nom_format, prix, temps_preparation, id_carte, nom_carte, id_categorie, 
			nom_categorie, parent_categorie, parent_nom, commentaire_carte)
			VALUES (:id_commande, :quantite, :id_format, :nom_format, :prix, :temps_preparation, :id_carte, :nom_carte, :id_categorie, :nom_categorie, 
			:parent_categorie, :parent_nom, :commentaire_carte)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id_commande", $id_commande_history);
			$stmt->bindValue(":quantite", $carte->quantite);
			$stmt->bindValue(":id_format", $carte->getFormat(0)->id);
			$stmt->bindValue(":nom_format", $carte->getFormat(0)->nom);
			$stmt->bindValue(":prix", $carte->prix);
			$stmt->bindValue(":temps_preparation", $carte->temps_preparation);
			$stmt->bindValue(":id_carte", $carte->id);
			$stmt->bindValue(":nom_carte", $carte->nom);
			$stmt->bindValue(":id_categorie", $carte->categorie->id);
			$stmt->bindValue(":nom_categorie", $carte->categorie->nom);
			$stmt->bindValue(":parent_categorie", $carte->categorie->parent_categorie->id);
			$stmt->bindValue(":parent_nom", $carte->categorie->parent_categorie->nom);
			$stmt->bindValue(":commentaire_carte", $carte->commentaire);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_carte = $this->db->lastInsertId();
		
			foreach ($carte->supplements as $supplement) {
				$sql = "INSERT INTO commande_carte_supplement_history (id_commande_carte, id_supplement, nom_supplement, prix_supplement, commentaire_supplement)
				VALUES (:id_commande_carte, :id_supplement, :nom_supplement, :prix_supplement, :commentaire_supplement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id_commande_carte", $id_commande_carte);
				$stmt->bindValue(":id_supplement", $supplement->id);
				$stmt->bindValue(":nom_supplement", $supplement->nom);
				$stmt->bindValue(":prix_supplement", $supplement->prix);
				$stmt->bindValue(":commentaire_supplement", $supplement->commentaire);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		
			foreach ($carte->accompagnements as $accompagnement) {
				$sql = "INSERT INTO commande_carte_accompagnement_history (id_commande_carte, id_accompagnement, nom_accompagnement, id_categorie, nom_categorie, 
				parent_categorie, parent_nom, commentaire_accompagnement)
				VALUES (:id_commande_carte, :id_accompagnement, :nom_accompagnement, :id_categorie, :nom_categorie, :parent_categorie, :parent_nom, 
				:commentaire_accompagnement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id_commande_carte", $id_commande_carte);
				$stmt->bindValue(":id_accompagnement", $accompagnement->id);
				$stmt->bindValue(":nom_accompagnement", $accompagnement->nom);
				$stmt->bindValue(":id_categorie", $accompagnement->categorie->id);
				$stmt->bindValue(":nom_categorie", $accompagnement->categorie->nom);
				$stmt->bindValue(":parent_categorie", $accompagnement->categorie->parent_cat->id);
				$stmt->bindValue(":parent_nom", $accompagnement->categorie->parent_cat->nom);
				$stmt->bindValue(":commentaire_accompagnement", $accompagnement->commentaire);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		
			foreach ($carte->options as $option) {
				foreach ($option->values as $optionValue) {
					$sql = "INSERT INTO commande_carte_option_history (id_commande_carte, id_option, nom_option, id_value, nom_value)
					VALUES (:id_commande_carte, :id_option, :nom_option, :id_value, :nom_value)";
					$stmt = $this->db->prepare($sql);
					$stmt->bindValue(":id_commande_carte", $id_commande_carte);
					$stmt->bindValue(":id_option", $option->id);
					$stmt->bindValue(":nom_option", $option->nom);
					$stmt->bindValue(":id_value", $optionValue->id);
					$stmt->bindValue(":nom_value", $optionValue->nom);
					if (!$stmt->execute()) {
						writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
						return false;
					}
				}
			}
		}
		return true;
	}
	
	/*
	* Récupère les commandes livreur
	*/
	public function loadCommandeLivreur () {
		$sql = "SELECT id, id_commande, id_user, nom_user, prenom_user, ville_commande, date_commande, prix, id_restaurant, nom_restaurant, ville_restaurant, 
		code_postal_restaurant, note
		FROM commande_history
		WHERE id_livreur = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande();
			$commande->id = $c["id"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->prix = $c["prix"];
			$commande->note = $c["note"];
			$commande->ville = $c["ville_commande"];
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
	
	public function getByUser () {
		$sql = "SELECT id, id_commande, id_livreur, prenom_livreur, id_restaurant, nom_restaurant, 
		code_postal_restaurant AS cp_restaurant, ville_restaurant, date_commande, prix, prix_livraison, note
		FROM commande_history
		WHERE id_user = :uid
		ORDER BY date_commande ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande_History();
			$commande->id = $c["id"];
			$commande->id_commande = $c["id_commande"];
			$commande->date_commande = $c["date_commande"];
			$commande->prix = $c["prix"] + $c["prix_livraison"];
			$commande->note = $c["note"];
			
			$livreur = new Model_User();
			$livreur->id = $c["id_livreur"];
			$livreur->prenom = $c["prenom_livreur"];
			
			$commande->livreur = $livreur;
			
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom_restaurant"];
			$restaurant->ville = $c["ville_restaurant"];
			$restaurant->code_postal = $c["cp_restaurant"];
			
			$commande->restaurant = $restaurant;
			
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function getAll ($dateDebut, $dateFin, $page = 1, $nbItem = 20) {
		$offset = (($page -1) * $nbItem);
		
		$sql = "SELECT COUNT(*) AS nb_row
		FROM commande_history
		WHERE date_commande BETWEEN :date_debut AND :date_fin";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		$total_row = $value['nb_row'];
		
		$sql = "SELECT id, id_commande, id_user AS id_client, nom_user, prenom_user, id_livreur, login_livreur AS login, prenom_livreur, id_restaurant, nom_restaurant, 
		code_postal_restaurant AS cp_restaurant, ville_restaurant, ville_commande, date_commande, date_validation_restaurant, date_livraison, prix, prix_livraison, note
		FROM commande_history
		WHERE date_commande BETWEEN :date_debut AND :date_fin
		ORDER BY date_commande ASC
		Limit $offset, $nbItem";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande();
			$commande->id = $c["id"];
			$commande->id_commande = $c["id_commande"];
			$commande->ville = $c["ville_commande"];
			$commande->date_commande = $c["date_commande"];
			$commande->date_validation_restaurant = $c["date_validation_restaurant"];
			$commande->date_livraison = $c["date_livraison"];
			$commande->prix = $c["prix"] + $c["prix_livraison"];
			$commande->note = $c["note"];
			
			$client = new Model_User();
			$client->id = $c["id_client"];
			$client->nom = $c["nom_user"];
			$client->prenom = $c["prenom_user"];
			
			$commande->client = $client;
			
			$livreur = new Model_User();
			$livreur->id = $c["id_livreur"];
			$livreur->login = $c["login"];
			$livreur->prenom = $c["prenom_livreur"];
			
			$commande->livreur = $livreur;
			
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom_restaurant"];
			$restaurant->ville = $c["ville_restaurant"];
			$restaurant->code_postal = $c["cp_restaurant"];
			
			$commande->restaurant = $restaurant;
			
			$listCommande[] = $commande;
		}
		return array("total_rows" => $total_row, "list_commandes" => $listCommande);
	}
	
	public function load () {
		$sql = "
		SELECT 
			id_commande, id_user AS uid, nom_user AS cnom, prenom_user AS cprenom, telephone_commande AS ctel, rue_commande AS com_rue, ville_commande AS com_ville, 
			code_postal_commande AS com_cp, id_restaurant AS id_resto, nom_restaurant AS nom_resto, rue_restaurant AS rue_resto, ville_restaurant AS ville_resto, 
			code_postal_restaurant AS cp_resto, telephone_restaurant, id_livreur, prenom_livreur, date_commande, heure_souhaite, minute_souhaite, 
			date_validation_restaurant, date_fin_preparation_restaurant, date_recuperation_livreur, date_livraison, prix, prix_livraison, distance
		FROM commande_history
		WHERE id = :id";
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
		$this->id_commande = $value['id_commande'];
		$this->client = new Model_User(false);
		$this->client->id = $value['uid'];
		$this->client->nom = $value['cnom'];
		$this->client->prenom = $value['cprenom'];
		$this->client->telephone = $value['ctel'];
		$this->rue = $value['com_rue'];
		$this->ville = $value['com_ville'];
		$this->code_postal = $value['com_cp'];
		$this->id_livreur = $value['id_livreur'];
		$this->restaurant = new Model_Restaurant(false);
		$this->restaurant->id = $value['id_resto'];
		$this->restaurant->nom = $value['nom_resto'];
		$this->restaurant->rue = $value['rue_resto'];
		$this->restaurant->ville = $value['ville_resto'];
		$this->restaurant->code_postal = $value['cp_resto'];
		$this->restaurant->telephone = $value['telephone_restaurant'];
		$this->livreur = new Model_User(false);
		$this->livreur->id = $value['id_livreur'];
		$this->livreur->prenom = $value['prenom_livreur'];
		$this->date_commande = $value['date_commande'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->date_validation_restaurant = $value['date_validation_restaurant'];
		$this->date_fin_preparation_restaurant = $value['date_fin_preparation_restaurant'];
		$this->date_recuperation_livreur = $value['date_recuperation_livreur'];
		$this->date_livraison = $value['date_livraison'];
		$this->prix = $value['prix'];
		$this->prix_livraison = $value['prix_livraison'];
		$this->distance = $value['distance'];
		
		$this->cartes = array();
		
		$sql = "SELECT id_carte AS id, nom_carte AS nom, id_categorie, quantite, prix AS prix, id_format, nom_format
		FROM commande_carte_history 
		WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		foreach ($result as $c) {
			$carte = new Model_Carte(false);
			$carte->id = $c['id'];
			$carte->nom = $c['nom'];
			$carte->quantite = $c['quantite'];
			$carte->prix = $c['prix'] * $c['quantite'];
			
			$format = new Model_Format(false);
			$format->id = $c['id_format'];
			$format->nom = $c['nom_format'];
			
			$carte->addFormat($format);
			
			$sql = "SELECT id_supplement AS id, nom_supplement AS nom, prix_supplement AS prix
			FROM commande_carte_supplement_history
			WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $carte->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
		
		
		$sql = "SELECT id, id_menu, nom_menu, id_format, nom_format, quantite, prix
		FROM commande_menu_history
		WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCommandeMenu = $stmt->fetchAll();
		foreach ($listCommandeMenu as $commandeMenu) {
			$menu = new Model_Menu(false);
			$menu->id = $commandeMenu['id_menu'];
			$menu->nom = $commandeMenu['nom_menu'];
			$menu->quantite = $commandeMenu['quantite'];
			$menu->prix = $commandeMenu['prix'] * $commandeMenu['quantite'];
			
			$format = new Model_Format();
			$format->id = $commandeMenu['id_format'];
			$format->nom = $commandeMenu['nom_format'];
			
			$menu->addFormat($format);
			
			$formule = new Model_Formule();
			$formule->id = 0;
			$formule->nom = 'formule';
			
			$menu->addFormule($formule);
			
			$sql = "SELECT id, id_categorie, nom_categorie, id_carte, nom_carte
			FROM commande_menu_contenu_history 
			WHERE id_commande_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeMenu['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeContenu = $stmt->fetchAll();
			foreach ($listCommandeContenu as $commandeContenu) {
				$categorie = new Model_Categorie(false);
				$categorie->id = $commandeContenu['id_categorie'];
				$categorie->nom = $commandeContenu['nom_categorie'];
				
				$contenu = new Model_Contenu(false);
				$contenu->id = $commandeContenu['id_carte'];
				$contenu->nom = $commandeContenu['nom_carte'];
				
				$categorie->addContenu($contenu);
				
				$formule->addCategorie($categorie);
			}
			$this->menus[] = $menu;
		}
		return $this;
	}
	
	public function getTotal ($dateDebut, $dateFin) {
		$sql = "SELECT COUNT(*) AS total_commande, SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, 
		SUM(prix + prix_livraison) AS total_prix FROM commande_history
		WHERE date_commande BETWEEN :date_debut AND :date_fin";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		return $value;
	}
	
	public function getTotalByDayAndRestaurant ($dateDebut, $dateFin) {
		$sql = "SELECT WEEKDAY(date_commande) AS weekday, HOUR(date_commande) AS hour, id_restaurant, nom_restaurant, 
		COUNT(*) AS total_commande, SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, 
		SUM(prix + prix_livraison) AS total_prix FROM commande_history
		WHERE date_commande BETWEEN :date_debut AND :date_fin
		GROUP BY weekday, hour, id_restaurant
		ORDER BY weekday, hour, id_restaurant";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getTotalByMonth ($dateDebut, $dateFin) {
		$sql = "SELECT MONTH(date_commande) AS month, COUNT(*) AS total_commande, SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, 
		SUM(prix + prix_livraison) AS total_prix FROM commande_history
		WHERE date_commande BETWEEN :date_debut AND :date_fin
		GROUP BY month";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getTotalByLivreur ($dateDebut, $dateFin) {
		$sql = "SELECT id_livreur, login_livreur AS nom, COUNT(*) AS total_commande, 
		SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, SUM(prix + prix_livraison) AS total_prix 
		FROM commande_history
		WHERE date_commande BETWEEN :date_debut AND :date_fin
		GROUP BY id_livreur";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getTotalByRestaurant ($dateDebut, $dateFin) {
		$sql = "SELECT id_restaurant, nom_restaurant AS nom, COUNT(*) AS total_commande, 
		SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, SUM(prix + prix_livraison) AS total_prix 
		FROM commande_history 
		WHERE date_commande BETWEEN :date_debut AND :date_fin
		GROUP BY id_restaurant";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByClient ($dateDebut, $dateFin) {
		$sql = "SELECT id_user, nom_user AS nom, prenom_user AS prenom, COUNT(*) AS total_commande, 
		SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, SUM(prix + prix_livraison) AS total_prix 
		FROM commande_history 
		WHERE date_commande BETWEEN :date_debut AND :date_fin
		GROUP BY id_user";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByVille ($dateDebut, $dateFin) {
		$sql = "SELECT ville_commande AS nom, code_postal_commande AS cp, COUNT(*) AS total_commande, 
		SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, SUM(prix + prix_livraison) AS total_prix 
		FROM commande_history
		WHERE date_commande BETWEEN :date_debut AND :date_fin
		GROUP BY ville_commande";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function noter () {
		$sql = "UPDATE commande_history SET note = :note, commentaire = :commentaire, commentaire_anonyme = :anonyme WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":note", $this->note);
		$stmt->bindValue(":commentaire", $this->commentaire);
		$stmt->bindValue(":anonyme", $this->commentaire_anonyme);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getAllCommentaire () {
		$sql = "SELECT id, id_commande, id_user, nom_user, prenom_user, id_livreur, login_livreur, id_restaurant, nom_restaurant, date_commande, 
		note, commentaire, validation_commentaire
		FROM commande_history
		WHERE commentaire != ''
		ORDER BY date_commande ASC";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande_History();
			$commande->id = $c["id"];
			$commande->id_commande = $c["id_commande"];
			$commande->date_commande = $c["date_commande"];
			$commande->note = $c["note"];
			$commande->commentaire = $c["commentaire"];
			$commande->validation_commentaire = $c["validation_commentaire"];
			
			$client = new Model_User();
			$client->id = $c["id_user"];
			$client->nom = $c["nom_user"];
			$client->prenom = $c["prenom_user"];
			
			$commande->client = $client;
			
			$livreur = new Model_User();
			$livreur->id = $c["id_livreur"];
			$livreur->login = $c["login_livreur"];
			
			$commande->livreur = $livreur;
			
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom_restaurant"];
			
			$commande->restaurant = $restaurant;
			
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function disableCommentaire () {
		$sql = "UPDATE commande_history SET validation_commentaire = 0 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function enableCommentaire () {
		$sql = "UPDATE commande_history SET validation_commentaire = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
}