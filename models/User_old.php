<?php

class Model_User extends Model_Template {
	
	private $id;
	private $nom;
	private $prenom;
	private $login;
	private $password;
	private $email;
	private $status;
	private $rue;
	private $ville;
	private $code_postal;
	private $telephone;
	private $session;
	private $gcm_token;
	private $inscription_token;
	private $is_login;
	private $is_enable;
	private $is_ready;
	private $is_premium;
	private $latitude;
	private $longitude;
	private $perimetres;
	private $dispos;
	private $horaires;
	private $id_restaurant;
	
	public function __construct($callParent = true, $db = null) {
		if ($callParent) {
			parent::__construct($db);
		}
		$this->id = -1;
		$this->perimetres = array();
		$this->dispos = array();
		$this->horaires = array();
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
	
	public function addPerimetre ($perimetre) {
		$this->perimetres[] = $perimetre;
	}
	
	public function addHoraire ($horaire) {
		$this->horaires[] = $horaire;
	}
	
	public function isLoginAvailable () {
		$sql = "SELECT uid FROM users WHERE login = :login";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":login", $this->login);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : isLoginAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		return $value === false;
	}
	
	public function get () {
		$sql = "SELECT nom, prenom, gcm_token FROM users WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : isLoginAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		$this->prenom = $value['prenom'];
		$this->gcm_token = $value['gcm_token'];
		return $this;
	}
	
	public function getByLogin () {
		$sql = "SELECT uid, email, is_enable FROM users WHERE login = :login";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":login", $this->login);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : isLoginAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : confirm", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->id = $value['uid'];
		$this->email = $value['email'];
		$this->is_enable = $value["is_enable"];
		return $this;
	}
	
	public function save () {
		if ($this->id == -1) {
			return $this->insert();
		}
		return false;
	}
	
	public function insert() {
		$sql = "INSERT INTO users (nom, prenom, login, password, email, status, inscription_token, is_enable, date_creation) 
		VALUES (:nom, :prenom, :login, sha1(:password), :email, :status, :token, false, now())";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":prenom", $this->prenom);
		$stmt->bindValue(":login", $this->login);
		$stmt->bindValue(":password", $this->password);
		$stmt->bindValue(":email", $this->email);
		$stmt->bindValue(":status", $this->status);
		$stmt->bindValue(":token", $this->inscription_token);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : insert", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->id = $this->db->lastInsertId();
		if ($this->status == USER_LIVREUR) {
			return $this->insertLivreur();
		} else if ($this->status == USER_CLIENT) {
			return $this->insertUser();
		} else if ($this->status == USER_RESTAURANT || $this->status == USER_ADMIN_RESTAURANT) {
			return $this->insertRestaurant();
		}
		return false;
	}
	
	public function insertUser() {
		$sql = "INSERT INTO user_client (uid, rue, ville, code_postal, telephone) VALUES (:uid, :rue, :ville, :code_postal, :telephone)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		$stmt->bindValue(":rue", $this->rue);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":code_postal", $this->code_postal);
		$stmt->bindValue(":telephone", $this->telephone);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : insertUser", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	private function insertLivreur () {
		$sql = "INSERT INTO user_livreur (uid, latitude, longitude, telephone) VALUES (:uid, 0, 0, :telephone)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		$stmt->bindValue(":telephone", $this->telephone);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : insert", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function insertRestaurant() {
		$sql = "INSERT INTO user_restaurant (uid, id_restaurant) VALUES (:uid, :id_restaurant)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		$stmt->bindValue(":id_restaurant", $this->id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : insertUser", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function confirm () {
		$sql = "SELECT uid, nom, prenom, status, is_enable FROM users WHERE uid = :id AND inscription_token = :token";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":token", $this->inscription_token);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : confirm", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : confirm", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE users SET is_enable = true WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : confirm", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function changePassword () {
		$sql = "UPDATE users SET password = :password WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":password", $this->password);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : confirm", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function enable () {
		$sql = "UPDATE users SET is_enable = true WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : enable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function disable () {
		$sql = "UPDATE users SET is_enable = false WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : disable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function subscribePremium () {
		$sql = "UPDATE users SET is_premium = true WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : disable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function login($login, $password) {
		$sql = "SELECT uid, nom, prenom, status, is_enable FROM users WHERE login = :login AND password = sha1(:password)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":login", $login);
		$stmt->bindValue(":password", $password);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		
		$this->is_enable = $value["is_enable"];
		
		if ($this->is_enable) {
			$token = generateToken();
		
			$sql = "INSERT INTO user_session (uid, session_key, date_login) VALUES(:uid, :key, NOW())";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $value["uid"]);
			$stmt->bindValue(":key", $token);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
				$this->sqlHasFailed = true;
				return false;
			}
		
			$sql = "UPDATE users SET is_login = true WHERE uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $value["uid"]);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
				$this->sqlHasFailed = true;
				return false;
			}
			
			$this->id = $value["uid"];
			$this->nom = $value["nom"];
			$this->prenom = $value["prenom"];
			$this->login = $login;
			$this->status = $value["status"];
			$this->session = $token;
			
			if ($this->status == USER_LIVREUR) {
				$sql = "UPDATE user_livreur SET is_ready = true WHERE uid = :id";
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue(":id", $this->id);
				if (!$stmt->execute()) {
					writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
					$this->sqlHasFailed = true;
					return false;
				}
			}
		}
		
		return $this;
	}
	
	public function logout () {
		$sql = "SELECT status, is_login, is_enable FROM users WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE users SET is_login = false WHERE uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE user_session SET date_logout = NOW() WHERE uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$status = $value['status'];
		if ($status == USER_LIVREUR) {
			$sql = "UPDATE user_livreur SET is_ready = false WHERE uid = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $this->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
				$this->sqlHasFailed = true;
				return false;
			}
		}
		return true;
	}
	
	public function getById () {
		$sql = "SELECT user.nom, user.prenom, user.status, user.login, user.email, user.is_premium, uc.rue, uc.ville, uc.code_postal, uc.telephone
		FROM users user
		LEFT JOIN user_client uc ON uc.uid = user.uid
		WHERE user.uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getById", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getById", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->nom = $value["nom"];
		$this->prenom = $value["prenom"];
		$this->login = $value["login"];
		$this->status = $value["status"];
		$this->email = $value["email"];
		$this->is_premium = $value["is_premium"];
		$this->rue = $value["rue"];
		$this->ville = $value["ville"];
		$this->code_postal = $value["code_postal"];
		$this->telephone = $value["telephone"];
		return $this;
	}
	
	public function getBySession ($uid, $session) {
		$sql = "SELECT user.nom, user.prenom, user.status, user.login, uc.rue, uc.ville, uc.code_postal, uc.telephone, user.is_premium
		FROM users user
		JOIN user_session us ON us.uid = user.uid
		LEFT JOIN user_client uc ON uc.uid = user.uid
		WHERE user.uid = :uid AND us.session_key = :session AND user.is_login = true";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		$stmt->bindValue(":session", $session);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getBySession", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getBySession", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->id = $uid;
		$this->nom = $value["nom"];
		$this->prenom = $value["prenom"];
		$this->login = $value["login"];
		$this->status = $value["status"];
		$this->session = $session;
		$this->rue = $value["rue"];
		$this->ville = $value["ville"];
		$this->code_postal = $value["code_postal"];
		$this->telephone = $value["telephone"];
		$this->is_premium = $value["is_premium"];
		return $this;
	}
	
	public function getByLoginAndPassword($login, $password) {
		$sql = "SELECT uid, nom, prenom, status, is_enable FROM users WHERE login = :login AND password = sha1(:password)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":login", $login);
		$stmt->bindValue(":password", $password);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : login", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function modifyPassword ($newPassword) {
		$sql = "UPDATE users SET password = sha1(:password) WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":password", $newPassword);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : livreurReady", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function getByRestaurant ($id_restaurant) {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, user.status, user.is_login, user.is_enable 
		FROM users user
		JOIN user_restaurant ur ON ur.uid = user.uid
		WHERE ur.id_restaurant = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getByRestaurant", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$utlisateurs = $stmt->fetchAll();
		$list = array();
		foreach ($utlisateurs as $utlisateur) {
			$user = new Model_User(false);
			$user->id = $utlisateur['uid'];
			$user->nom = $utlisateur['nom'];
			$user->prenom = $utlisateur['prenom'];
			$user->login = $utlisateur['login'];
			$user->status = $utlisateur['status'];
			$user->is_login = $utlisateur['is_login'];
			$user->is_enable = $utlisateur['is_enable'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function livreurReady ($uid) {
		$sql = "UPDATE user_livreur SET is_ready = true WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : livreurReady", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
	}
	
	public function livreurLogout ($uid) {
		$sql = "UPDATE user_livreur SET is_ready = false WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : livreurLogout", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
	}
	
	public function getLivreurAvailable ($codePostal, $ville) {
		$sql = "SELECT user.uid, ulh.heure_debut, ulh.heure_fin
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_perimetre ulp ON ulp.uid = user.uid
		JOIN user_livreur_horaires ulh ON ulh.uid = user.uid
		WHERE ulp.code_postal = :code_postal AND ulp.ville = :ville
		AND ulh.id_jour = (((DAYOFWEEK(CURRENT_DATE)-1) +7)%7) AND (ulh.heure_debut > HOUR(CURRENT_TIME) 
		OR (ulh.heure_debut < HOUR(CURRENT_TIME) AND ulh.heure_fin > HOUR(CURRENT_TIME)))";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":code_postal", $codePostal);
		$stmt->bindValue(":ville", $ville);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getLivreurAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getLivreurAvailableForRestaurant ($restaurant) {
		$sql = "SELECT user.uid
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		JOIN distance_livreur_resto dlr ON dlr.id_restaurant = :restaurant AND dlr.id_dispo = uld.id
		WHERE user.is_enable = 1 AND uld.id_jour = (WEEKDAY(CURRENT_DATE)+1) 
		AND (uld.heure_debut >= HOUR(CURRENT_TIME) OR (uld.heure_debut < HOUR(CURRENT_TIME) AND uld.heure_fin >= HOUR(CURRENT_TIME)))";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $restaurant->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getLivreurAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getLivreurAvailableForRestaurant2 ($codePostal, $ville, $restaurant) {
		$sql = "SELECT user.uid
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_perimetre ulp ON ulp.uid = user.uid
		JOIN user_livreur_perimetre ulp2 ON ulp2.uid = user.uid
		JOIN user_livreur_horaires ulh ON ulh.uid = user.uid
		WHERE user.is_enable = 1 AND ulp.code_postal = :user_code_postal AND ulp.ville = :user_ville
		AND ulp2.code_postal = :restaurant_code_postal AND ulp2.ville = :restaurant_ville
		AND ulh.id_jour = (WEEKDAY(CURRENT_DATE)+1) 
		AND (ulh.heure_debut > HOUR(CURRENT_TIME) OR (ulh.heure_debut < HOUR(CURRENT_TIME) AND ulh.heure_fin >= HOUR(CURRENT_TIME)))";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":user_code_postal", $codePostal);
		$stmt->bindValue(":user_ville", $ville);
		$stmt->bindValue(":restaurant_code_postal", $restaurant->code_postal);
		$stmt->bindValue(":restaurant_ville", $restaurant->ville);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getLivreurAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getLivreurAvailableForCommande ($commande) {
		$sql = "SELECT user.uid, user.login, user.is_login, us.gcm_token, uld.heure_debut, uld.minute_debut, uld.heure_fin, uld.minute_fin
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		JOIN distance_livreur_resto dlr ON dlr.id_restaurant = :restaurant AND dlr.id_dispo = uld.id
		JOIN user_session us ON us.uid = user.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE user.is_enable = 1 AND uld.id_jour = (WEEKDAY(CURRENT_DATE)+1) ";
		if ($commande->heure_souhaite == -1) {
			"AND (uld.heure_debut > HOUR(CURRENT_TIME) OR (uld.heure_debut < HOUR(CURRENT_TIME) AND uld.heure_fin >= HOUR(CURRENT_TIME)))";
		} else {
			$heure = $commande->heure_souhaite;
			"AND (uld.heure_debut > ".$heure." OR (uld.heure_debut < ".$heure." AND uld.heure_fin > ".$heure."))";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $commande->restaurant->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getLivreurAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$user->login = $livreur['login'];
			$user->is_login = $livreur['is_login'];
			$user->gcm_token = $livreur['gcm_token'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getLivreurAvailableForCommande2 ($commande) {
		$sql = "SELECT user.uid, user.login, user.is_login, user.gcm_token, ulh.heure_debut, ulh.heure_fin
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_perimetre ulp ON ulp.uid = user.uid
		JOIN user_livreur_perimetre ulp2 ON ulp2.uid = user.uid
		JOIN user_livreur_horaires ulh ON ulh.uid = user.uid
		WHERE user.is_enable = 1 AND ulp.code_postal = :user_code_postal AND ulp.ville = :user_ville
		AND ulp2.code_postal = :restaurant_code_postal AND ulp2.ville = :restaurant_ville
		AND ulh.id_jour = (WEEKDAY(CURRENT_DATE)+1) ";
		if ($commande->heure_souhaite == -1) {
			"AND (ulh.heure_debut > HOUR(CURRENT_TIME) OR (ulh.heure_debut < HOUR(CURRENT_TIME) AND ulh.heure_fin > HOUR(CURRENT_TIME)))";
		} else {
			$heure = $commande->heure_souhaite;
			"AND (ulh.heure_debut > ".$heure." OR (ulh.heure_debut < ".$heure." AND ulh.heure_fin > ".$heure."))";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":user_code_postal", $commande->code_postal);
		$stmt->bindValue(":user_ville", $commande->ville);
		$stmt->bindValue(":restaurant_code_postal", $commande->restaurant->code_postal);
		$stmt->bindValue(":restaurant_ville", $commande->restaurant->ville);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getLivreurAvailable", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$user->login = $livreur['login'];
			$user->is_login = $livreur['is_login'];
			$user->gcm_token = $livreur['gcm_token'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getLivreurPerimetre ($code_postal) {
		$sql = "SELECT users.uid, users.gcm_token FROM users 
		JOIN user_livreur ul ON ul.uid = users.uid
		JOIN user_livreur_perimetre ulp ON ulp.uid = users.uid
		WHERE code_postal = :code_postal AND ul.is_ready = true";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":code_postal", $code_postal);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getLivreurPerimetre", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$user->gcm_token = $livreur['gcm_token'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getRestaurantUsers ($id_restaurant) {
		$sql = "SELECT users.uid, us.gcm_token FROM users 
		JOIN user_restaurant ur ON ur.uid = users.uid
		JOIN user_session us ON us.uid = users.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE ur.id_restaurant = :restaurant AND users.is_login = true AND us.gcm_token IS NOT NULL";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getRestaurantUsers", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$users = $stmt->fetchAll();
		$list = array();
		foreach ($users as $usr) {
			$user = new Model_User(false);
			$user->id = $usr['uid'];
			$user->gcm_token = $usr['gcm_token'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function registerToGcm () {
		$sql = "UPDATE user_session SET gcm_token = :token WHERE uid = :id AND session_key = :session_key";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":token", $this->gcm_token);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":session_key", $this->session);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : registerToGcm", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function getAllLivreurs () {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, user.email, user.is_enable, ul.is_ready 
		FROM users user JOIN user_livreur ul ON ul.uid = user.uid WHERE user.status = 'LIVREUR'
		ORDER BY user.is_enable DESC";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getRestaurantUsers", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$users = $stmt->fetchAll();
		$list = array();
		foreach ($users as $usr) {
			$user = new Model_User(false);
			$user->id = $usr['uid'];
			$user->nom = $usr['nom'];
			$user->prenom = $usr['prenom'];
			$user->login = $usr['login'];
			$user->email = $usr['email'];
			$user->is_enable = $usr['is_enable'];
			$user->is_ready = $usr['is_ready'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getAllClients () {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, uc.code_postal, uc.ville, user.is_enable, user.is_premium
		FROM users user JOIN user_client uc ON uc.uid = user.uid WHERE user.status = 'USER'";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getRestaurantUsers", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$users = $stmt->fetchAll();
		$list = array();
		foreach ($users as $usr) {
			$user = new Model_User(false);
			$user->id = $usr['uid'];
			$user->nom = $usr['nom'];
			$user->prenom = $usr['prenom'];
			$user->login = $usr['login'];
			$user->code_postal = $usr['code_postal'];
			$user->ville = $usr['ville'];
			$user->is_enable = $usr['is_enable'];
			$user->is_premium = $usr['is_premium'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function getClient () {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, user.email, user.is_enable, uc.rue, uc.code_postal, uc.ville, uc.telephone
		FROM users user JOIN user_client uc ON uc.uid = user.uid WHERE user.uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getClient", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getClient", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->nom = $value["nom"];
		$this->prenom = $value["prenom"];
		$this->login = $value["login"];
		$this->email = $value["email"];
		$this->is_enable = $value["is_enable"];
		$this->rue = $value["rue"];
		$this->ville = $value["ville"];
		$this->code_postal = $value["code_postal"];
		$this->telephone = $value["telephone"];
		return $this;
	}
	
	public function getLivreur () {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, user.email, user.is_enable, ul.telephone
		FROM users user JOIN user_livreur ul ON ul.uid = user.uid WHERE user.uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getClient", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getClient", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->nom = $value["nom"];
		$this->prenom = $value["prenom"];
		$this->login = $value["login"];
		$this->email = $value["email"];
		$this->is_enable = $value["is_enable"];
		$this->telephone = $value["telephone"];
		
		$sql = "SELECT uld.id, rue, ville, code_postal, latitude, longitude, perimetre, vehicule, id_jour, heure_debut, minute_debut, heure_fin, minute_fin, days.nom 
		FROM user_livreur_dispo uld
		JOIN days ON days.id = id_jour
		WHERE uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : getLivreur", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$dispos = $stmt->fetchAll();
		foreach ($dispos as $disp) {
			$dispo = new Model_Dispo(false);
			$dispo->id = $disp['id'];
			$dispo->rue = $disp['rue'];
			$dispo->ville = $disp['ville'];
			$dispo->code_postal = $disp['code_postal'];
			$dispo->latitude = $disp['latitude'];
			$dispo->longitude = $disp['longitude'];
			$dispo->perimetre = $disp['perimetre'];
			$dispo->vehicule = $disp['vehicule'];
			$dispo->id_jour = $disp['id_jour'];
			$dispo->jour = $disp['nom'];
			$dispo->heure_debut = $disp['heure_debut'];
			$dispo->minute_debut = $disp['minute_debut'];
			$dispo->heure_fin = $disp['heure_fin'];
			$dispo->minute_fin = $disp['minute_fin'];
			$this->dispos[] = $dispo;
		}
		return $this;
	}
	
	public function updateLivreurPosition () {
		$sql = "INSERT INTO user_livreur_position (id_livreur, latitude, longitude, date) 
		(SELECT uid, latitude, longitude, NOW() FROM user_livreur WHERE uid = :id)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : updateLivreurPosition", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE user_livreur SET latitude = :latitude, longitude = :longitude WHERE uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":latitude", $this->latitude);
		$stmt->bindValue(":longitude", $this->longitude);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), "Model_User : updateLivreurPosition", $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
}