<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Restaurant.php";

class Controller_Index extends Controller_Default_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->home($request);
					break;
				case "qui_sommes_nous" :
					$this->qui_sommes_nous($request);
					break;
				case "premium" :
					$this->premium($request);
					break;
				case "faq" :
					$this->faq($request);
					break;
				case "inscription" :
					$this->signin($request);
					break;
				case "inscription_sucess" :
					$this->signin_success($request);
					break;
				case "login" :
					$this->login($request);
					break;
				case "logout" :
					$this->logout($request);
					break;
				case "mention_legal" :
					$this->mention_legal($request);
					break;
				case "cgu" :
					$this->cgu($request);
					break;
				case "cgv" :
					$this->cgv($request);
					break;
				case "paiement" :
					$this->paiement($request);
					break;
				case "paiement2" :
					$this->paiement2($request);
					break;
				case "recaptcha" :
					$this->recaptcha($request);
					break;
				case "404" :
					$this->error_404($request);
					break;
				default :
					$this->error_404($request);
					break;
			}
		} else {
			$this->home($request);
		}
	}
	
	public function home ($request) {
		$request->home = true;
		$request->title = "HoMe Menus";
		$request->javascripts = array("res/js/home.js");
		$request->vue = $this->render("home.php");
	}
	
	public function qui_sommes_nous ($request) {
		$request->title = "HoMe Menus - qui sommes nous";
		$request->vue = $this->render("qui_somme_nous.php");
	}
	
	public function premium ($request) {
		$request->title = "premium";
		$request->vue = $this->render("premium.php");
	}
	
	public function faq ($request) {
		$request->title = "HoMe Menus - FAQ";
		$request->vue = $this->render("faq.php");
	}
	
	public function signin ($request) {
		if ($request->request_method == "POST") {
			$errorMessage = array();
			if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
				$errorMessage["EMPTY_NOM"] = "Le nom ne peut être vide";
			} else {
				$request->fieldNom = $_POST["nom"];
			}
			if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
				$errorMessage["EMPTY_PRENOM"] = "Le prénom ne peut être vide";
			} else {
				$request->fieldPrenom = $_POST["prenom"];
			}
			if (!isset($_POST["login"]) || trim($_POST["login"]) == "") {
				$errorMessage["EMPTY_LOGIN"] = "Le login ne peut être vide";
			} else {
				$request->fieldEmail = $_POST["login"];
			}
			if (!isset($_POST["password"]) || trim($_POST["password"]) == "") {
				$errorMessage["EMPTY_PASSWORD"] = "Le mot de passe ne peut être vide";
			}
			$rue = null;
			$ville = null;
			$code_postal = null;
			$telephone = null;
			if (isset($_POST["adresse"]) && trim($_POST["adresse"]) != '') {
				$rue = trim($_POST["rue"]);
				$ville = trim($_POST["ville"]);
				$code_postal = trim($_POST["code_postal"]);
				$request->fieldAdresse = $_POST["adresse"];
				$request->fieldRue = $rue;
				$request->fieldVille = $ville;
				$request->fieldCP = $code_postal;
			}
			if (isset($_POST["telephone"]) && trim($_POST["telephone"]) != '') {
				$telephone = trim($_POST["telephone"]);
				$request->fieldTel = $_POST["telephone"];
			}
			if (count($errorMessage) == 0) {
				$model = new Model_User();
				$model->nom = trim($_POST["nom"]);
				$model->prenom = trim($_POST["prenom"]);
				$model->login = trim($_POST["login"]);
				$model->email = trim($_POST["login"]);
				$model->password = trim($_POST["password"]);
				$model->status = USER_CLIENT;
				$model->rue = $rue;
				$model->ville = $ville;
				$model->code_postal = $code_postal;
				$model->inscription_token = generateToken();
				$model->telephone = $telephone;
				if ($model->isLoginAvailable()) {
					$model->beginTransaction();
					if ($model->save()) {
						$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription.html');
						$messageContent = str_replace("[NOM]", $model->nom, $messageContent);
						$messageContent = str_replace("[PRENOM]", $model->prenom, $messageContent);
						$messageContent = str_replace("[LOGIN]", $model->login, $messageContent);
						$messageContent = str_replace("[UID]", $model->id, $messageContent);
						$messageContent = str_replace("[TOKEN]", $model->inscription_token, $messageContent);
						$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
						
						send_mail ($model->email, "Création de votre compte", $messageContent);
						$this->redirect("inscription_sucess");
					} else {
						$request->errorMessage = array("CREATE_ERROR" => "Une erreur s'est produite, veuillez réessayé ultérieurement.");
					}
					$model->endTransaction();
				} else {
					$request->errorMessage = array("USER_EXISTS" => "l'email ".$model->email." existe déjà");
				}
			} else {
				$request->errorMessage = $errorMessage;
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/compte.js");
		$request->title = "Inscription";
		$request->vue = $this->render("signin.php");
	}
	
	public function signin_success ($request) {
		$request->vue = $this->render("signin_success.php");
	}
	
	public function login ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		if (!isset($_POST['login']) || !isset($_POST['password'])) {
			$this->error(400, "bad request");
		}
		$login = trim($_POST['login']);
		$password = trim($_POST['password']);
		if ($login == '') {
			$this->error(400, "bad request");
		}
		if ($password == '') {
			$this->error(400, "bad request");
		}
		$user = new Model_User();
		if (!$user->login($login, $password)) {
			$this->error(404, "Not found");
		}
		if (!$user->is_enable) {
			$this->error(403, "Not authorized");
		}
		$_SESSION["uid"] = $user->id;
		$_SESSION["session"] = $user->session;
	}
	
	public function mention_legal ($request) {
		$request->vue = $this->render("mention_legal.php");
	}
	
	public function cgu ($request) {
		$request->vue = $this->render("cgu.php");
	}
	
	public function cgv ($request) {
		$request->vue = $this->render("cgv.php");
	}
	
	public function paiement ($request) {
		require_once WEBSITE_PATH.'res/lib/braintree/Braintree.php';
		\Braintree_Configuration::environment('sandbox');
		\Braintree_Configuration::merchantId('f67spz3sxb4rdvfs');
		\Braintree_Configuration::publicKey('nzng9z3d8dmyw84g');
		\Braintree_Configuration::privateKey('39f185c352f34549e1fbc7017e881184');
		$clientToken = Braintree_ClientToken::generate();
		var_dump($clientToken); die();
		$request->vue = $this->render("paiement.php");
	}
	
	public function paiement2 ($request) {
		$request->vue = $this->render("paiement2.php");
	}
	
	public function recaptcha ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		$request->vue = $this->render("recaptcha.php");
	}
	
	public function error_404 ($request) {
		$request->vue = $this->render("404.php");
	}
	
	public function logout ($request) {
		$user = new Model_User();
		$user->id = $_SESSION["uid"];
		if ($user->logout()) {
			session_destroy();
		}
		$this->redirect();
	}
	
	
}