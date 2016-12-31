<?php

class Model_Option extends Model_Template {
	
	protected $id;
	private $id_restaurant;
	private $nom;
	private $values;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->values = array();
		$this->_tableName = "restaurant_option";
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
	
	public function addValue ($value) {
		$this->values[] = $value;
	}
	
	public function load () {
		$sql = "SELECT nom FROM restaurant_option WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		
		$sql = "SELECT id, nom, ordre FROM restaurant_option_value
		WHERE id_option = :id
		ORDER BY ordre";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$optionValues = $stmt->fetchAll();
		foreach ($optionValues as $value) {
			$optionValue = new Model_Option_Value();
			$optionValue->id = $value["id"];
			$optionValue->nom = $value["nom"];
			$this->addValue($optionValue);
		}
		return $this;
	}
	
	public function insert () {
		$sql = "INSERT INTO restaurant_option (id_restaurant, nom) VALUES (:restaurant, :nom)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $this->id_restaurant);
		$stmt->bindValue(":nom", $this->nom);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function update () {
		$sql = "UPDATE restaurant_option SET nom = :nom WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	public function saveValue ($optionValue) {
		$sql = "INSERT INTO restaurant_option_value (id_option, nom) VALUES (:option, :nom)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":option", $this->id);
		$stmt->bindValue(":nom", $optionValue->nom);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$optionValue->id = $this->db->lastInsertId();
		return true;
	}
	
	public function remove () {
		$sql = "DELETE FROM restaurant_option WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
}