<?php

class Model_User extends Model_Template {
	
	public function getUserById ($id) {
		$sql = "SELECT login, email, compte FROM users where uid = :uid";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		return $stmt->execute();
	}
	
	public function getUserBySession($session) {
		$sql = "SELECT uid, nom, prenom, login, status, session_id FROM users WHERE session_id = :session";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":session", $session);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getUserByLoginAndPassword($login, $password) {
		$sql = "SELECT uid, nom, prenom, login, status, session_id FROM users WHERE login = :login AND password = :password";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":login", $login);
		$stmt->bindValue(":password", $password);
		if (!$stmt->execute()) {
			return false;
		}
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function insert($nom, $prenom, $login, $password, $status) {
		$sql = "INSERT INTO users (nom, prenom, login, password, status, session_id) VALUES (:nom, :prenom, :login, :password, :status, :session_id)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $nom);
		$stmt->bindValue(":prenom", $prenom);
		$stmt->bindValue(":login", $login);
		$stmt->bindValue(":password", $password);
		$stmt->bindValue(":status", $status);
		$stmt->bindValue(":session_id", $login);
		if (!$stmt->execute()) {
			return false;
		}
		return $this->db->lastInsertId();
	}
	
	public function insertRestaurant($id_user, $id_restaurant) {
		$sql = "INSERT INTO user_restaurant (uid, id_restaurant) VALUES (:uid, :id_restaurant)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $id_user);
		$stmt->bindValue(":id_restaurant", $id_restaurant);
		if (!$stmt->execute()) {
			return false;
		}
	}
	
	public function insertUser($id_user, $rue, $ville, $code_postal, $telephone) {
		$sql = "INSERT INTO user_client (uid, rue, ville, code_postal, telephone) VALUES (:uid, :rue, :ville, :code_postal, :telephone)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $id_user);
		$stmt->bindValue(":rue", $rue);
		$stmt->bindValue(":ville", $ville);
		$stmt->bindValue(":code_postal", $code_postal);
		$stmt->bindValue(":telephone", $telephone);
		if (!$stmt->execute()) {
			return false;
		}
	}
	
	public function insertLivreur($id_user, $dispo) {
		$sql = "INSERT INTO user_livreur (uid, dispo) VALUES (:uid, :dispo)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $id_user);
		$stmt->bindValue(":dispo", $dispo);
		if (!$stmt->execute()) {
			return false;
		}
	}
}
