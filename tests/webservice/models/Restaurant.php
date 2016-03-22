<?php

class Model_Restaurant extends Model_Template {
	
	public function getAll () {
		$sql = "SELECT id, url_logo, nom, rue, ville, code_postal, telephone FROM restaurant";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getById ($id) {
		$sql = "SELECT id, url_logo, nom, rue, ville, code_postal, telephone FROM restaurant WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}