<?php

class Model_Notification extends Model_Template {
	
	protected $id;
	private $id_user;
	private $user;
	private $token;
	private $message;
	private $datas;
	private $device;
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
		$sql = "INSERT INTO notifications (id_user, token, message, datas, device, date_envoie, is_send) 
		VALUES (:uid, :token, :message, :data, :device, now(), :send)";
		$stmt = $this->db->prepare($sql);
		
		$stmt->bindValue(":uid", $this->id_user);
		$stmt->bindValue(":token", $this->token);
		$stmt->bindValue(":message", $this->message);
		$stmt->bindValue(":data", $this->datas);
		$stmt->bindValue(":device", $this->device);
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
		$sql = "SELECT notif.id, user.uid, user.nom, user.prenom, user.status, notif.date_envoie, notif.is_send
		FROM notifications notif
		LEFT JOIN users user ON user.uid = notif.id_user
		WHERE notif.date_envoie BETWEEN :date_debut AND :date_fin
		ORDER BY notif.date_envoie DESC LIMIT 50";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$result = $stmt->fetchAll();
		$notifications = array();
		foreach ($result as $item) {
			$notification = new Model_Notification(false);
			$notification->id = $item["id"];
			$notification->date_envoi = $item["date_envoie"];
			$notification->is_send = $item["is_send"];
			
			$user = new Model_User(false);
			$user->id = $item["uid"];
			$user->nom = $item["nom"];
			$user->prenom = $item["prenom"];
			$user->status = $item["status"];
			
			$notification->user = $user;
			
			$notifications[] = $notification;
		}
		return $notifications;
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