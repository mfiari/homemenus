<?php

class Model_Panier extends Model_Template {
	
	public function getByUser ($uid) {
		$sql = "SELECT panier.id AS id_panier, pm.id AS id, quantite, nom, prix, temps_preparation 
			FROM panier 
			JOIN panier_menu pm ON panier.id = pm.id_panier 
			JOIN menus ON pm.id_menu = menus.id
			WHERE panier.uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $uid);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getMenus ($panier) {
		$sql = "SELECT id, id_panier, id_menu, quantite FROM panier_menu WHERE id_panier = :panier";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $panier);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getContenus ($id_panier_menu) {
		$sql = "SELECT id_contenu FROM panier_contenu WHERE id_panier_menu = :id_panier_menu";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_panier_menu", $id_panier_menu);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function create ($uid) {
		$sql = "SELECT id FROM panier WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			return false;
		}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result != false) {
			return $result["id"];
		}
		$sql = "INSERT INTO panier (uid) VALUES (:uid)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addMenu ($id_panier, $id_menu, $quantite) {
		$sql = "INSERT INTO panier_menu (id_panier, id_menu, quantite) VALUES (:id, :menu, :quantite)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_panier);
		$stmt->bindValue(":menu", $id_menu);
		$stmt->bindValue(":quantite", $quantite);
		if (!$stmt->execute()) {
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function addContenu ($id_panier_menu, $id_contenu) {
		$sql = "INSERT INTO panier_contenu (id_panier_menu, id_contenu) VALUES (:id, :id_contenu)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_panier_menu);
		$stmt->bindValue(":id_contenu", $id_contenu);
		if (!$stmt->execute()) {
			return false;
		}
		return true;;
	}
	
	public function remove ($id_user, $id_menu) {
		$sql = "DELETE pm FROM panier_menu pm JOIN panier ON (pm.id_panier = panier.id) WHERE pm.id = :id AND panier.uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_menu);
		$stmt->bindValue(":uid", $id_user);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return true;;
	}
	
	public function deleteAll ($uid) {
		$sql = "SELECT panier.id AS id_panier, pm.id AS id_menu 
			FROM panier 
			JOIN panier_menu pm ON pm.id_panier = panier.id  
			WHERE panier.uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $uid);
		if (!$stmt->execute()) {
			return false;
		}
		$results = $stmt->fetchAll();
		$stmtDelete = $this->db->prepare("DELETE FROM panier_contenu WHERE id_panier_menu = :id");
		$id_panier = 0;
		foreach($results as $result) {
			$id_panier = $result['id_panier'];
			$stmtDelete->bindValue(":id", $result['id_menu']);
			if (!$stmtDelete->execute()) {
				return false;
			}
		}
		$stmt = $this->db->prepare("DELETE FROM panier_menu WHERE id_panier = :id");
		$stmt->bindValue(":id", $id_panier);
		if (!$stmt->execute()) {
			return false;
		}
		
		$stmt = $this->db->prepare("DELETE FROM panier WHERE uid = :uid");
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			return false;
		}
		return true;
	}
	
	
}