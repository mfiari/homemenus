<?php

include_once MODEL_PATH.'Template.php';
include_once MODEL_PATH.'User.php';

class Controller_User extends Controller_Template {
	
	public function handle() {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "one" :
					$this->getUserById();
					break;
				case "session" :
					$this->getUserBySession();
					break;
				case "login" :
					$this->login();
					break;
				case "logout" :
					$this->logout();
					break;
				case "inscription" :
					$this->inscription();
					break;
				case "livreurReady" :
					$this->livreurReady();
					break;
				case "registerToGcm" :
					$this->registerToGcm();
					break;
				case "updateLivreurPosition" :
					$this->updateLivreurPosition();
					break;
			}
		} else {
			$this->getUserById();
		}
	}
	
	private function getUserById () {
		$model = new Model_User();
		if (isset($_GET["id"])) {
			$id = $_GET["id"];
			$result = $model->getUserById($id);
			if ($result) {
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($row != null) {
					header("Content-type: text/xml; charset=utf-8");
					$dom = new DOMDocument();
					$user = $dom->createElement("user");
					$user->setAttribute("id", $uid);
					$user->setAttribute("email", $row['email']);
					$user->setAttribute("login", $row['login']);
					$user->setAttribute("compte", $row['compte']);
					$dom->appendChild($user);
					print $dom->saveXML();
				} else {
					$this->error(400, "User does not exist.");
				}
			} else {
				$this->error(500, "Request error.");
			}
		} else {
			$this->error(401, "Not authorized.");
		}
	}
	
	private function getUserBySession () {
		if (!isset($_POST["session"])) {
			die();
		}
		$ext = $this->getExtension();
		$session = $_POST["session"];
		$model = new Model_User();
		$result = $model->getUserBySession($session);
		require 'vue/inscription.'.$ext.'.php';
	}
	
	private function login () {
		if (!isset($_POST["login"]) || trim($_POST["login"]) == "") {
			$this->error(400, "Login non renseigné");
			return;
		}
		if (!isset($_POST["password"]) || trim($_POST["password"]) == "") {
			$this->error(400, "Mot de passe non renseigné");
			return;
		}
		$ext = $this->getExtension();
		$login = $_POST["login"];
		$password = $_POST["password"];
		$model = new Model_User();
		$user = $model->login($login, $password);
		if (!$user) {
			$this->error(404, "Login ou mot de passe incorrect");
			return;
		}
		require 'vue/inscription.'.$ext.'.php';
	}
	
	private function logout () {
		if (!isset($_POST["id"]) || trim($_POST["id"]) == "") {
			$this->error(400, "Login non renseigné");
			return;
		}
		$id = $_POST["id"];
		$model = new Model_User();
		$model->id = $id;
		if (!$model->logout()) {
			$this->error(404, "Login ou mot de passe incorrect");
			return;
		}
	}
	
	private function inscription () {
		if (!isset($_POST["nom"])) {
			die();
		}
		if (!isset($_POST["prenom"])) {
			die();
		}
		if (!isset($_POST["login"])) {
			die();
		}
		if (!isset($_POST["password"])) {
			die();
		}
		if (!isset($_POST["status"])) {
			die();
		}
		$nom = $_POST["nom"];
		$prenom = $_POST["prenom"];
		$login = $_POST["login"];
		$password = $_POST["password"];
		$status = $_POST["status"];
		$model = new Model_User();
		$id_user = $model->insert($nom, $prenom, $login, $password, $status);
		if ($status == "restaurant") {
			$id_restaurant = $_POST["id_restaurant"];
			$model->insertRestaurant($id_user, $id_restaurant);
		} else if ($status == "user") {
			$rue = $_POST["rue"];
			$ville = $_POST["ville"];
			$code_postal = $_POST["code_postal"];
			$telephone = $_POST["telephone"];
			$model->insertUser($id_user, $rue, $ville, $code_postal, $telephone);
		} else if ($status == "livreur") {
			$dispo = $_POST["dispo"];
			$model->insertLivreur($id_user, $dispo);
		}
	}
	
	private function livreurReady () {
		if (!isset($_POST["id"])) {
			die();
		}
		$uid = $_POST["id"];
		$model = new Model_User();
		if (!$model->livreurReady($uid)) {
			return;
		}
	}
	
	private function livreurLogout () {
		if (!isset($_POST["id"])) {
			die();
		}
		$uid = $_POST["id"];
		$model = new Model_User();
		if (!$model->livreurLogout($uid)) {
			return;
		}
	}
	
	private function registerToGcm () {
		var_dump($_POST);
		echo "id : ";
		if (!isset($_POST["id"])) {
			$this->error(404, "Login ou mot de passe incorrect");
			return;
		}
		echo $_POST["id"];
		echo "gcm_token : ";
		if (!isset($_POST["gcm_token"])) {
			$this->error(404, "Login ou mot de passe incorrect");
			return;
		}
		echo $_POST["gcm_token"];
		$model = new Model_User();
		$model->id = $_POST["id"];
		$model->gcm_token = $_POST["gcm_token"];
		if (!$model->registerToGcm()) {
			return;
		}
	}
	
	private function updateLivreurPosition () {
		$id_livreur = $_POST['livreur'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$model = new Model_User();
		$model->id = $id_livreur;
		$model->latitude = $latitude;
		$model->longitude = $longitude;
		$model->updateLivreurPosition();
	}
}
