<?php

class Model_SMS extends Model_Template {
	
	protected $id;
	private $telephone;
	private $message;
	private $date_envoi;
	private $is_send;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->id = -1;
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
		$sql = "INSERT INTO sms (telephone, message, date_envoie, is_send) 
		VALUES (:telephone, :message, now(), :send)";
		$stmt = $this->db->prepare($sql);
		
		$stmt->bindValue(":telephone", $this->telephone);
		$stmt->bindValue(":message", $this->message);
		$stmt->bindValue(":send", $this->is_send ? 1 : 0);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	/*
	* Récupère les mails
	*/
	public function getAll ($dateDebut, $dateFin) {
		$sql = "SELECT id, telephone, date_envoie, is_send
		FROM sms 
		WHERE date_envoie BETWEEN :date_debut AND :date_fin
		ORDER BY date_envoie DESC LIMIT 50";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listSMS = array();
		foreach ($result as $item) {
			$sms = new Model_SMS(false);
			$sms->id = $item["id"];
			$sms->telephone = $item["telephone"];
			$sms->date_envoi = $item["date_envoie"];
			$sms->is_send = $item["is_send"];
			$listSMS[] = $sms;
		}
		return $listSMS;
	}
	
	public function load () {
		$sql = "SELECT telephone, message, date_envoie, is_send FROM sms WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		$this->telephone = $value['telephone'];
		$this->message = $value['message'];
		$this->date_envoi = $value["date_envoie"];
		$this->is_send = $value["is_send"];
		
		return $this;
	}
	
	public function getAllClientTel () {
		$sql = "SELECT user.uid, user.nom, user.prenom, uc.telephone FROM users user
		JOIN user_client uc ON uc.uid = user.uid
		WHERE user.status = 'USER' AND user.is_enable = 1 AND user.deleted = 0 AND uc.telephone != ''";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$listUser = array();
		foreach ($result as $item) {
			$user = new Model_User(false);
			$user->id = $item["uid"];
			$user->nom = $item["nom"];
			$user->prenom = $item["prenom"];
			$user->telephone = $item["telephone"];
			$listUser[] = $user;
		}
		return $listUser;
	}
}