<?php

class Model_Menu extends Model_Template {
	
	public function getByRestaurant ($id) {
		$sql = "SELECT id, nom, prix, temps_preparation FROM menus WHERE id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
		
	}
	
	public function getById ($id) {
		$sql = "SELECT id, nom, prix, temps_preparation, commentaire FROM menus WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
		
	}
}