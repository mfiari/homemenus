<?php

class Model_TransfertPaiement extends Model_Template {
	
	private $method;
	private $quantite;
	private $montant;
	private $date_paiement;
	private $envoi;
	private $date_envoi;
	private $id_coursier;
	private $date_reception;
	private $date_validation;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
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
	
	public function save () {
		if ($this->id == -1) {
			return $this->insert();
		} else {
			return $this->update();
		}
	}
	
	public function insert () {
		$sql = "INSERT INTO transfert_paiement (paiement_method, quantite, montant, date_paiement, envoi)
		VALUES (:method, :quantite, :montant, NOW(), :envoi)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":method", $this->method);
		$stmt->bindValue(":quantite", $this->quantite);
		$stmt->bindValue(":montant", $this->montant);
		$stmt->bindValue(":envoi", $this->envoi);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	
}