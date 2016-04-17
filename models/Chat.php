<?php

class Model_Chat extends Model_Template {
	
	private $id;
	private $id_commande;
	private $sender;
	private $message;
	private $date;
	
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
	
	public function save () {
		$sql = "INSERT INTO chat_commande (id_commande, sender, message) VALUES(:id, :sender, :message)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_commande);
		$stmt->bindValue(":sender", $this->sender);
		$stmt->bindValue(":message", $this->message);
		if (!$stmt->execute()) {
			return false;
		}
		$this->id = $this->db->lastInsertId();
	}
	
	public function getChatCommande () {
		$sql = "SELECT sender, message, date FROM chat_commande WHERE id_commande = :id ORDER BY date ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_commande);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$output = array();
		$result = $stmt->fetchAll();
		foreach ($result as $c) {
			$chat = new Model_Chat(false);
			$chat->sender = $c['sender'];
			$chat->message = $c['message'];
			$chat->date = $c['date'];
			$output[] = $chat;
		}
		return $output;
	}
	
	public function hasChatClient () {
		$sql = "SELECT count(*) AS nb FROM chat_commande WHERE id_commande = :id  AND sender = 'LIVREUR' AND has_view = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_commande);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		return $value['nb'];
	}
	
	public function vueClient () {
		$sql = "UPDATE chat_commande SET has_view = 1 WHERE id_commande = :id  AND sender = 'LIVREUR' AND has_view = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_commande);
		if (!$stmt->execute()) {
			return false;
		}
		return true;
	}
	
	public function hasChatLivreur () {
		$sql = "SELECT count(*) AS nb FROM chat_commande WHERE id_commande = :id  AND sender = 'USER' AND has_view = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_commande);
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null) {
			return;
		}
		return $value['nb'];
	}
	
	public function vueLivreur () {
		$sql = "UPDATE chat_commande SET has_view = 1 WHERE id_commande = :id  AND sender = 'USER' AND has_view = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id_commande);
		if (!$stmt->execute()) {
			return false;
		}
		return true;
	}
}