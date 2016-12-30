<?php

class Model_Supplement extends Model_Template {
	
	protected $id;
	private $id_restaurant;
	private $nom;
	private $prix;
	private $commentaire;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
		}
		$this->_tableName = "supplements";
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
	
	public function insert () {
		$sql = "INSERT INTO supplements (id_restaurant, nom, prix, commentaire) VALUES (:restaurant, :nom, :prix, :commentaire)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $this->id_restaurant);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":prix", $this->prix);
		$stmt->bindValue(":commentaire", $this->commentaire);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function update () {
		$sql = "UPDATE supplements SET  nom = :nom, prix = :prix, commentaire = :commentaire WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":prix", $this->prix);
		$stmt->bindValue(":commentaire", $this->commentaire);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function remove () {
		$sql = "DELETE FROM supplements WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
}