<?php

class Model_Commande extends Model_Template {
	
	public function create ($uid) {
		$sql = "INSERT INTO commande (uid, date_commande, etape) VALUES (:uid, now(), 0)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$id_commande = $this->db->lastInsertId();
		return $id_commande;
	}
	
	public function createMenu ($id_commande, $id_menu, $quantite) {
		$sql = "INSERT INTO commande_menu (id_commande, id_menu, quantite) VALUES (:id, :menu, :quantite)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_commande);
		$stmt->bindValue(":menu", $id_menu);
		$stmt->bindValue(":quantite", $quantite);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$id_commande_menu = $this->db->lastInsertId();
		return $id_commande_menu;
	}
	
	public function createContenu ($id_commande_menu, $id_contenu) {
		$sql = "INSERT INTO commande_contenu (id_commande_menu, id_contenu) VALUES (:id_commande_menu, :id_contenu)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_commande_menu", $id_commande_menu);
		$stmt->bindValue(":id_contenu", $id_contenu);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return true;
	}
	
	public function getNA ($id) {
		$sql = "SELECT id FROM commande WHERE etape = 0";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCategorieByMenu ($id) {
		$sql = "SELECT cont.id, cont.id_categorie, quantite FROM contenu_menu cont JOIN categorie_menu cat 
			ON cat.id_menu = cont.id_menu AND cat.id_categorie = cont.id_categorie WHERE cont.id_menu = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCommandeAtEtape ($etape) {
		$sql = "SELECT cmd.id, cmd.date_commande, cmd.etape, user.uid, user.nom, user.prenom, client.rue, client.ville, client.code_postal, client.telephone
		FROM commande cmd JOIN users user ON user.uid = cmd.uid JOIN user_client client ON client.uid = cmd.uid WHERE etape = :etape";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":etape", $etape);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCommandeAtEtapeForLivreur ($etape, $livreur) {
		$sql = "SELECT cmd.id, cmd.date_commande, user.uid, user.nom, client.rue, client.ville, client.code_postal, client.telephone
		FROM commande cmd JOIN users user ON user.uid = cmd.uid JOIN user_client client ON client.uid = cmd.uid WHERE etape = :etape AND id_livreur = :livreur";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":etape", $etape);
		$stmt->bindValue(":livreur", $livreur);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCommandeActifClient ($id_user) {
		$sql = "SELECT cmd.id, cmd.date_commande, cmd.etape, user.uid, user.nom, user.prenom, client.rue, client.ville, client.code_postal, client.telephone
		FROM commande cmd JOIN users user ON user.uid = cmd.uid JOIN user_client client ON client.uid = cmd.uid WHERE cmd.uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $id_user);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCommandeActifLivreur ($id_livreur) {
		$sql = "SELECT cmd.id, cmd.date_commande, cmd.etape, user.uid, user.nom, user.prenom, client.rue, client.ville, client.code_postal, client.telephone
		FROM commande cmd JOIN users user ON user.uid = cmd.uid JOIN user_client client ON client.uid = cmd.uid WHERE cmd.id_livreur = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_livreur);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCommandeAttenteRestaurant ($id_restaurant) {
		$sql = "SELECT cmd.id, cmd.id_commande, cmd.quantite, menus.id AS id_menu, menus.nom
		FROM user_restaurant user
		JOIN menus ON menus.id_restaurant = user.id_restaurant
		JOIN commande_menu cmd ON cmd.id_menu = menus.id
		WHERE user.uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_restaurant);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCommandeMenu ($id_commande) {
		$sql = "SELECT cmd.id, cmd.quantite, menu.nom AS menu, menu.prix, resto.nom, resto.id AS id_restaurant, resto.rue, resto.ville, resto.code_postal, resto.telephone 
			FROM commande_menu cmd JOIN menus menu ON menu.id = cmd.id_menu JOIN restaurant resto ON resto.id = menu.id_restaurant
			WHERE cmd.id_commande = :id_commande";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_commande", $id_commande);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getCommandeContenu ($id_commande_menu) {
		$sql = "SELECT cmd.id, ctn.nom, cat.id AS id_categorie, cat.nom AS categorie
				FROM commande_contenu cmd JOIN contenu_menu ctn ON ctn.id = cmd.id_contenu JOIN categorie cat ON cat.id = ctn.id_categorie
				WHERE cmd.id_commande_menu = :id_commande_menu ORDER BY cat.id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_commande_menu", $id_commande_menu);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function validationLivreur ($id_commande, $id_livreur) {
		$sql = "UPDATE commande SET id_livreur = :livreur, etape = 1 WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":livreur", $id_livreur);
		$stmt->bindValue(":id", $id_commande);
		if (!$stmt->execute()) {
			return false;
		}
		return true;
	}
	
	public function validationMenuRestaurant ($id_menu, $id_user) {
		$sql = "UPDATE commande_menu SET date_validation = NOW() WHERE id_menu = :id_menu";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_menu", $id_menu);
		$stmt->bindValue(":uid", $id_user);
		if (!$stmt->execute()) {
			return false;
		}
		return true;
	}
	
	public function validationCommandeRestaurant ($id_commande, $id_user) {
		$sql = "UPDATE commande_menu SET date_validation = NOW() WHERE id_commande = :id_commande AND id_menu IN 
		(SELECT menus.id FROM user_restaurant user JOIN menus ON menus.id_restaurant = user.id_restaurant WHERE user.uid = :uid)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_commande", $id_commande);
		$stmt->bindValue(":uid", $id_user);
		if (!$stmt->execute()) {
			return false;
		}
		return true;
	}
	
}