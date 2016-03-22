<?php

class Model_Categorie extends Model_Template {
	
	public function getByMenu ($id) {
		$sql = "SELECT categorie_menu.id, id_categorie, nom, quantite FROM categorie_menu JOIN categorie ON categorie.id = id_categorie WHERE id_menu = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
}