<?php

class Model_Contenu extends Model_Template {
	
	public function getByMenu ($id) {
		$sql = "SELECT id, id_categorie, nom, obligatoire, commentaire FROM contenu_menu WHERE id_menu = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
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
}