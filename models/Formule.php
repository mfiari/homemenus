<?php

class Model_Formule extends Model_Template {
	
	private $id;
	private $id_restaurant;
	private $nom;
	private $categories;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->categories = array();
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
	
	public function addCategorie ($categorie) {
		$this->categories[] = $categorie;
	}
	
	public function save () {
		$sql = "INSERT INTO restaurant_formule (id_restaurant, nom) VALUES (:restaurant, :nom)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $this->id_restaurant);
		$stmt->bindValue(":nom", $this->nom);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function remove () {
		$sql = "DELETE FROM restaurant_formule WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		return true;
	}
}