<?php

class Model_Dispo_History extends Model_Template {
	
	private $id_livreur;
	private $nom;
	private $prenom;
	private $login;
	private $email;
	private $rue;
	private $ville;
	private $code_postal;
	private $latitude;
	private $longitude;
	private $perimetre;
	private $vehicule;
	private $date_dispo;
	private $heure_debut;
	private $minute_debut;
	private $heure_fin;
	private $minute_fin;
	
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
		$sql = "INSERT INTO historique_dispo_livreur (uid, nom_livreur, prenom_livreur, login_livreur, email_livreur, rue_dispo, ville_dispo, code_postal_dispo, 
		latitude_dispo, longitude_dispo, perimetre, vehicule, date_dispo, heure_debut, minute_debut, heure_fin, minute_fin) VALUES 
		(:uid, :nom, :prenom, :login, :email, :rue, :ville, :code_postal, :latitude, :longitude, :perimetre, :vehicule, :date, 
		:heure_debut, :minute_debut, :heure_fin, :minute_fin)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id_livreur);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":prenom", $this->prenom);
		$stmt->bindValue(":login", $this->login);
		$stmt->bindValue(":email", $this->email);
		$stmt->bindValue(":rue", $this->rue);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":code_postal", $this->code_postal);
		$stmt->bindValue(":latitude", $this->latitude);
		$stmt->bindValue(":longitude", $this->longitude);
		$stmt->bindValue(":perimetre", $this->perimetre);
		$stmt->bindValue(":vehicule", $this->vehicule);
		$stmt->bindValue(":date", $this->date_dispo);
		$stmt->bindValue(":heure_debut", $this->heure_debut);
		$stmt->bindValue(":minute_debut", $this->minute_debut);
		$stmt->bindValue(":heure_fin", $this->heure_fin);
		$stmt->bindValue(":minute_fin", $this->minute_fin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			return false;
		}
		$this->id = $this->db->lastInsertId();
		return true;
	}
}