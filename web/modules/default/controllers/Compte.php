<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Tag.php";
include_once ROOT_PATH."models/Option.php";
include_once ROOT_PATH."models/OptionValue.php";
include_once ROOT_PATH."models/Accompagnement.php";
include_once ROOT_PATH."models/PreCommande.php";

class Controller_Compte extends Controller_Default_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "activation" :
					$this->activation($request);
					break;
				case "forgot_password" :
					$this->forgot_password($request);
					break;
				case "reset_password" :
					$this->reset_password($request);
					break;
				case "solde" :
					$this->solde($request);
					break;
				case "calendrier" :
					$this->calendrier($request);
					break;
				default :
					$this->redirect('404');
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		if ($request->_auth) {
			$request->title = "Compte";
			$modelUser = new Model_User();
			$modelUser->id = $request->_auth->id;
			$request->user = $modelUser->getById();
			$request->vue = $this->render("compte.php");
		} else {
			$this->redirect('inscription');
		}
	}
	
	public function calendrier ($request) {
		$request->title = "Compte";
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
		$request->user = $modelUser->getById();
		$request->javascripts = array("res/js/calendar.js");
		$request->vue = $this->render("compte/calendrier.php");
	}
	
	public function activation ($request) {
		$model = new Model_User();
		$model->id = trim($_GET["uid"]);
		$model->inscription_token = trim($_GET["token"]);
		if ($model->confirm()) {
			$model->getById();
			$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription_admin.html');
					
			$messageContent = str_replace("[PRENOM]", $model->prenom, $messageContent);
			$messageContent = str_replace("[NOM]", $model->nom, $messageContent);
			if ($model->ville != '') {
				$messageContent = str_replace("[ADRESSE]", $model->ville.' ('.$model->code_postal.')', $messageContent);
			} else {
				$messageContent = str_replace("[ADRESSE]", "(adresse non renseignée)", $messageContent);
			}
			send_mail (MAIL_ADMIN, "création de compte", $messageContent);
			
			$_SESSION["uid"] = $model->id;
			$_SESSION["session"] = $model->session;
			$request->vue = $this->render("confirmation_inscription_success.php");
		} else {
			$request->vue = $this->render("confirmation_inscription_fail.php");
		}
	}
	
	public function forgot_password ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
		}
		if (!isset($_POST['login'])) {
			$this->error(400, "bad request");
		}
		$model = new Model_User();
		$model->login = trim($_POST["login"]);
		$user = $model->getByLogin();
		if ($user) {
			if (!$user->is_enable) {
				$this->error(403, "Not authorized");
			}
			$token = generateToken();
			$messageContent =  file_get_contents (ROOT_PATH.'mails/forgot_password.html');
			$messageContent = str_replace("[UID]", $model->id, $messageContent);
			$messageContent = str_replace("[TOKEN]", $token, $messageContent);
			$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
					
			send_mail ($model->email, "Changement de mot de passe", $messageContent);
			
		} else {
			$this->error(404, "Not found");
		}
	}
	
	public function reset_password ($request) {
		if ($request->request_method == "POST") {
			$model = new Model_User();
			$model->id = trim($_POST["uid"]);
			$model->password = trim($_POST["password"]);
			$model->changePassword();
		} else if ($request->request_method == "GET") {
			$request->vue = $this->render("reset_password.php");
		}
	}
}