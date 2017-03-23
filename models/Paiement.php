<?php

class Model_Paiement extends Model_Template {
	
	private $id_panier;
	private $montant;
	private $method;
	private $token;
	private $error_code;
	
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
		$sql = "INSERT INTO panier_paiement (id_panier, montant, paiement_method, paiement_token, paiement_erreur_code)
		VALUES (:id_panier, :montant, :method, :token, :error_code)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id_panier", $this->id_panier);
		$stmt->bindValue(":montant", $this->montant);
		$stmt->bindValue(":method", $this->method);
		$stmt->bindValue(":token", $this->token);
		$stmt->bindValue(":error_code", $this->error_code);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	public function getByPanier () {
		$sql = "SELECT id, montant, paiement_method, paiement_erreur_code 
		FROM panier_paiement
		WHERE id_panier = :panier";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":panier", $this->id_panier);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$resultats = $stmt->fetchAll();
		$list = array();
		foreach ($resultats as $resultat) {
			$paiement = new Model_Paiement(true, $this->db);
			$paiement->id = $resultat['id'];
			$paiement->montant = $resultat['montant'];
			$paiement->method = $resultat['paiement_method'];
			$paiement->error_code = $resultat['paiement_erreur_code'];
			$list[] = $paiement;
		}
		return $list;
	}
	
	public function load () {
		$sql = "SELECT id_panier, montant, paiement_method, paiement_token, paiement_erreur_code
		FROM panier_paiement
		WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id_panier = $value['id_panier'];
		$this->montant = $value['montant'];
		$this->method = $value['paiement_method'];
		$this->token = $value['paiement_token'];
		$this->error_code = $value['paiement_erreur_code'];
		return $this;
	}
	
	public function remove() {
		$sql = "DELETE FROM panier_paiement WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		return true;
	}
	
	
}