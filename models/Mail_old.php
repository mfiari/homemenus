<?php

class Model_Mail extends Model_Template {
	
	private $id;
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
	
	public function addAttachement ($attachement) {
		$this->attachements[] = $attachement;
	}
	
	public function save () {
		$sql = "INSERT INTO mails (email_from, email_to, sujet, contenu, attachements, id_user, date_envoie, is_send) 
		VALUES (:from, :to, :sujet, :contenu, :attachements, :user, :date, :send)";
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
		$stmt->bindValue(":date", $this->date_envoi);
		$stmt->bindValue(":send", $this->is_send);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
	
	/*
	* Récupère les mails
	*/
	public function getAll () {
		$sql = "SELECT id, email_to, sujet, attachements, date_envoie, is_send
		FROM mails";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
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
			var_dump($stmt->errorInfo());
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
}