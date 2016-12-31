<?php

class Model_Commande extends Model_Template {
	
	protected $id;
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
	private $livreur;
	private $restaurant;
	private $codePromo;
	private $etape;
	private $etapeLibelle;
	private $is_modif;
	private $is_premium;
	private $part_restaurant;
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
	
	public function create ($panier) {
		$sql = "INSERT INTO commande (uid, rue, ville, code_postal, latitude, longitude, telephone, id_restaurant, date_commande, heure_souhaite, minute_souhaite, 
		prix_livraison, part_restaurant, distance, etape, is_premium) 
		(SELECT panier.uid, panier.rue, panier.ville, panier.code_postal, panier.latitude, panier.longitude, panier.telephone, panier.id_restaurant, now(), panier.heure_souhaite, 
		panier.minute_souhaite, CASE WHEN user.is_premium THEN pl.prix - pl.reduction_premium ELSE pl.prix END, resto.pourcentage, panier.distance, 0, user.is_premium
		FROM panier 
		JOIN prix_livraison pl ON panier.distance BETWEEN pl.distance_min AND pl.distance_max
		JOIN users user ON user.uid = panier.uid
		JOIN restaurants resto ON resto.id = panier.id_restaurant
		WHERE panier.id = :id)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$id_commande = $this->db->lastInsertId();
		$this->id = $id_commande;
		
		$total = 0;
		
		$sql = "SELECT pm.id, pm.id_menu, pm.id_format, pm.id_formule, pm.quantite, mf.prix 
		FROM panier_menu pm
		JOIN menu_format mf ON mf.id_format = pm.id_format AND mf.id_menu = pm.id_menu
		WHERE pm.id_panier = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listPanierMenu = $stmt->fetchAll();
		foreach ($listPanierMenu as $panierMenu) {
			$total += $panierMenu['prix'] * $panierMenu['quantite'];
			$sql = "INSERT INTO commande_menu (id_commande, id_menu, id_format, id_formule, quantite) VALUES (:id, :menu, :format, :formule, :quantite)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande);
			$stmt->bindValue(":menu", $panierMenu['id_menu']);
			$stmt->bindValue(":format", $panierMenu['id_format']);
			$stmt->bindValue(":formule", $panierMenu['id_formule']);
			$stmt->bindValue(":quantite", $panierMenu['quantite']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_menu = $this->db->lastInsertId();
			
			$sql = "SELECT id_contenu FROM panier_menu_contenu WHERE id_panier_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierMenu['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierContenu = $stmt->fetchAll();
			foreach ($listPanierContenu as $panierContenu) {
				$sql = "INSERT INTO commande_menu_contenu (id_commande_menu, id_contenu) VALUES (:id, :id_contenu)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_menu);
				$stmt->bindValue(":id_contenu", $panierContenu['id_contenu']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		}
		
		$sql = "SELECT pc.id, pc.id_carte, pc.id_format, pc.quantite, cf.prix FROM panier_carte pc
		JOIN carte_format cf ON cf.id_format = pc.id_format AND cf.id_carte = pc.id_carte
		WHERE id_panier = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $panier->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listPanierCarte = $stmt->fetchAll();
		foreach ($listPanierCarte as $panierCarte) {
			$total += $panierCarte['prix'] * $panierCarte['quantite'];
			$sql = "INSERT INTO commande_carte (id_commande, id_carte, id_format, quantite) VALUES (:id, :id_carte, :id_format, :quantite)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande);
			$stmt->bindValue(":id_carte", $panierCarte['id_carte']);
			$stmt->bindValue(":id_format", $panierCarte['id_format']);
			$stmt->bindValue(":quantite", $panierCarte['quantite']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_carte = $this->db->lastInsertId();
			
			$sql = "SELECT id_option, id_value FROM panier_carte_option WHERE id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierOption = $stmt->fetchAll();
			foreach ($listPanierOption as $panierOption) {
				$sql = "INSERT INTO commande_carte_option (id_commande_carte, id_option, id_value) VALUES (:id, :option, :value)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":option", $panierOption['id_option']);
				$stmt->bindValue(":value", $panierOption['id_value']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
			
			$sql = "SELECT pcs.id_supplement, supp.prix FROM panier_carte_supplement pcs
			JOIN supplements supp ON supp.id = pcs.id_supplement
			WHERE id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierSupplement = $stmt->fetchAll();
			foreach ($listPanierSupplement as $panierSupplement) {
				$total += $panierSupplement['prix'];
				$sql = "INSERT INTO commande_carte_supplement (id_commande_carte, id_supplement) VALUES (:id, :id_supplement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":id_supplement", $panierSupplement['id_supplement']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
			
			$sql = "SELECT id_accompagnement FROM panier_carte_accompagnement WHERE id_panier_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $panierCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listPanierAccompagnement = $stmt->fetchAll();
			foreach ($listPanierAccompagnement as $panierAccompagnement) {
				$sql = "INSERT INTO commande_carte_accompagnement (id_commande_carte, id_accompagnement) VALUES (:id, :accompagnement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":accompagnement", $panierAccompagnement['id_accompagnement']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		}
		
		$sql = "UPDATE commande SET prix = :prix WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_commande);
		$stmt->bindValue(":prix", $total);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function createFromCommande ($commande) {
		$sql = "INSERT INTO commande (uid, rue, ville, code_postal, telephone, id_restaurant, date_commande, heure_souhaite, minute_souhaite, 
		prix_livraison, distance, etape, is_premium) 
		(SELECT uid, rue, ville, code_postal, telephone, id_restaurant, now(), heure_souhaite, minute_souhaite, prix_livraison, distance, 0, true
		FROM pre_commande
		WHERE id = :id)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $commande->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$id_commande = $this->db->lastInsertId();
		$this->id = $id_commande;
		
		$total = 0;
		
		$sql = "SELECT pcm.id, pcm.id_menu, pcm.id_format, pcm.id_formule, pcm.quantite, mf.prix 
		FROM pre_commande_menu pcm
		JOIN menu_format mf ON mf.id_format = pcm.id_format AND mf.id_menu = pcm.id_menu
		WHERE pcm.id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $commande->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCommandeMenu = $stmt->fetchAll();
		foreach ($listCommandeMenu as $commandeMenu) {
			$total += $commandeMenu['prix'] * $commandeMenu['quantite'];
			$sql = "INSERT INTO commande_menu (id_commande, id_menu, id_format, id_formule, quantite) VALUES (:id, :menu, :format, :formule, :quantite)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande);
			$stmt->bindValue(":menu", $commandeMenu['id_menu']);
			$stmt->bindValue(":format", $commandeMenu['id_format']);
			$stmt->bindValue(":formule", $commandeMenu['id_formule']);
			$stmt->bindValue(":quantite", $commandeMenu['quantite']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_menu = $this->db->lastInsertId();
			
			$sql = "SELECT id_contenu FROM pre_commande_menu_contenu WHERE id_commande_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeMenu['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeContenu = $stmt->fetchAll();
			foreach ($listCommandeContenu as $commandeContenu) {
				$sql = "INSERT INTO commande_menu_contenu (id_commande_menu, id_contenu) VALUES (:id, :id_contenu)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_menu);
				$stmt->bindValue(":id_contenu", $commandeContenu['id_contenu']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		}
		
		$sql = "SELECT pcc.id, pcc.id_carte, pcc.id_format, pcc.quantite, cf.prix FROM pre_commande_carte pcc
		JOIN carte_format cf ON cf.id_format = pcc.id_format AND cf.id_carte = pcc.id_carte
		WHERE pcc.id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $commande->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCommandeCarte = $stmt->fetchAll();
		foreach ($listCommandeCarte as $commandeCarte) {
			$total += $commandeCarte['prix'] * $commandeCarte['quantite'];
			$sql = "INSERT INTO commande_carte (id_commande, id_carte, id_format, quantite) VALUES (:id, :id_carte, :id_format, :quantite)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande);
			$stmt->bindValue(":id_carte", $commandeCarte['id_carte']);
			$stmt->bindValue(":id_format", $commandeCarte['id_format']);
			$stmt->bindValue(":quantite", $commandeCarte['quantite']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_carte = $this->db->lastInsertId();
			
			$sql = "SELECT id_option, id_value FROM pre_commande_carte_option WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeOption = $stmt->fetchAll();
			foreach ($listCommandeOption as $commandeOption) {
				$sql = "INSERT INTO commande_carte_option (id_commande_carte, id_option, id_value) VALUES (:id, :option, :value)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":option", $commandeOption['id_option']);
				$stmt->bindValue(":value", $commandeOption['id_value']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
			
			$sql = "SELECT pccs.id_supplement, supp.prix FROM pre_commande_carte_supplement pccs
			JOIN supplements supp ON supp.id = pccs.id_supplement
			WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeSupplement = $stmt->fetchAll();
			foreach ($listCommandeSupplement as $commandeSupplement) {
				$total += $commandeSupplement['prix'];
				$sql = "INSERT INTO commande_carte_supplement (id_commande_carte, id_supplement) VALUES (:id, :id_supplement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":id_supplement", $commandeSupplement['id_supplement']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
			
			$sql = "SELECT id_accompagnement FROM pre_commande_carte_accompagnement WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeAccompagnement = $stmt->fetchAll();
			foreach ($listCommandeAccompagnement as $commandeAccompagnement) {
				$sql = "INSERT INTO commande_carte_accompagnement (id_commande_carte, id_accompagnement) VALUES (:id, :accompagnement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":accompagnement", $commandeAccompagnement['id_accompagnement']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		}
		
		$sql = "UPDATE commande SET prix = :prix WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_commande);
		$stmt->bindValue(":prix", $total);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function renew ($commande) {
		$sql = "INSERT INTO commande (uid, rue, ville, code_postal, telephone, id_livreur, id_restaurant, date_commande, heure_souhaite, minute_souhaite, 
		prix, prix_livraison, distance, etape, is_premium) 
		(SELECT uid, rue, ville, code_postal, telephone, id_livreur, id_restaurant, now(), heure_souhaite, minute_souhaite, prix, prix_livraison, distance, 0, 
		is_premium
		FROM commande WHERE id = :id)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$id_commande = $this->db->lastInsertId();
		
		$sql = "SELECT id_menu, id_format, id_formule, quantite FROM commande_menu WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCommandeMenu = $stmt->fetchAll();
		foreach ($listCommandeMenu as $commandeMenu) {
			$sql = "INSERT INTO commande_menu (id, id_commande, id_menu, id_format, id_formule, quantite) 
			(SELECT $id_commande, id_menu)
			VALUES (:id, :menu, :format, :formule, :quantite)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande);
			$stmt->bindValue(":menu", $commandeMenu['id_menu']);
			$stmt->bindValue(":format", $commandeMenu['id_format']);
			$stmt->bindValue(":formule", $commandeMenu['id_formule']);
			$stmt->bindValue(":quantite", $commandeMenu['quantite']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_menu = $this->db->lastInsertId();
			
			$sql = "SELECT id_contenu FROM commande_menu_contenu WHERE id_commande_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeMenu['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeContenu = $stmt->fetchAll();
			foreach ($listCommandeContenu as $commandeContenu) {
				$sql = "INSERT INTO commande_menu_contenu (id_commande_menu, id_contenu) VALUES (:id, :id_contenu)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_menu);
				$stmt->bindValue(":id_contenu", $commandeContenu['id_contenu']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		}
		
		$sql = "SELECT id, id_carte, id_format, quantite FROM commande_carte WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCommandeCarte = $stmt->fetchAll();
		foreach ($listCommandeCarte as $commandeCarte) {
			$sql = "INSERT INTO commande_carte (id_commande, id_carte, id_format, quantite) VALUES (:id, :id_carte, :id_format, :quantite)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande);
			$stmt->bindValue(":id_carte", $commandeCarte['id_carte']);
			$stmt->bindValue(":id_format", $commandeCarte['id_format']);
			$stmt->bindValue(":quantite", $commandeCarte['quantite']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$id_commande_carte = $this->db->lastInsertId();
			
			$sql = "SELECT id_option, id_value FROM commande_carte_option WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeOption = $stmt->fetchAll();
			foreach ($listCommandeOption as $commandeOption) {
				$sql = "INSERT INTO commande_carte_option (id_commande_carte, id_option, id_value) VALUES (:id, :option, :value)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":option", $commandeOption['id_option']);
				$stmt->bindValue(":value", $commandeOption['id_value']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
			
			$sql = "SELECT id_supplement FROM commande_carte_supplement WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeSupplement = $stmt->fetchAll();
			foreach ($listCommandeSupplement as $commandeSupplement) {
				$sql = "INSERT INTO commande_carte_supplement (id_commande_carte, id_supplement) VALUES (:id, :id_supplement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":id_supplement", $commandeSupplement['id_supplement']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
			
			$sql = "SELECT id_accompagnement FROM commande_carte_accompagnement WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeAccompagnement = $stmt->fetchAll();
			foreach ($listCommandeAccompagnement as $commandeAccompagnement) {
				$sql = "INSERT INTO commande_carte_accompagnement (id_commande_carte, id_accompagnement) VALUES (:id, :accompagnement)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $id_commande_carte);
				$stmt->bindValue(":accompagnement", $commandeAccompagnement['id_accompagnement']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
			}
		}
		
		$sql = "UPDATE commande SET etape = -1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		
		return true;
	}
	
	public function load () {
		$sql = "
		SELECT 
			client.uid AS uid, client.nom AS cnom, client.prenom AS cprenom, uc.telephone ctel, com.rue AS com_rue, com.ville AS com_ville, 
			com.code_postal AS com_cp, com.telephone, com.id_livreur AS id_livreur, resto.id AS id_resto, resto.nom AS nom_resto, resto.rue AS rue_resto, 
			resto.ville AS ville_resto, resto.code_postal AS cp_resto, resto.telephone AS tel_resto, livreur.uid AS id_livreur, livreur.prenom AS prenom_livreur, 
			ul.latitude AS lat_livreur, ul.longitude AS lon_livreur, ul.is_ready AS livreur_ready, com.date_commande, com.heure_souhaite, com.minute_souhaite, 
			com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_recuperation_livreur, com.etape, com.prix, com.prix_livraison, com.distance
		FROM commande com
		JOIN users client ON client.uid = com.uid
		JOIN user_client uc ON uc.uid = client.uid
		JOIN restaurants resto ON resto.id = com.id_restaurant
		LEFT JOIN users livreur ON livreur.uid = com.id_livreur
		LEFT JOIN user_livreur ul ON ul.uid = livreur.uid
		WHERE com.id = :id";
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
		$this->client = new Model_User(false);
		$this->client->id = $value['uid'];
		$this->client->nom = $value['cnom'];
		$this->client->prenom = $value['cprenom'];
		$this->client->telephone = $value['ctel'];
		$this->rue = $value['com_rue'];
		$this->ville = $value['com_ville'];
		$this->code_postal = $value['com_cp'];
		$this->telephone = $value['telephone'];
		$this->id_livreur = $value['id_livreur'];
		$this->restaurant = new Model_Restaurant(false);
		$this->restaurant->id = $value['id_resto'];
		$this->restaurant->nom = $value['nom_resto'];
		$this->restaurant->rue = $value['rue_resto'];
		$this->restaurant->ville = $value['ville_resto'];
		$this->restaurant->code_postal = $value['cp_resto'];
		$this->restaurant->telephone = $value['tel_resto'];
		$this->livreur = new Model_User(false);
		$this->livreur->id = $value['id_livreur'];
		$this->livreur->prenom = $value['prenom_livreur'];
		$this->livreur->latitude = $value['lat_livreur'];
		$this->livreur->longitude = $value['lon_livreur'];
		$this->livreur->is_ready = $value['livreur_ready'];
		$this->date_commande = $value['date_commande'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->date_validation_restaurant = $value['date_validation_restaurant'];
		$this->date_fin_preparation_restaurant = $value['date_fin_preparation_restaurant'];
		$this->date_recuperation_livreur = $value['date_recuperation_livreur'];
		$this->etape = $value['etape'];
		$this->prix = $value['prix'];
		$this->prix_livraison = $value['prix_livraison'];
		$this->distance = $value['distance'];
		$this->cartes = array();
		
		$sql = "SELECT cc.id AS id, carte.id AS id_carte, carte.nom, carte.id_categorie, cc.quantite, cf.id AS id_format, cf.prix, rf.nom AS nom_format
		FROM commande_carte cc 
		JOIN carte ON carte.id = cc.id_carte
		JOIN carte_format cf ON cf.id_carte = carte.id AND cf.id_format = cc.id_format
		JOIN restaurant_format rf ON rf.id = cc.id_format
		WHERE cc.id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		foreach ($result as $c) {
			$id_commande_carte = $c['id'];
			$carte = new Model_Carte(false);
			$carte->id = $c['id_carte'];
			$carte->nom = $c['nom'];
			$carte->quantite = $c['quantite'];
			$carte->prix = $c['prix'] * $c['quantite'];
			
			$format = new Model_Format();
			$format->id = $c['id_format'];
			$format->nom = $c['nom_format'];

			$carte->addFormat($format);
			
			$sql = "SELECT supp.id, supp.nom, supp.prix
			FROM commande_carte_supplement css
			JOIN supplements supp ON supp.id = css.id_supplement
			WHERE css.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande_carte);
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
			
			$sql = "SELECT cco.id AS id, ro.id AS id_option, ro.nom AS nom_option, rov.id AS id_value, rov.nom AS nom_value
			FROM commande_carte_option cco 
			JOIN restaurant_option ro ON ro.id = cco.id_option
			JOIN restaurant_option_value rov ON rov.id = cco.id_value
			WHERE cco.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande_carte);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteOption = $stmt->fetchAll();
			foreach ($listCommandeCarteOption as $commandeCarteOption) {
				$option = new Model_Option(false);
				$option->id = $commandeCarteOption['id_option'];
				$option->nom = $commandeCarteOption['nom_option'];
				
				$optionValue = new Model_Option_Value(false);
				$optionValue->id = $commandeCarteOption['id_value'];
				$optionValue->nom = $commandeCarteOption['nom_value'];
				
				$option->addValue($optionValue);
				
				$carte->addOption($option);
			}
			
			$sql = "SELECT cca.id AS id, carte.id AS id_carte, carte.nom AS nom_carte
			FROM commande_carte_accompagnement cca 
			JOIN carte ON carte.id = cca.id_accompagnement
			WHERE cca.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $id_commande_carte);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteAccompagnement = $stmt->fetchAll();
			foreach ($listCommandeCarteAccompagnement as $commandeCarteAccompagnement) {
				$accompagnement = new Model_Accompagnement(false);
				$accompagnement->id = $commandeCarteAccompagnement['id'];
				$accompagnementCarte = new Model_Carte(false);
				$accompagnementCarte->id = $commandeCarteAccompagnement['id_carte'];
				$accompagnementCarte->nom = $commandeCarteAccompagnement['nom_carte'];
				$accompagnement->addCarte($accompagnementCarte);
				$carte->addAccompagnement($accompagnement);
			}
			$this->cartes[] = $carte;
		}
		
		
		$sql = "SELECT cm.id AS id, menu.id AS id_menu, menu.nom AS nom_menu, format.id AS id_format, format.nom AS nom_format, cm.quantite, mf.prix
		FROM commande_menu cm
		JOIN menus menu ON menu.id = cm.id_menu
		JOIN restaurant_format format ON format.id = cm.id_format
		JOIN menu_format mf ON mf.id_menu = menu.id AND mf.id_format = format.id
		WHERE cm.id_commande = :id";
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
			
			$sql = "SELECT cmc.id AS id, menu_cat.id AS id_categorie, menu_cat.nom AS nom_categorie, carte.id AS id_carte, carte.nom AS nom_carte
			FROM commande_menu_contenu cmc 
			JOIN menu_contenu mc ON mc.id = cmc.id_contenu
			JOIN menu_categorie menu_cat ON menu_cat.id = mc.id_menu_categorie
			JOIN carte ON carte.id = mc.id_carte
			WHERE cmc.id_commande_menu = :id";
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
		
		/*$sql = "SELECT menu.id, menu.nom, cm.quantite
		FROM commande_menu cm 
		JOIN menus menu ON menu.id = cm.id_menu
		WHERE cm.id_commande = :id";
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
			$menu->prix = $commandeMenu['prix'] * $commandeMenu['quantite'];
			
			$sql = "SELECT carte.id, carte.nom, mc.limite_supplement
			FROM commande_menu_contenu cmc
			JOIN menu_contenu mc ON mc.id = cmc.id_contenu
			JOIN carte ON carte.id = mc.id_carte
			WHERE cmc.id_commande_menu = :id";
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
		}*/
		
		return $this;
	}
	
	public function getLivreur () {
		$sql = "SELECT livreur.uid, livreur.nom, livreur.prenom, us.gcm_token, livreur.is_login 
		FROM commande
		JOIN users livreur ON livreur.uid = commande.id_livreur
		LEFT JOIN user_session us ON us.uid = livreur.uid AND us.date_logout = '0000-00-00 00:00:00'
		WHERE commande.id = :id
		LIMIT 1";
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
		$this->livreur = new Model_User();
		$this->livreur->id = $value['uid'];
		$this->livreur->nom = $value['nom'];
		$this->livreur->prenom = $value['prenom'];
		$this->livreur->gcm_token = $value['gcm_token'];
		$this->livreur->is_login = $value['is_login'];
		return $this->livreur;
	}
	
	public function getClient () {
		$sql = "SELECT client.uid, client.nom, client.prenom, us.gcm_token, client.is_login, up.send_sms_commande, commande.telephone 
		FROM commande
		JOIN users client ON client.uid = commande.uid
		LEFT JOIN user_session us ON us.uid = client.uid AND us.date_logout = '0000-00-00 00:00:00'
		LEFT JOIN user_parametre up ON up.uid = client.uid
		WHERE commande.id = :id
		LIMIT 1";
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
		$this->client = new Model_User();
		$this->client->id = $value['uid'];
		$this->client->nom = $value['nom'];
		$this->client->prenom = $value['prenom'];
		$this->client->gcm_token = $value['gcm_token'];
		$this->client->is_login = $value['is_login'];
		$this->client->telephone = $value["telephone"];
		
		$parameter = new Model_Parametre();
		$parameter->send_sms_commande = $value["send_sms_commande"];
		
		$this->client->parametre = $parameter;
		
		return $this->client;
	}
	
	public function hasCommandeEnCours () {
		$sql = "SELECT COUNT(*) AS nb_commande FROM commande WHERE uid = :uid AND etape < 4";
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
		return $value['nb_commande'] > 0;
	}
	
	public function getIdCommandeEnCoursClient () {
		$sql = "SELECT id FROM commande WHERE uid = :uid AND etape < 4";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$ids = array();
		foreach ($result as $commande) {
			$ids[] = $commande["id"];
		}
		return $ids;
	}
	
	public function hasCommandeEnCoursLivreur () {
		$sql = "SELECT COUNT(*) AS nb_commande FROM commande WHERE id_livreur = :id AND etape < 4";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		return $value['nb_commande'] > 0;
	}
	
	public function getIdCommandeEnCoursLivreur () {
		$sql = "SELECT id FROM commande WHERE id_livreur = :id AND etape < 4";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$ids = array();
		foreach ($result as $commande) {
			$ids[] = $commande["id"];
		}
		return $ids;
	}
	
	/*
	* Récupère les commandes utilisateur en cours
	*/
	public function loadNotFinishedCommande () {
		$sql = "SELECT com.id AS id_commande, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.prix, com.prix_livraison,
		com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_recuperation_livreur, com.etape, resto.id AS id_restaurant, resto.nom,
		com.last_view_user, livreur.uid AS id_livreur, livreur.nom AS nom_livreur, livreur.prenom AS prenom_livreur
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		LEFT JOIN users livreur ON livreur.uid = com.id_livreur
		WHERE com.uid = :uid AND etape < 4";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->etape = $c["etape"];
			$commande->prix = $c["prix"] + $c["prix_livraison"];
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->livreur = $c["id_livreur"];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom"];
			$commande->restaurant = $restaurant;
			$commande->livreur = new Model_User(false);
			$commande->livreur->id = $c['id_livreur'];
			$commande->livreur->nom = $c['nom_livreur'];
			$commande->livreur->prenom = $c['prenom_livreur'];
			$listCommande[] = $commande;
		}
		$sql = "UPDATE commande com SET last_view_user = NOW() WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $listCommande;
	}
	
	public function getCommandeClientModified () {
		$sql = "SELECT id, etape
		FROM commande
		WHERE uid = :uid AND (last_view_user < date_validation_restaurant OR last_view_user < date_fin_preparation_restaurant
		OR last_view_user < date_recuperation_livreur OR last_view_user < date_livraison)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$list = array();
		foreach ($result as $commande) {
			$list[$commande["id"]] = $commande["etape"];
		}
		return $list;
	}
	
	/*
	* Récupère les commandes utilisateur terminées
	*/
	public function loadFinishedCommande () {
		$sql = "SELECT com.id AS id_commande, com.id_livreur, com.date_commande, com.heure_souhaite, com.minute_souhaite, 
		com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_recuperation_livreur, com.etape, resto.id AS id_restaurant, resto.nom
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		WHERE uid = :uid AND etape = 4";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->etape = $c["etape"];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom"];
			$commande->restaurant = $restaurant;
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	/*
	* Récupère les commandes utilisateur en cours
	*/
	public function loadCommandeClient () {
		$sql = "SELECT com.id AS id_commande, livreur.uid AS id_livreur, livreur.nom AS nom_livreur, livreur.prenom AS prenom_livreur, livreur.login AS login_livreur, 
		com.date_commande, com.heure_souhaite, com.minute_souhaite, com.prix, com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_recuperation_livreur, 
		com.etape, resto.id AS id_restaurant, resto.nom AS nom_resto, resto.ville AS ville_resto, resto.code_postal AS cp_resto, com.last_view_user
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		LEFT JOIN users livreur ON livreur.uid = com.id_livreur
		LEFT JOIN user_livreur ul ON ul.uid = livreur.uid
		WHERE com.uid = :uid";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->etape = $c["etape"];
			$commande->prix = $c["prix"];
			$commande->livreur = new Model_User(false);
			$commande->livreur->id = $c['id_livreur'];
			$commande->livreur->nom = $c['nom_livreur'];
			$commande->livreur->prenom = $c['prenom_livreur'];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom_resto"];
			$restaurant->ville = $c["ville_resto"];
			$restaurant->code_postal = $c["cp_resto"];
			$commande->restaurant = $restaurant;
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	/*
	* Récupère les commandes livreur en cours
	*/
	public function loadCommandeLivreur () {
		$sql = "SELECT com.id AS id_commande, user.uid AS id_client, user.nom AS nom_client, user.prenom AS prenom_client, com.date_commande, com.heure_souhaite, 
		com.minute_souhaite, com.prix, com.note, com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_recuperation_livreur, 
		com.etape, resto.id AS id_restaurant, resto.nom AS nom_resto, resto.ville AS ville_resto, resto.code_postal AS cp_resto, com.last_view_user
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		JOIN users user ON user.uid = com.uid
		WHERE com.id_livreur = :uid";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->etape = $c["etape"];
			$commande->prix = $c["prix"];
			$commande->note = $c["note"];
			$commande->client = new Model_User(false);
			$commande->client->id = $c['id_client'];
			$commande->client->nom = $c['nom_client'];
			$commande->client->prenom = $c['prenom_client'];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom_resto"];
			$restaurant->ville = $c["ville_resto"];
			$restaurant->code_postal = $c["cp_resto"];
			$commande->restaurant = $restaurant;
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	/*
	* Récupère les commandes qui n'ont pas été attribué à un livreur
	*/
	public function getCommandeNonAttribue () {
		$sql = "SELECT com.id AS id_commande, com.rue AS rue_commande, com.ville AS ville_commande, com.code_postal AS cp_commande, com.date_commande, 
		com.heure_souhaite, com.minute_souhaite, resto.id AS id_restaurant, resto.nom, resto.rue AS rue_resto, resto.ville AS ville_resto, 
		resto.code_postal AS cp_restaurant, resto.latitude, resto.longitude
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		WHERE com.id_livreur IS NULL";
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
			$commande->id = $c["id_commande"];
			$commande->rue = $c["rue_commande"];
			$commande->ville = $c["ville_commande"];
			$commande->code_postal = $c["cp_commande"];
			$commande->date_commande = $c["date_commande"];
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom"];
			$restaurant->rue = $c["rue_resto"];
			$restaurant->ville = $c["ville_resto"];
			$restaurant->code_postal = $c["cp_restaurant"];
			$restaurant->latitude = $c["latitude"];
			$restaurant->longitude = $c["longitude"];
			$commande->restaurant = $restaurant;
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	/*
	* Récupère les commandes livreur en cours
	*/
	public function getCommandeEnCours () {
		$sql = "SELECT com.id AS id_commande, com.uid, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.etape, com.is_premium, 
		com.ville AS ville_commande, com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_recuperation_livreur, 
		resto.id AS id_restaurant, resto.nom, resto.ville, com.last_view_livreur, client.uid, client.nom AS nom_client, client.prenom AS prenom_client,
		(com.last_view_livreur = '0000-00-00 00:00:00' OR com.last_view_livreur < date_validation_restaurant OR 
		com.last_view_livreur < date_fin_preparation_restaurant) AS is_modif
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		JOIN users client ON client.uid = com.uid
		WHERE id_livreur = :livreur
		AND etape BETWEEN 0 AND 3";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":livreur", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande();
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->etape = $c["etape"];
			$commande->ville = $c["ville_commande"];
			$commande->is_premium = $c["is_premium"];
			$commande->client = new Model_User();
			$commande->client->id = $c['uid'];
			$commande->client->nom = $c['nom_client'];
			$commande->client->prenom = $c['prenom_client'];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom"];
			$restaurant->ville = $c["ville"];
			$commande->restaurant = $restaurant;
			$restaurant->is_modif = $c["is_modif"];
			$listCommande[] = $commande;
		}
		$sql = "UPDATE commande SET last_view_livreur = NOW() WHERE id_livreur = :livreur AND etape < 5";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":livreur", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $listCommande;
	}
	
	/*
	* Récupère les commandes du livreur
	*/
	public function getCommandesLivreur () {
		$sql = "SELECT com.id AS id_commande, com.uid, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.etape, com.is_premium, 
		com.ville AS ville_commande, com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_recuperation_livreur, 
		resto.id AS id_restaurant, resto.nom, resto.ville, com.last_view_livreur, client.uid, client.nom AS nom_client, client.prenom AS prenom_client
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		JOIN users client ON client.uid = com.uid
		WHERE id_livreur = :livreur";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":livreur", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande();
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->etape = $c["etape"];
			$commande->ville = $c["ville_commande"];
			$commande->is_premium = $c["is_premium"];
			$commande->client = new Model_User();
			$commande->client->id = $c['uid'];
			$commande->client->nom = $c['nom_client'];
			$commande->client->prenom = $c['prenom_client'];
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = $c["nom"];
			$restaurant->ville = $c["ville"];
			$commande->restaurant = $restaurant;
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function getCommandeEnCoursNonVue () {
		$sql = "SELECT COUNT(*) AS count
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		WHERE id_livreur = :livreur
		AND etape < 5
		AND (com.last_view_livreur = '0000-00-00 00:00:00' OR com.last_view_livreur < date_validation_restaurant OR com.last_view_livreur < date_fin_preparation_restaurant)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":livreur", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		return $value['count'];
	}
	
	/*
	* Récupère les commandes du restaurants
	*/
	public function getCommandesRestaurant ($etape = false) {
		$sql = "SELECT com.id AS id_commande, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.date_validation_restaurant, 
		com.date_fin_preparation_restaurant, com.etape, com.is_premium, liv.uid, liv.nom, liv.prenom
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		JOIN user_restaurant ur ON ur.id_restaurant = resto.id
		LEFT JOIN users liv ON liv.uid = com.id_livreur
		WHERE ur.uid = :uid";
		if ($etape === false) {
			$sql .= " AND etape BETWEEN 0 AND 2";
		} else {
			$sql .= " AND etape = $etape";
		}
		$sql .= " ORDER BY com.heure_souhaite, com.minute_souhaite";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->etape = $c["etape"];
			$commande->is_premium = $c["is_premium"];
			
			$commande->livreur = new Model_User();
			$commande->livreur->id = $c['uid'];
			$commande->livreur->nom = $c['nom'];
			$commande->livreur->prenom = $c['prenom'];
			
			$listCommande[] = $commande;
		}
		$sql = "UPDATE commande com JOIN user_restaurant ur ON ur.id_restaurant = com.id_restaurant SET last_view_restaurant = NOW()
		WHERE ur.uid = :uid";
		if ($etape === false) {
			$sql .= " AND (etape = 0 OR etape = 1)";
		} else {
			$sql .= " AND etape = $etape";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $listCommande;
	}
	
	public function getCommandeRestaurant () {
		$sql = "SELECT com.id, liv.uid, liv.nom, liv.prenom, com.heure_souhaite, com.minute_souhaite, com.heure_restaurant, com.minute_restaurant, com.prix, 
		com.prix_livraison, com.etape, com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.date_commande, 
		resto.id AS id_resto, resto.nom AS nom_resto, resto.rue AS rue_resto, resto.ville AS ville_resto, resto.code_postal AS cp_resto, 
		resto.telephone AS tel_resto, resto.pourcentage AS pourcentage
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		LEFT JOIN users liv ON liv.uid = com.id_livreur
		WHERE com.id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->heure_restaurant = $value['heure_restaurant'];
		$this->minute_restaurant = $value['minute_restaurant'];
		
		$restaurant = new Model_Restaurant();
		$restaurant->id = $value["id_resto"];
		$restaurant->nom = $value["nom_resto"];
		$restaurant->rue = $value["rue_resto"];
		$restaurant->ville = $value["ville_resto"];
		$restaurant->code_postal = $value["cp_resto"];
		$restaurant->telephone = $value["tel_resto"];
		$restaurant->pourcentage = $value["pourcentage"];
		$this->restaurant = $restaurant;
		
		$this->prix = $value['prix'];
		$this->prix_livraison = $value['prix_livraison'];
		$this->etape = $value['etape'];
		$this->date_validation_restaurant = $value['date_validation_restaurant'];
		$this->date_fin_preparation_restaurant = $value['date_fin_preparation_restaurant'];
		$this->date_commande = $value['date_commande'];
		$this->livreur = new Model_User();
		$this->livreur->id = $value['uid'];
		$this->livreur->nom = $value['nom'];
		$this->livreur->prenom = $value['prenom'];
		
		$sql = "SELECT cm.id AS id, menu.id AS id_menu, menu.nom AS nom_menu, format.id AS id_format, format.nom AS nom_format, cm.quantite, mf.prix
		FROM commande_menu cm
		JOIN menus menu ON menu.id = cm.id_menu
		JOIN restaurant_format format ON format.id = cm.id_format
		JOIN menu_format mf ON mf.id_menu = menu.id AND mf.id_format = format.id
		WHERE cm.id_commande = :id";
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
			
			$sql = "SELECT cmc.id AS id, menu_cat.id AS id_categorie, menu_cat.nom AS nom_categorie, carte.id AS id_carte, carte.nom AS nom_carte
			FROM commande_menu_contenu cmc 
			JOIN menu_contenu mc ON mc.id = cmc.id_contenu
			JOIN menu_categorie menu_cat ON menu_cat.id = mc.id_menu_categorie
			JOIN carte ON carte.id = mc.id_carte
			WHERE cmc.id_commande_menu = :id";
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
		
		$sql = "SELECT cc.id AS id, carte.id AS id_carte, carte.nom AS nom_carte, rf.id AS id_format, rf.nom AS nom_format, cf.prix, cc.quantite
		FROM commande_carte cc
		JOIN carte ON carte.id = cc.id_carte
		JOIN restaurant_format rf ON rf.id = cc.id_format
		JOIN carte_format cf ON cf.id_carte = carte.id AND cf.id_format = rf.id
		WHERE cc.id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCommandeCarte = $stmt->fetchAll();
		foreach ($listCommandeCarte as $commandeCarte) {
			$carte = new Model_Carte(false);
			$carte->id = $commandeCarte['id_carte'];
			$carte->nom = $commandeCarte['nom_carte'];
			$carte->prix = $commandeCarte['prix'];
			//$carte->prix = $commandeCarte['prix'] * $commandeCarte['quantite'];
			$carte->quantite = $commandeCarte['quantite'];
			
			$format = new Model_Format();
			$format->id = $commandeCarte['id_format'];
			$format->nom = $commandeCarte['nom_format'];
			
			$carte->addFormat($format);
			
			$sql = "SELECT ccs.id AS id, supp.id AS id_supplement, supp.nom AS nom_supplement
			FROM commande_carte_supplement ccs 
			JOIN supplements supp ON supp.id = ccs.id_supplement
			WHERE ccs.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteSupplement = $stmt->fetchAll();
			foreach ($listCommandeCarteSupplement as $commandeCarteSupplement) {
				$supplement = new Model_Supplement(false);
				$supplement->id = $commandeCarteSupplement['id_supplement'];
				$supplement->nom = $commandeCarteSupplement['nom_supplement'];
				
				$carte->addSupplement($supplement);
			}
			
			$sql = "SELECT cco.id AS id, ro.id AS id_option, ro.nom AS nom_option, rov.id AS id_value, rov.nom AS nom_value
			FROM commande_carte_option cco 
			JOIN restaurant_option ro ON ro.id = cco.id_option
			JOIN restaurant_option_value rov ON rov.id = cco.id_value
			WHERE cco.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteOption = $stmt->fetchAll();
			foreach ($listCommandeCarteOption as $commandeCarteOption) {
				$option = new Model_Option(false);
				$option->id = $commandeCarteOption['id_option'];
				$option->nom = $commandeCarteOption['nom_option'];
				
				$optionValue = new Model_Option_Value(false);
				$optionValue->id = $commandeCarteOption['id_value'];
				$optionValue->nom = $commandeCarteOption['nom_value'];
				
				$option->addValue($optionValue);
				
				$carte->addOption($option);
			}
			
			$sql = "SELECT cca.id AS id, carte.id AS id_carte, carte.nom AS nom_carte
			FROM commande_carte_accompagnement cca 
			JOIN carte ON carte.id = cca.id_accompagnement
			WHERE cca.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteAccompagnement = $stmt->fetchAll();
			foreach ($listCommandeCarteAccompagnement as $commandeCarteAccompagnement) {
				$accompagnement = new Model_Accompagnement(false);
				$accompagnement->id = $commandeCarteAccompagnement['id'];
				$accompagnementCarte = new Model_Carte(false);
				$accompagnementCarte->id = $commandeCarteAccompagnement['id_carte'];
				$accompagnementCarte->nom = $commandeCarteAccompagnement['nom_carte'];
				$accompagnement->addCarte($accompagnementCarte);
				$carte->addAccompagnement($accompagnement);
			}
			$this->cartes[] = $carte;
		}
		return $this;
	}
	
	public function getCommandeRestaurantNonVue () {
		$sql = "SELECT COUNT(*) AS count
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		JOIN user_restaurant ur ON ur.id_restaurant = resto.id
		WHERE ur.uid = :uid AND etape = 0 AND com.last_view_restaurant = '0000-00-00 00:00:00'";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		return $value['count'];
	}
	
	public function getAllCommandesRestaurant () {
		$sql = "SELECT com.id AS id_commande, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.date_validation_restaurant, 
		com.date_fin_preparation_restaurant, com.prix, com.etape, com.is_premium, liv.uid, liv.nom, liv.prenom
		FROM commande com
		JOIN restaurants resto ON resto.id = com.id_restaurant
		JOIN user_restaurant ur ON ur.id_restaurant = resto.id
		LEFT JOIN users liv ON liv.uid = com.id_livreur
		WHERE ur.uid = :uid
		ORDER BY com.date_commande DESC";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->prix = $c["prix"];
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->etape = $c["etape"];
			$commande->is_premium = $c["is_premium"];
			
			$commande->livreur = new Model_User();
			$commande->livreur->id = $c['uid'];
			$commande->livreur->nom = $c['nom'];
			$commande->livreur->prenom = $c['prenom'];
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function getAllCommandesEntrepriseGroupe () {
		$sql = "SELECT com.id AS id_commande, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.date_validation_restaurant, 
		com.date_fin_preparation_restaurant, com.prix, com.etape, com.is_premium, liv.uid, liv.nom, liv.prenom
		FROM commande com
		LEFT JOIN users liv ON liv.uid = com.id_livreur
		WHERE com.uid IN (SELECT users.uid FROM users JOIN user_entreprise ue ON ue.uid = users.uid WHERE ue.id_groupe = 
		(SELECT id_groupe FROM user_entreprise WHERE uid = :uid))
		ORDER BY com.date_commande DESC";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->prix = $c["prix"];
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->etape = $c["etape"];
			$commande->is_premium = $c["is_premium"];
			
			$commande->livreur = new Model_User();
			$commande->livreur->id = $c['uid'];
			$commande->livreur->nom = $c['nom'];
			$commande->livreur->prenom = $c['prenom'];
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function getAllCommandesEntreprise () {
		$sql = "SELECT com.id AS id_commande, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.date_validation_restaurant, 
		com.date_fin_preparation_restaurant, com.prix, com.etape, com.is_premium, liv.uid, liv.nom, liv.prenom
		FROM commande com
		LEFT JOIN users liv ON liv.uid = com.id_livreur
		WHERE com.uid = :uid
		ORDER BY com.date_commande DESC";
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
			$commande->id = $c["id_commande"];
			$commande->date_commande = formatTimestampToDateHeure($c["date_commande"]);
			$commande->prix = $c["prix"];
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->etape = $c["etape"];
			$commande->is_premium = $c["is_premium"];
			
			$commande->livreur = new Model_User();
			$commande->livreur->id = $c['uid'];
			$commande->livreur->nom = $c['nom'];
			$commande->livreur->prenom = $c['prenom'];
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function attributionLivreur () {
		$sql = "UPDATE commande SET id_livreur = :livreur WHERE id = :commande";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":livreur", $this->uid);
		$stmt->bindValue(":commande", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function annule () {
		$sql = "UPDATE commande SET etape = -1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function validation () {
		$sql = "UPDATE commande SET etape = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function finPreparation () {
		$sql = "UPDATE commande SET etape = 2 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function recuperationCommande () {
		$sql = "UPDATE commande SET etape = 3 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function livraisonCommande () {
		$sql = "UPDATE commande SET etape = 4 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	/*
	* Le restaurant valide que la préparation de la commande à commencer
	*/
	public function annulationRestaurant () {
		$sql = "UPDATE commande SET date_validation_restaurant = NOW(), etape = -1 WHERE id = :commande AND id_restaurant = 
		(SELECT id_restaurant FROM user_restaurant WHERE uid = :uid)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	/*
	* Le restaurant valide que la préparation de la commande à commencer
	*/
	public function validationRestaurant () {
		$sql = "UPDATE commande SET date_validation_restaurant = NOW(), etape = 1 WHERE id = :commande AND id_restaurant = 
		(SELECT id_restaurant FROM user_restaurant WHERE uid = :uid)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	/*
	* Le restaurant valide que la commande est prête
	*/
	public function finPreparationRestaurant () {
		$sql = "UPDATE commande SET date_fin_preparation_restaurant = NOW(), etape = 2 WHERE id = :commande AND id_restaurant = 
		(SELECT id_restaurant FROM user_restaurant WHERE uid = :uid)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getCommandeLivreur () {
		$sql = "SELECT com.id AS id_commande, user.uid AS uid, user.nom AS user_nom, user.prenom AS user_prenom, com.rue AS cmd_rue, com.ville AS cmd_ville, 
		com.code_postal AS cmd_cp, com.telephone AS cmd_tel, resto.id AS id_restaurant, resto.nom AS resto_nom, resto.rue AS resto_rue, resto.ville AS resto_ville, 
		resto.code_postal AS resto_cp, resto.telephone AS resto_tel, com.date_commande, com.heure_souhaite, com.minute_souhaite, com.heure_restaurant, 
		com.minute_restaurant, com.prix, com.date_validation_restaurant, com.date_fin_preparation_restaurant, com.etape, com.prix_livraison
		FROM commande com
		JOIN users user ON user.uid = com.uid
		JOIN restaurants resto ON resto.id = com.id_restaurant
		WHERE com.id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return false;
		}
		
		$this->client = new Model_User(false);
		$this->client->id = $value['uid'];
		$this->client->nom = $value['user_nom'];
		$this->client->prenom = $value['user_prenom'];
		
		$this->rue = $value['cmd_rue'];
		$this->ville = $value['cmd_ville'];
		$this->code_postal = $value['cmd_cp'];
		$this->telephone = $value['cmd_tel'];
		
		$this->restaurant = new Model_Restaurant(false);
		$this->restaurant->id = $value['id_restaurant'];
		$this->restaurant->nom = $value['resto_nom'];
		$this->restaurant->rue = $value['resto_rue'];
		$this->restaurant->ville = $value['resto_ville'];
		$this->restaurant->code_postal = $value['resto_cp'];
		$this->restaurant->telephone = $value['resto_tel'];
		
		$this->date_commande = $value['date_commande'];
		$this->heure_souhaite = $value['heure_souhaite'];
		$this->minute_souhaite = $value['minute_souhaite'];
		$this->date_validation_restaurant = $value['date_validation_restaurant'];
		$this->date_fin_preparation_restaurant = $value['date_fin_preparation_restaurant'];
		$this->etape = $value['etape'];
		$this->prix = $value['prix'];
		$this->prix_livraison = $value['prix_livraison'];
		
		$sql = "SELECT cm.id AS id, menu.id AS id_menu, menu.nom AS nom_menu, format.id AS id_format, format.nom AS nom_format, cm.quantite, mf.prix
		FROM commande_menu cm
		JOIN menus menu ON menu.id = cm.id_menu
		JOIN restaurant_format format ON format.id = cm.id_format
		JOIN menu_format mf ON mf.id_menu = menu.id AND mf.id_format = format.id
		WHERE cm.id_commande = :id";
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
			$menu->prix = $commandeMenu['prix'];
			
			$format = new Model_Format();
			$format->id = $commandeMenu['id_format'];
			$format->nom = $commandeMenu['nom_format'];
			
			$menu->addFormat($format);
			
			$formule = new Model_Formule();
			$formule->id = 0;
			$formule->nom = 'formule';
			
			$menu->addFormule($formule);
			
			$sql = "SELECT cmc.id AS id, menu_cat.id AS id_categorie, menu_cat.nom AS nom_categorie, carte.id AS id_carte, carte.nom AS nom_carte
			FROM commande_menu_contenu cmc 
			JOIN menu_contenu mc ON mc.id = cmc.id_contenu
			JOIN menu_categorie menu_cat ON menu_cat.id = mc.id_menu_categorie
			JOIN carte ON carte.id = mc.id_carte
			WHERE cmc.id_commande_menu = :id";
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
		
		$sql = "SELECT cc.id AS id, carte.id AS id_carte, carte.nom AS nom_carte, rf.id AS id_format, rf.nom AS nom_format, cf.prix, cc.quantite
		FROM commande_carte cc
		JOIN carte ON carte.id = cc.id_carte
		JOIN restaurant_format rf ON rf.id = cc.id_format
		JOIN carte_format cf ON cf.id_carte = carte.id AND cf.id_format = rf.id
		WHERE cc.id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$listCommandeCarte = $stmt->fetchAll();
		foreach ($listCommandeCarte as $commandeCarte) {
			$carte = new Model_Carte(false);
			$carte->id = $commandeCarte['id_carte'];
			$carte->nom = $commandeCarte['nom_carte'];
			$carte->prix = $commandeCarte['prix'];
			$carte->quantite = $commandeCarte['quantite'];
			
			$format = new Model_Format();
			$format->id = $commandeCarte['id_format'];
			$format->nom = $commandeCarte['nom_format'];
			
			$carte->addFormat($format);
			
			$sql = "SELECT ccs.id AS id, supp.id AS id_supplement, supp.nom AS nom_supplement
			FROM commande_carte_supplement ccs 
			JOIN supplements supp ON supp.id = ccs.id_supplement
			WHERE ccs.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteSupplement = $stmt->fetchAll();
			foreach ($listCommandeCarteSupplement as $commandeCarteSupplement) {
				$supplement = new Model_Supplement(false);
				$supplement->id = $commandeCarteSupplement['id_supplement'];
				$supplement->nom = $commandeCarteSupplement['nom_supplement'];
				
				$carte->addSupplement($supplement);
			}
			
			$sql = "SELECT cco.id AS id, ro.id AS id_option, ro.nom AS nom_option, rov.id AS id_value, rov.nom AS nom_value
			FROM commande_carte_option cco 
			JOIN restaurant_option ro ON ro.id = cco.id_option
			JOIN restaurant_option_value rov ON rov.id = cco.id_value
			WHERE cco.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteOption = $stmt->fetchAll();
			foreach ($listCommandeCarteOption as $commandeCarteOption) {
				$option = new Model_Option(false);
				$option->id = $commandeCarteOption['id_option'];
				$option->nom = $commandeCarteOption['nom_option'];
				
				$optionValue = new Model_Option_Value(false);
				$optionValue->id = $commandeCarteOption['id_value'];
				$optionValue->nom = $commandeCarteOption['nom_value'];
				
				$option->addValue($optionValue);
				
				$carte->addOption($option);
			}
			
			$sql = "SELECT cca.id AS id, carte.id AS id_carte, carte.nom AS nom_carte
			FROM commande_carte_accompagnement cca 
			JOIN carte ON carte.id = cca.id_accompagnement
			WHERE cca.id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commandeCarte['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarteAccompagnement = $stmt->fetchAll();
			foreach ($listCommandeCarteAccompagnement as $commandeCarteAccompagnement) {
				$accompagnement = new Model_Accompagnement(false);
				$accompagnement->id = $commandeCarteAccompagnement['id'];
				$accompagnementCarte = new Model_Carte(false);
				$accompagnementCarte->id = $commandeCarteAccompagnement['id_carte'];
				$accompagnementCarte->nom = $commandeCarteAccompagnement['nom_carte'];
				$accompagnement->addCarte($accompagnementCarte);
				$carte->addAccompagnement($accompagnement);
			}
			$this->cartes[] = $carte;
		}
		return $this;
	}
	
	/*
	* Le livreur valide qu'il a récupérer la commande dans le restaurant
	*/
	public function validationLivreur () {
		$sql = "UPDATE commande SET date_validation_livreur = NOW() WHERE id = :commande AND id_livreur = :livreur";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":livreur", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	/*
	* Le livreur valide qu'il a récupérer la commande dans le restaurant
	*/
	public function recuperationLivreur () {
		$sql = "UPDATE commande SET date_recuperation_livreur = NOW(), etape = 3 WHERE id = :commande AND id_livreur = :livreur";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":livreur", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	/*
	* Le livreur valide qu'il a livré la commande
	*/
	public function livraison () {
		$sql = "UPDATE commande SET date_livraison = NOW(), etape = 4 WHERE id = :commande AND id_livreur = :livreur";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":livreur", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	/*
	* L'utilisateur note la commande
	*/
	public function noter ($note, $commentaire) {
		$sql = "UPDATE commande SET note = :note, commentaire = :commentaire WHERE id = :commande AND uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":note", $note);
		$stmt->bindValue(":commentaire", $commentaire);
		$stmt->bindValue(":commande", $this->id);
		$stmt->bindValue(":uid", $this->uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getCommandeTerminer () {
		$sql = "SELECT com.id AS id_commande, user.uid AS id_client, user.nom AS nom_client, user.prenom AS prenom_client, user.login AS login_user, 
		user.email AS email_client, uc.rue AS rue_client, uc.ville AS ville_client, uc.code_postal AS cp_client, com.rue AS rue_commande, 
		com.ville AS ville_commande, com.code_postal AS cp_commande, com.latitude AS latitude_commande, com.longitude AS longitude_commande, 
		com.telephone AS tel_commande, livreur.uid AS id_livreur, livreur.nom AS nom_livreur, livreur.prenom AS prenom_livreur, livreur.login AS login_livreur, 
		resto.id AS id_restaurant, resto.nom AS nom_restaurant, resto.rue AS rue_restaurant, resto.ville AS ville_restaurant, 
		resto.code_postal AS cp_restaurant, resto.telephone AS tel_restaurant, resto.latitude AS latitude_restaurant, resto.longitude AS longitude_restaurant, 
		com.date_commande, com.heure_souhaite, com.minute_souhaite, com.prix, com.prix_livraison, com.part_restaurant, com.distance, com.date_validation_restaurant, 
		com.date_fin_preparation_restaurant, com.date_recuperation_livreur, com.date_livraison, com.etape, com.note, com.commentaire, com.is_premium,
		cp.id AS id_code_promo, cp.code, cp.description, cp.date_debut, cp.date_fin, cp.publique, cp.sur_restaurant, cp.type_reduc, cp.sur_prix_livraison, 
		cp.valeur_prix_livraison, cp.sur_prix_total, cp.valeur_prix_total, cp.pourcentage_prix_total	
		FROM commande com
		JOIN users user ON user.uid = com.uid
		JOIN user_client uc ON uc.uid = user.uid
		LEFT JOIN users livreur ON livreur.uid = com.id_livreur
		JOIN restaurants resto ON resto.id = com.id_restaurant
		LEFT JOIN code_promo cp ON cp.id = com.id_code_promo";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande();
			$commande->id = $c["id_commande"];
			$commande->rue = $c["rue_commande"];
			$commande->ville = $c["ville_commande"];
			$commande->code_postal = $c["cp_commande"];
			$commande->latitude = $c["latitude_commande"];
			$commande->longitude = $c["longitude_commande"];
			$commande->telephone = $c["tel_commande"];
			$commande->date_commande = $c["date_commande"];
			$commande->heure_souhaite = $c["heure_souhaite"];
			$commande->minute_souhaite = $c["minute_souhaite"];
			$commande->prix = $c["prix"];
			$commande->prix_livraison = $c["prix_livraison"];
			$commande->part_restaurant = $c["part_restaurant"];
			$commande->distance = $c["distance"];
			$commande->date_validation_restaurant = $c["date_validation_restaurant"];
			$commande->date_fin_preparation_restaurant = $c["date_fin_preparation_restaurant"];
			$commande->date_recuperation_livreur = $c["date_recuperation_livreur"];
			$commande->date_livraison = $c["date_livraison"];
			$commande->etape = $c["etape"];
			$commande->note = $c["note"];
			$commande->commentaire = $c["commentaire"];
			$commande->is_premium = $c["is_premium"];
			
			$user = new Model_User();
			$user->id = $c["id_client"];
			$user->nom = $c["nom_client"];
			$user->prenom = $c["prenom_client"];
			$user->login = $c["login_user"];
			$user->email = $c["email_client"];
			$user->rue = $c["rue_client"];
			$user->ville = $c["ville_client"];
			$user->code_postal = $c["cp_client"];
			
			$commande->client = $user;
			
			$livreur = new Model_User();
			$livreur->id = $c["id_livreur"];
			$livreur->nom = $c["nom_livreur"];
			$livreur->prenom = $c["prenom_livreur"];
			$livreur->login = $c["login_livreur"];
			$livreur->email = $c["login_livreur"];
			
			$commande->livreur = $livreur;
			
			$restaurant = new Model_Restaurant();
			$restaurant->id = $c["id_restaurant"];
			$restaurant->nom = utf8_encode($c["nom_restaurant"]);
			$restaurant->rue = $c["rue_restaurant"];
			$restaurant->ville = $c["ville_restaurant"];
			$restaurant->code_postal = $c["cp_restaurant"];
			$restaurant->telephone = $c["tel_restaurant"];
			$restaurant->latitude = $c["latitude_restaurant"];
			$restaurant->longitude = $c["longitude_restaurant"];
			
			$commande->restaurant = $restaurant;
		
			$codePromo = new Model_CodePromo(false);
			$codePromo->id = $c['id_code_promo'];
			$codePromo->code = $c['code'];
			$codePromo->description = $c['description'];
			$codePromo->date_debut = $c['date_debut'] == '' ? '0000-00-00 00:00:00' : $c['date_debut'];
			$codePromo->date_fin = $c['date_fin'] == '' ? '0000-00-00 00:00:00' : $c['date_fin'];
			$codePromo->type_reduc = $c['type_reduc'];
			$codePromo->sur_prix_livraison = $c['sur_prix_livraison'];
			$codePromo->valeur_prix_livraison = $c['valeur_prix_livraison'];
			$codePromo->sur_prix_total = $c['sur_prix_total'];
			$codePromo->valeur_prix_total = $c['valeur_prix_total'];
			$codePromo->pourcentage_prix_total = $c['pourcentage_prix_total'];
			$commande->codePromo = $codePromo;
			
			$sql = "SELECT cm.id, menu.id AS id_menu, menu.nom AS nom_menu, menu.commentaire AS commentaire_menu, rf.id AS id_format, rf.nom AS nom_format, cm.quantite, mf.prix
			FROM commande_menu cm
			JOIN menus menu ON menu.id = cm.id_menu
			JOIN restaurant_format rf ON rf.id = cm.id_format
			JOIN menu_format mf ON mf.id_menu = menu.id AND mf.id_format = rf.id
			WHERE cm.id_commande = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commande->id);
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
				$menu->prix = $commandeMenu['prix'];
				
				$format = new Model_Format();
				$format->id = $commandeMenu['id_format'];
				$format->nom = $commandeMenu['nom_format'];
				
				$menu->addFormat($format);
				
				$formule = new Model_Formule();
				$formule->id = 0;
				$formule->nom = 'formule';
				
				$menu->addFormule($formule);
				
				$sql = "SELECT cmc.id, menu_cat.id AS id_categorie, menu_cat.nom AS nom_categorie, carte.id AS id_carte, carte.nom AS nom_carte, 
				carte.commentaire AS commentaire_carte, mc.obligatoire AS contenu_obligatoire, mc.limite_supplement, mc.limite_accompagnement, 
				mc.commentaire AS contenu_commentaire
				FROM commande_menu_contenu cmc 
				JOIN menu_contenu mc ON mc.id = cmc.id_contenu
				JOIN menu_categorie menu_cat ON menu_cat.id = mc.id_menu_categorie
				JOIN carte ON carte.id = mc.id_carte
				WHERE cmc.id_commande_menu = :id";
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
				
					$sql = "SELECT spl.id, spl.nom, spl.prix, spl.commentaire
					FROM commande_menu_supplement cms 
					JOIN supplements spl ON spl.id = cms.id_supplement
					WHERE cms.id_commande_menu_contenu = :id";
					$stmt = $this->db->prepare($sql);
					$stmt->bindValue(":id", $commandeContenu['id']);
					if (!$stmt->execute()) {
						writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
						return false;
					}
					$listCommandeContenuSupplement = $stmt->fetchAll();
					foreach ($listCommandeContenuSupplement as $commandeContenuSupplement) {
						$supplement = new Model_Supplement(false);
						$supplement->id = $commandeContenuSupplement['id'];
						$supplement->nom = $commandeContenuSupplement['nom'];
						$supplement->prix = $commandeContenuSupplement['prix'];
						$supplement->commentaire = $commandeContenuSupplement['commentaire'];
						
						$contenu->addSupplement($supplement);
					}
				
					$sql = "SELECT carte.id, carte.nom, carte.commentaire
					FROM commande_menu_accompagnement cma 
					JOIN carte ON carte.id = cma.id_accompagnement
					WHERE cma.id_commande_menu_contenu = :id";
					$stmt = $this->db->prepare($sql);
					$stmt->bindValue(":id", $commandeContenu['id']);
					if (!$stmt->execute()) {
						writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
						return false;
					}
					$listCommandeContenuAccompagnement = $stmt->fetchAll();
					foreach ($listCommandeContenuAccompagnement as $commandeContenuAccompagnement) {
						$accompagnement = new Model_Carte(false);
						$accompagnement->id = $commandeContenuAccompagnement['id'];
						$accompagnement->nom = $commandeContenuAccompagnement['nom'];
						$accompagnement->commentaire = $commandeContenuAccompagnement['commentaire'];
						
						$contenu->addAccompagnement($accompagnement);
					}
					
					$categorie->addContenu($contenu);
					
					$formule->addCategorie($categorie);
				}
				$this->menus[] = $menu;
			}
			
			$sql = "SELECT cc.id, carte.id AS id_carte, carte.nom AS nom_carte, rc.id AS id_categorie, rc.nom AS nom_categorie, parent.id AS id_parent, parent.nom AS nom_parent, 
			carte.commentaire AS commentaire_carte, carte.limite_supplement, cc.quantite, rf.id AS id_format, rf.nom AS nom_format, 
			cf.prix, cf.temps_preparation
			FROM commande_carte cc
			JOIN carte ON carte.id = cc.id_carte
			JOIN restaurant_categorie rc ON rc.id = carte.id_categorie
			LEFT JOIN restaurant_categorie parent ON parent.id = rc.parent_categorie
			JOIN restaurant_format rf ON rf.id = cc.id_format
			JOIN carte_format cf ON cf.id_carte = carte.id AND cf.id_format = rf.id
			WHERE cc.id_commande = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $commande->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$listCommandeCarte = $stmt->fetchAll();
			foreach ($listCommandeCarte as $commandeCarte) {
				$carte = new Model_Carte(false);
				$carte->id = $commandeCarte['id_carte'];
				$carte->nom = $commandeCarte['nom_carte'];
				$carte->commentaire = $commandeCarte['commentaire_carte'];
				$carte->quantite = $commandeCarte['quantite'];
				$carte->prix = $commandeCarte['prix'];
				$carte->temps_preparation = $commandeCarte['temps_preparation'];
				
				$format = new Model_Format();
				$format->id = $commandeCarte['id_format'];
				$format->nom = $commandeCarte['nom_format'];
				
				$categorie = new Model_Categorie(false);
				$categorie->id = $commandeCarte['id_categorie'];
				$categorie->nom = $commandeCarte['nom_categorie'];
				
				$parentCategorie = new Model_Categorie(false);
				$parentCategorie->id = $commandeCarte['id_parent'];
				$parentCategorie->nom = $commandeCarte['nom_parent'];
				
				$categorie->parent_categorie = $parentCategorie;
				
				$carte->categorie = $categorie;
				
				$carte->addFormat($format);
				
				$sql = "SELECT spl.id, spl.nom, spl.prix, spl.commentaire
				FROM commande_carte_supplement ccs 
				JOIN supplements spl ON spl.id = ccs.id_supplement
				WHERE ccs.id_commande_carte = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $commandeCarte['id']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
				$listCommandeCarteSupplement = $stmt->fetchAll();
				foreach ($listCommandeCarteSupplement as $commandeCarteSupplement) {
					$supplement = new Model_Supplement(false);
					$supplement->id = $commandeCarteSupplement['id'];
					$supplement->nom = $commandeCarteSupplement['nom'];
					$supplement->prix = $commandeCarteSupplement['prix'];
					$supplement->commentaire = $commandeCarteSupplement['commentaire'];
					
					$carte->addSupplement($supplement);
				}
			
				$sql = "SELECT carte.id, carte.nom, carte.commentaire
				FROM commande_carte_accompagnement cca 
				JOIN carte ON carte.id = cca.id_accompagnement
				WHERE cca.id_commande_carte = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $commandeCarte['id']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
				$listCommandeCarteAccompagnement = $stmt->fetchAll();
				foreach ($listCommandeCarteAccompagnement as $commandeCarteAccompagnement) {
					$accompagnement = new Model_Carte(false);
					$accompagnement->id = $commandeCarteAccompagnement['id'];
					$accompagnement->nom = $commandeCarteAccompagnement['nom'];
					$accompagnement->commentaire = $commandeCarteAccompagnement['commentaire'];
					
					$carte->addAccompagnement($accompagnement);
				}
			
				$sql = "SELECT cco.id AS id, ro.id AS id_option, ro.nom AS nom_option, rov.id AS id_value, rov.nom AS nom_value
				FROM commande_carte_option cco 
				JOIN restaurant_option ro ON ro.id = cco.id_option
				JOIN restaurant_option_value rov ON rov.id = cco.id_value
				WHERE cco.id_commande_carte = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $commandeCarte['id']);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
					return false;
				}
				$listCommandeCarteOption = $stmt->fetchAll();
				foreach ($listCommandeCarteOption as $commandeCarteOption) {
					$option = new Model_Option(false);
					$option->id = $commandeCarteOption['id_option'];
					$option->nom = $commandeCarteOption['nom_option'];
					
					$optionValue = new Model_Option_Value(false);
					$optionValue->id = $commandeCarteOption['id_value'];
					$optionValue->nom = $commandeCarteOption['nom_value'];
					
					$option->addValue($optionValue);
					
					$carte->addOption($option);
				}
				
				$commande->cartes[] = $carte;
			}
			
			$listCommande[] = $commande;
		}
		return $listCommande;
	}
	
	public function remove () {
		$sql = "SELECT id FROM commande_menu WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		foreach ($result as $value) {
			$sql = "DELETE FROM commande_menu_contenu WHERE id_commande_menu = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$sql = "DELETE FROM commande_menu WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		$sql = "SELECT id FROM commande_carte WHERE id_commande = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		foreach ($result as $value) {
			$sql = "DELETE FROM commande_carte_supplement WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$sql = "DELETE FROM commande_carte_option WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$sql = "DELETE FROM commande_carte_accompagnement WHERE id_commande_carte = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
			$sql = "DELETE FROM commande_carte WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $value['id']);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				return false;
			}
		}
		$sql = "DELETE FROM commande WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function getAll () {
		$sql = "SELECT com.id AS id_commande, client.uid AS id_client, client.nom AS nom_client, client.prenom AS prenom_client, 
		livreur.uid AS id_livreur, livreur.prenom AS prenom_livreur, livreur.login, 
		resto.id AS id_restaurant, resto.nom AS nom_restaurant, resto.code_postal AS cp_restaurant, resto.ville AS ville_restaurant, 
		com.ville, com.code_postal, com.date_commande, com.prix, com.prix_livraison, com.etape, com.note, com.date_validation_livreur
		FROM commande com
		LEFT JOIN users client ON client.uid = com.uid
		LEFT JOIN users livreur ON livreur.uid = com.id_livreur
		JOIN restaurants resto ON resto.id = com.id_restaurant
		ORDER BY com.date_commande ASC";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listCommande = array();
		foreach ($result as $c) {
			$commande = new Model_Commande();
			$commande->id = $c["id_commande"];
			$commande->ville = $c["ville"];
			$commande->code_postal = $c["code_postal"];
			$commande->date_commande = $c["date_commande"];
			$commande->prix = $c["prix"] + $c["prix_livraison"];
			$commande->etape = $c["etape"];
			$commande->note = $c["note"];
			$commande->date_validation_livreur = $c["date_validation_livreur"];
			
			$client = new Model_User();
			$client->id = $c["id_client"];
			$client->nom = $c["nom_client"];
			$client->prenom = $c["prenom_client"];
			
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
		return $listCommande;
	}
	
	public function getTotal () {
		$sql = "SELECT COUNT(*) AS total_commande, SUM(prix - ((prix * part_restaurant) / 100)) AS part_restaurant, SUM(prix_livraison) AS part_livreur, 
		SUM(prix + prix_livraison) AS total_prix FROM commande";
		$stmt = $this->db->prepare($sql);
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
	
	public function getTotalByLivreur () {
		$sql = "SELECT livreur.uid AS id_livreur, livreur.login AS nom, COUNT(*) AS total_commande, 
		SUM(commande.prix - ((commande.prix * commande.part_restaurant) / 100)) AS part_restaurant, SUM(commande.prix_livraison) AS part_livreur, 
		SUM(commande.prix + commande.prix_livraison) AS total_prix
		FROM commande 
		LEFT JOIN users livreur ON livreur.uid = commande.id_livreur
		GROUP BY livreur.uid";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByRestaurant () {
		$sql = "SELECT resto.id AS id_restaurant, resto.nom AS nom, COUNT(*) AS total_commande, 
		SUM(commande.prix - ((commande.prix * commande.part_restaurant) / 100)) AS part_restaurant, SUM(commande.prix_livraison) AS part_livreur, 
		SUM(commande.prix + commande.prix_livraison) AS total_prix
		FROM commande 
		JOIN restaurants resto ON resto.id = commande.id_restaurant
		GROUP BY resto.id";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByClient () {
		$sql = "SELECT client.uid AS id_livreur, client.nom AS nom, client.prenom AS prenom, COUNT(*) AS total_commande, 
		SUM(commande.prix - ((commande.prix * commande.part_restaurant) / 100)) AS part_restaurant, SUM(commande.prix_livraison) AS part_livreur, 
		SUM(commande.prix + commande.prix_livraison) AS total_prix
		FROM commande 
		JOIN users client ON client.uid = commande.uid
		GROUP BY client.uid";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getTotalByVille () {
		$sql = "SELECT ville AS nom, code_postal AS cp, COUNT(*) AS total_commande, 
		SUM(commande.prix - ((commande.prix * commande.part_restaurant) / 100)) AS part_restaurant, SUM(commande.prix_livraison) AS part_livreur, 
		SUM(commande.prix + commande.prix_livraison) AS total_prix
		FROM commande 
		GROUP BY ville";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return $stmt->fetchAll();;
	}
	
	public function getStatus () {
		switch ($this->etape) {
			case -1:
				return "Annulée";
			case 0:
				return "En attente de validation";
			case 1:
				return "En préparation";
			case 2:
				return "prête";
			case 3:
				return "En livraison";
			case 4:
				return "Livrée";
			default :
				return "";
		}
	}
}