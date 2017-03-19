<?php

class Model_User extends Model_Template {
	
	protected $id;
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
	private $parametre;
	
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		return $value === false;
	}
	
	public function get () {
		$sql = "SELECT user.nom, user.prenom, us.gcm_token
		FROM users user
		JOIN user_session us ON us.uid = user.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE user.uid = :uid
		LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		$this->prenom = $value['prenom'];
		$this->gcm_token = $value['gcm_token'];
		return $this;
	}
	
	public function getById () {
		$sql = "SELECT user.nom, user.prenom, user.status, user.login, user.email, user.is_premium, uc.rue, uc.ville, uc.code_postal, uc.telephone, 
		up.default_adresse_search, up.send_mail_commande, up.send_sms_commande
		FROM users user
		LEFT JOIN user_client uc ON uc.uid = user.uid
		LEFT JOIN user_parametre up ON up.uid = user.uid
		WHERE user.uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
		
		$parameter = new Model_Parametre();
		$parameter->default_adresse_search = $value["default_adresse_search"];
		$parameter->send_mail_commande = $value["send_mail_commande"];
		$parameter->send_sms_commande = $value["send_sms_commande"];
		
		$this->parametre = $parameter;
		
		return $this;
	}
	
	public function getBySession ($uid, $session) {
		$sql = "SELECT user.nom, user.prenom, user.status, user.login, uc.rue, uc.ville, uc.code_postal, uc.telephone, user.is_premium, 
		up.default_adresse_search, up.send_mail_commande, up.send_sms_commande
		FROM users user
		JOIN user_session us ON us.uid = user.uid
		LEFT JOIN user_client uc ON uc.uid = user.uid
		LEFT JOIN user_parametre up ON up.uid = user.uid
		WHERE user.uid = :uid AND us.session_key = :session AND user.is_login = true";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		$stmt->bindValue(":session", $session);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
		
		$parameter = new Model_Parametre();
		$parameter->default_adresse_search = $value["default_adresse_search"];
		$parameter->send_mail_commande = $value["send_mail_commande"];
		$parameter->send_sms_commande = $value["send_sms_commande"];
		
		$this->parametre = $parameter;
		
		return $this;
	}
	
	public function getByLoginAndPassword($login, $password) {
		$sql = "SELECT uid, nom, prenom, status, is_enable FROM users WHERE login = :login AND password = sha1(:password)";
		$params = array (
			":login" => $login,
			":password" => $password
		);
		$value = $this->executeSql($sql, $params, PDO::FETCH_ASSOC);
		return $value !== false;
	}
	
	public function getByLogin () {
		$sql = "SELECT uid, email, is_enable FROM users WHERE login = :login";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":login", $this->login);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
		} else {
			return $this->update();
		}
		return false;
	}
	
	public function saveParameters () {
		$sql = "UPDATE user_parametre SET default_adresse_search = :default_adresse_search, send_mail_commande = :send_mail_commande, send_sms_commande = :send_sms_commande
		WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":default_adresse_search", $this->parametre->default_adresse_search);
		$stmt->bindValue(":send_mail_commande", $this->parametre->send_mail_commande);
		$stmt->bindValue(":send_sms_commande", $this->parametre->send_sms_commande);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->id = $this->db->lastInsertId();
		$sql = "INSERT INTO user_parametre (uid) VALUES (:uid)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function update() {
		$sql = "UPDATE users SET nom = :nom, prenom = :prenom, login = :login, email = :email WHERE uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":nom", $this->nom);
		$stmt->bindValue(":prenom", $this->prenom);
		$stmt->bindValue(":login", $this->login);
		$stmt->bindValue(":email", $this->email);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		if ($this->status == USER_LIVREUR) {
			return $this->updateLivreur();
		} else if ($this->status == USER_CLIENT) {
			return $this->updateUser();
		} else if ($this->status == USER_RESTAURANT || $this->status == USER_ADMIN_RESTAURANT) {
			return $this->updateRestaurant();
		}
		return false;
	}
	
	public function updateUser() {
		$sql = "UPDATE user_client SET rue = :rue, ville = :ville, code_postal = :code_postal, telephone = :telephone WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":rue", $this->rue);
		$stmt->bindValue(":ville", $this->ville);
		$stmt->bindValue(":code_postal", $this->code_postal);
		$stmt->bindValue(":telephone", $this->telephone);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function updateLivreur() {
		$sql = "UPDATE user_livreur SET telephone = :telephone WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":telephone", $this->telephone);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE users SET is_enable = true WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function deleted () {
		$sql = "UPDATE users SET deleted = true, date_suppression = NOW() WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function login($login, $password) {
		$sql = "SELECT uid, nom, prenom, status, is_enable FROM users WHERE login = :login AND password = sha1(:password)";
		
		$params = array (
			":login" => $login,
			":password" => $password
		);
		$value = $this->executeSql($sql, $params, PDO::FETCH_ASSOC);
		if ($value === false) {
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
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				$this->sqlHasFailed = true;
				return false;
			}
		
			$sql = "UPDATE users SET is_login = true WHERE uid = :uid";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":uid", $value["uid"]);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
					writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null || $value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE users SET is_login = false WHERE uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE user_session SET date_logout = NOW() WHERE uid = :id AND date_logout IS NULL";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$status = $value['status'];
		if ($status == USER_LIVREUR) {
			$sql = "UPDATE user_livreur SET is_ready = false WHERE uid = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id", $this->id);
			if (!$stmt->execute()) {
				writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
				$this->sqlHasFailed = true;
				return false;
			}
		}
		return true;
	}
	
	public function modifyPassword ($newPassword) {
		$sql = "UPDATE users SET password = sha1(:password) WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":password", $newPassword);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function getByRestaurant ($id_restaurant) {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, user.status, user.is_login, user.is_enable 
		FROM users user
		JOIN user_restaurant ur ON ur.uid = user.uid
		WHERE ur.id_restaurant = :id AND user.deleted = 0";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
	}
	
	public function livreurLogout ($uid) {
		$sql = "UPDATE user_livreur SET is_ready = false WHERE uid = :uid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $uid);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
	}
	
	public function getAllActifLivreur () {
		$sql = "SELECT user.uid, user.login, user.is_login, us.gcm_token
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		LEFT JOIN user_session us ON us.uid = user.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE user.is_enable = 1 
		GROUP BY user.login";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
	
	public function getLivreurAvailableToday () {
		$sql = "SELECT user.uid, user.login, user.nom, user.prenom, uld.heure_debut, uld.minute_debut, uld.heure_fin, uld.minute_fin
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		WHERE user.is_enable = 1 AND uld.id_jour = (WEEKDAY(CURRENT_DATE)+1) ";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$user->login = $livreur['login'];
			$user->nom = $livreur['nom'];
			$user->prenom = $livreur['prenom'];
			
			$dispo = new Model_Dispo(false);
			$dispo->heure_debut = $livreur['heure_debut'];
			$dispo->minute_debut = $livreur['minute_debut'];
			$dispo->heure_fin = $livreur['heure_fin'];
			$dispo->minute_fin = $livreur['minute_fin'];
			$user->dispos = $dispo;
			
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(true, $this->db);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
	
	public function getLivreurAvailableTodayForRestaurant () {
		$sql = "SELECT user.uid, user.login, user.nom, user.prenom, uld.heure_debut, uld.minute_debut, uld.heure_fin, uld.minute_fin, dlr.perimetre, 
		resto.id, resto.nom AS nom_resto
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		JOIN distance_livreur_resto dlr ON dlr.id_dispo = uld.id
		JOIN restaurants resto ON resto.id = dlr.id_restaurant
		WHERE user.is_enable = 1 AND resto.enabled = 1 AND resto.deleted = 0 AND uld.id_jour = (WEEKDAY(CURRENT_DATE)+1)";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$user->login = $livreur['login'];
			$user->nom = $livreur['nom'];
			$user->prenom = $livreur['prenom'];
			
			$dispo = new Model_Dispo(false);
			$dispo->heure_debut = $livreur['heure_debut'];
			$dispo->minute_debut = $livreur['minute_debut'];
			$dispo->heure_fin = $livreur['heure_fin'];
			$dispo->minute_fin = $livreur['minute_fin'];
			$dispo->perimetre = $livreur['perimetre'];
			
			$restaurant = new Model_Restaurant(false);
			$restaurant->id = $livreur['heure_debut'];
			$restaurant->nom = $livreur['nom_resto'];
			
			$dispo->restaurants = $restaurant;
			
			$user->dispos = $dispo;
			
			$list[] = $user;
		}
		return $list;
	}
	
	public function getLivreurAvailableWeek () {
		$sql = "SELECT user.uid, user.login, user.nom, user.prenom, uld.id_jour, uld.heure_debut, uld.minute_debut, uld.heure_fin, uld.minute_fin, days.nom AS jour
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		JOIN days ON days.id = uld.id_jour
		WHERE user.is_enable = 1
		ORDER BY uld.id_jour, user.nom, user.prenom, uld.heure_debut, uld.minute_debut, uld.heure_fin, uld.minute_fin";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$livreurs = $stmt->fetchAll();
		$list = array();
		foreach ($livreurs as $livreur) {
			$user = new Model_User(false);
			$user->id = $livreur['uid'];
			$user->login = $livreur['login'];
			$user->nom = $livreur['nom'];
			$user->prenom = $livreur['prenom'];
			
			$dispo = new Model_Dispo(false);
			$dispo->id_jour = $livreur['id_jour'];
			$dispo->jour = $livreur['jour'];
			$dispo->heure_debut = $livreur['heure_debut'];
			$dispo->minute_debut = $livreur['minute_debut'];
			$dispo->heure_fin = $livreur['heure_fin'];
			$dispo->minute_fin = $livreur['minute_fin'];
			
			$user->dispos = $dispo;
			
			$list[] = $user;
		}
		return $list;
	}
	
	public function getLivreurAvailableForCommande ($commande) {
		$sql = "SELECT user.uid, user.login, user.is_login, ul.telephone, us.gcm_token, uld.heure_debut, uld.minute_debut, uld.heure_fin, uld.minute_fin, 
		up.send_sms_commande, up.send_notification_commande
		FROM users user
		JOIN user_parametre up ON up.uid = user.uid
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		JOIN distance_livreur_resto dlr ON dlr.id_restaurant = :restaurant AND dlr.id_dispo = uld.id AND dlr.perimetre <= uld.perimetre
		LEFT JOIN user_session us ON us.uid = user.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE user.is_enable = 1 AND uld.id_jour = (WEEKDAY(CURRENT_DATE)+1) ";
		if ($commande->heure_souhaite == -1) {
			$sql.= "AND (uld.heure_debut > HOUR(CURRENT_TIME) OR (uld.heure_debut < HOUR(CURRENT_TIME) AND uld.heure_fin >= HOUR(CURRENT_TIME)))";
		} else {
			$heure = $commande->heure_souhaite;
			$sql.= "AND (uld.heure_debut > ".$heure." OR (uld.heure_debut < ".$heure." AND uld.heure_fin > ".$heure."))";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $commande->restaurant->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			$user->telephone = $livreur['telephone'];
			$user->gcm_token = $livreur['gcm_token'];
		
			$parameter = new Model_Parametre();
			$parameter->send_sms_commande = $livreur["send_sms_commande"];
			$parameter->send_notification_commande = $livreur["send_notification_commande"];
			
			$user->parametre = $parameter;
			
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
	
	public function getLivreurAvailableForCommandeAdmin ($commande) {
		$sql = "SELECT user.uid, user.login, user.is_login, us.gcm_token, uld.heure_debut, uld.minute_debut, uld.heure_fin, uld.minute_fin
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_livreur_dispo uld ON uld.uid = user.uid
		JOIN distance_livreur_resto dlr ON dlr.id_restaurant = :restaurant AND dlr.id_dispo = uld.id AND dlr.perimetre <= uld.perimetre
		JOIN user_session us ON us.uid = user.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE user.is_enable = 1 AND uld.id_jour = (WEEKDAY(CURRENT_DATE)+1) ";
		if ($commande->heure_souhaite == -1) {
			$sql.= "AND (uld.heure_debut > HOUR(CURRENT_TIME) OR (uld.heure_debut < HOUR(CURRENT_TIME) AND uld.heure_fin >= HOUR(CURRENT_TIME)))";
		} else {
			$heure = $commande->heure_souhaite;
			$sql.= "AND (uld.heure_debut > ".$heure." OR (uld.heure_debut < ".$heure." AND uld.heure_fin > ".$heure."))";
		}
		$sql.= " GROUP BY user.login";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $commande->restaurant->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
		$sql = "SELECT users.uid, users.email, us.gcm_token, ur.telephone FROM users 
		JOIN user_restaurant ur ON ur.uid = users.uid
		LEFT JOIN user_session us ON us.uid = users.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE ur.id_restaurant = :restaurant AND users.is_login = true
		GROUP BY us.gcm_token";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":restaurant", $id_restaurant);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$users = $stmt->fetchAll();
		$list = array();
		foreach ($users as $usr) {
			$user = new Model_User(false);
			$user->id = $usr['uid'];
			$user->email = $usr['email'];
			$user->gcm_token = $usr['gcm_token'];
			$user->telephone = $usr['telephone'];
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function getAllLivreurs () {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, user.email, user.is_enable, ul.is_ready 
		FROM users user JOIN user_livreur ul ON ul.uid = user.uid WHERE deleted = 0 AND user.status = 'LIVREUR'
		ORDER BY user.is_enable DESC";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, uc.code_postal, uc.ville, uc.telephone, user.is_enable, user.is_premium
		FROM users user JOIN user_client uc ON uc.uid = user.uid WHERE deleted = 0 AND user.status = 'USER'";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			$user->telephone = $usr['telephone'];
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
	
	public function getNouveauClient () {
		$sql = "SELECT uid, nom, prenom, login, is_enable, is_premium
		FROM users
		WHERE deleted = 0 AND status = 'USER' AND DATE(date_creation) = DATE(NOW())";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			$user->is_enable = $usr['is_enable'];
			$user->is_premium = $usr['is_premium'];
			$list[] = $user;
		}
		return $list;
	}
	
	public function countClients () {
		$sql = "SELECT COUNT(*) AS total
		FROM users
		WHERE deleted = 0 AND status = 'USER' AND DATE(date_creation) < DATE(NOW())";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return $value["total"];
	}
	
	public function getClientBeforeDate ($dateDebut) {
		$sql = "SELECT COUNT(*) AS total
		FROM users
		WHERE deleted = 0 AND status = 'USER' AND DATE(date_creation) < :date_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return $value["total"];
	}
	
	public function getNouveauClientByMonth ($dateDebut, $dateFin) {
		$sql = "SELECT YEAR(date_creation) AS year, MONTH(date_creation) AS month, COUNT(*) AS total
		FROM users
		WHERE deleted = 0 AND status = 'USER' AND date_creation BETWEEN :date_debut AND :date_fin
		GROUP BY year, month";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date_debut", $dateDebut);
		$stmt->bindValue(":date_fin", $dateFin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return $stmt->fetchAll();
	}
	
	public function getLivreur () {
		$sql = "SELECT user.uid, user.nom, user.prenom, user.login, user.email, user.status, user.is_enable, ul.telephone
		FROM users user JOIN user_livreur ul ON ul.uid = user.uid WHERE user.uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$this->nom = $value["nom"];
		$this->prenom = $value["prenom"];
		$this->login = $value["login"];
		$this->email = $value["email"];
		$this->status = $value["status"];
		$this->is_enable = $value["is_enable"];
		$this->telephone = $value["telephone"];
		
		$sql = "SELECT uld.id, rue, ville, code_postal, latitude, longitude, perimetre, vehicule, id_jour, heure_debut, minute_debut, heure_fin, minute_fin, days.nom 
		FROM user_livreur_dispo uld
		JOIN days ON days.id = id_jour
		WHERE uid = :id
		ORDER BY id_jour, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
	
	public function getLivreurInfo () {
		$sql = "SELECT user.nom, user.prenom, us.gcm_token, ul.telephone, up.send_sms_commande, up.send_notification_commande
		FROM users user
		JOIN user_livreur ul ON ul.uid = user.uid
		JOIN user_parametre up ON up.uid = user.uid
		LEFT JOIN user_session us ON us.uid = user.uid AND date_logout = '0000-00-00 00:00:00'
		WHERE user.uid = :uid
		LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nom = $value['nom'];
		$this->prenom = $value['prenom'];
		$this->telephone = $value['telephone'];
		$this->gcm_token = $value['gcm_token'];
		
		$parameter = new Model_Parametre();
		$parameter->send_sms_commande = $value["send_sms_commande"];
		$parameter->send_notification_commande = $value["send_notification_commande"];
		$this->parametre = $parameter;
		
		return $this;
	}
	
	public function getLivreurDispo () {
		$sql = "SELECT uld.id, rue, ville, code_postal, latitude, longitude, perimetre, vehicule, id_jour, heure_debut, minute_debut, heure_fin, minute_fin, 
		days.nom 
		FROM user_livreur_dispo uld
		JOIN days ON days.id = id_jour
		WHERE uid = :id AND id_jour = WEEKDAY(CURRENT_DATE)+1
		ORDER BY id_jour, heure_debut";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
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
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE user_livreur SET latitude = :latitude, longitude = :longitude WHERE uid = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":id", $this->id);
		$stmt->bindValue(":latitude", $this->latitude);
		$stmt->bindValue(":longitude", $this->longitude);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function getNbCommandeLivreur ($debut, $fin) {
		$sql = "SELECT COUNT(*) AS total FROM commande_history WHERE id_livreur = :livreur AND date_livraison BETWEEN :debut AND :fin";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":livreur", $this->id);
		$stmt->bindValue(":debut", $debut);
		$stmt->bindValue(":fin", $fin);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			return 0;
		}
		return $value["total"];
	}
	
	public function closeSessionBeforeDate ($date) {
		$sql = "UPDATE user_session SET date_logout = NOW() WHERE date_login < :date AND date_logout IS NULL";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":date", $date);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE users SET is_login = false WHERE uid IN (SELECT DISTINCT uid FROM user_session WHERE date_logout IS NOT NULL)";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$sql = "UPDATE user_livreur SET is_ready = false WHERE uid IN (SELECT DISTINCT uid FROM user_session WHERE date_logout IS NOT NULL)";
		$stmt = $this->db->prepare($sql);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		return true;
	}
	
	public function getLastPosition () {
		$sql = "SELECT latitude, longitude FROM user_livreur_position WHERE id_livreur = :uid ORDER BY date DESC LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(":uid", $this->id);
		if (!$stmt->execute()) {
			writeLog(SQL_LOG, $stmt->errorInfo(), LOG_LEVEL_ERROR, $sql);
			$this->sqlHasFailed = true;
			return false;
		}
		$value = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($value == null ||$value == false) {
			return false;
		}
		$this->latitude = $value["latitude"];
		$this->longitude = $value["longitude"];
		return $this;
	}
	
	public function isAdmin () {
		return $this->status == USER_ADMIN || $this->status == USER_ADMIN_INFO || $this->status == USER_ADMIN_CLIENT;
	}
}