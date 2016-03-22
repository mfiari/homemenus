<?php

class Model_Gcm extends Model_Template {
	
	private $id;
	private $gcm_token;
	private $date_creation;
	
	public function __construct($callParent = true) {
		if ($callParent) {
			parent::__construct();
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
	
	/**
     * On ajoute un nouvel utilisateur
    */
    public function addUser($gcm_token) {
        // insert user into database
		$sql = "INSERT INTO gcm_ids (gcm_token, date_creation) VALUES(:token, NOW())";
        $stmt = $this->db->prepare($sql);
		$stmt->bindValue(":token", $gcm_token);
		if (!$stmt->execute()) {
			return false;
		}
		return true;
    }
 
    /**
     * Pour obtenir tous les utilisateurs
     */
    public function getDevices() {
		$sql = "select gcm_token FROM gcm_ids";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$devices = array();
		$list = $stmt->fetchAll();
		foreach ($list as $item) {
			$gcm = new Model_Gcm(false);
			$gcm->gcm_token = $item['gcm_token'];
			$devices[] = $gcm;
		}
		return $devices;
    }
	
}