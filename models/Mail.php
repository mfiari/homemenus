<?php

class Model_Mail extends Model_Template {
	
	protected $id;
	private $from;
	private $to;
	private $sujet;
	private $contenu;
	private $attachements;
	private $id_user;
	private $date_envoi;
	private $is_send;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->id = -1;
		$this->attachements = array();
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
	
	public function addAttachment ($attachement) {
		$this->attachements[] = $attachement;
	}
	
	public function save () {
		$sql = "INSERT INTO mails (email_from, email_to, sujet, contenu, attachements, id_user, date_envoie, is_send) 
		VALUES (:from, :to, :sujet, :contenu, :attachements, :user, now(), :send)";
		$stmt = $this->db->prepare($sql);
		
		$attachements = '';
		$separator = '';
		foreach ($this->attachements as $attachement) {
			$attachements .= $separator.$attachement;
			$separator = ';';
		}
		
		$stmt->bindValue(":from", $this->from);
		$stmt->bindValue(":to", $this->to);
		$stmt->bindValue(":sujet", $this->sujet);
		$stmt->bindValue(":contenu", $this->contenu);
		$stmt->bindValue(":attachements", $attachements);
		$stmt->bindValue(":user", $this->id_user);
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
		$sql = "SELECT id, email_to, sujet, attachements, date_envoie, is_send
		FROM mails 
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
		$listMail = array();
		foreach ($result as $item) {
			$mail = new Model_Mail(false);
			$mail->id = $item["id"];
			$mail->to = $item["email_to"];
			$mail->sujet = $item["sujet"];
			if ($item["attachements"] != '') {
				$mail->attachements = explode(';', $item["attachements"]);
			}
			$mail->date_envoi = $item["date_envoie"];
			$mail->is_send = $item["is_send"];
			$listMail[] = $mail;
		}
		return $listMail;
	}
	
	public function load () {
		$sql = "SELECT email_from, email_to, sujet, contenu, attachements, id_user, date_envoie, is_send
		FROM mails
		WHERE id = :id";
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
		$this->from = $value['email_from'];
		$this->to = $value['email_to'];
		$this->sujet = $value['sujet'];
		$this->contenu = $value['contenu'];
		if ($value["attachements"] != '') {
			$this->attachements = explode(';', $value["attachements"]);
		}
		$this->id_user = $value["id_user"];
		$this->date_envoi = $value["date_envoie"];
		$this->is_send = $value["is_send"];
		
		return $this;
	}
	
	public function getAllClientEmail () {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.email FROM users user
		WHERE user.status = 'USER' AND user.is_enable = 1 AND user.deleted = 0";
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
			$user->email = $item["email"];
			$listUser[] = $user;
		}
		return $listUser;
	}
}