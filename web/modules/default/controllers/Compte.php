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
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "modify_password" :
					$this->modify_password($request);
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
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('compte/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('compte/'.$vue.'.php');
	}
	
	public function index ($request) {
		if ($request->_auth) {
			$request->title = "Compte";
			if ($request->request_method == "POST") {
				$modelUser = new Model_User();
				$modelUser->id = $request->_auth->id;
				$oldUser = $modelUser->getById();
				if (isset($_POST["nom"]) && trim($_POST["nom"]) != '') {
					$modelUser->nom = $_POST["nom"];
				}
				if (isset($_POST["prenom"]) && trim($_POST["prenom"]) != '') {
					$modelUser->prenom = $_POST["prenom"];
				}
				if (isset($_POST["login"]) && trim($_POST["login"]) != '') {
					$modelUser->login = $_POST["login"];
				}
				if (isset($_POST["email"]) && trim($_POST["email"]) != '') {
					$modelUser->email = $_POST["email"];
				}
				if (isset($_POST["adresse"]) && trim($_POST["adresse"]) == '') {
					$modelUser->rue = '';
					$modelUser->ville = '';
					$modelUser->code_postal = '';
				} else {
					if (isset($_POST["rue"]) && trim($_POST["rue"]) != '') {
						$modelUser->rue = $_POST["rue"];
					}
					if (isset($_POST["ville"]) && trim($_POST["ville"]) != '') {
						$modelUser->ville = $_POST["ville"];
					}
					if (isset($_POST["code_postal"]) && trim($_POST["code_postal"]) != '') {
						$modelUser->code_postal = $_POST["code_postal"];
					}
				}
				if (isset($_POST["telephone"])) {
					$modelUser->telephone = $_POST["telephone"];
				}
				if ($modelUser->save()) {
					$request->user = $modelUser;
					$request->successMessage = "Votre compte a bien été modifié.";
				} else {
					$request->errorMessage = array("Une erreur est survenue lors de la modification des données. Veuillez réessayer ultérieurement.");
					$request->user = $oldUser;
				}
			} else {
				$modelUser = new Model_User();
				$modelUser->id = $request->_auth->id;
				$request->user = $modelUser->getById();
			}
			$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places");
			$request->vue = $this->render("compte");
		} else {
			$this->redirect('inscription');
		}
	}
	
	public function modify_password ($request) {
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
		if ($request->request_method == "POST") {
			$errors = array();
			$oldPassword = $_POST['old_password'];
			$newPassword = $_POST['new_password'];
			$confirmPassword = $_POST['confirm_password'];
			$errors = array();
			if ($newPassword != $confirmPassword) {
				$errors["DIFFERENT_PASSWORD"] = "Les champs mot de passe et confirmations sont différents.";
			} else {
				
				if ($modelUser->getByLoginAndPassword($request->_auth->login, $oldPassword) === false) {
					$errors["WRONG_PASSWORD"] = "Le mot de passe saisie est incorrect";
				} else {
					if ($modelUser->modifyPassword($newPassword) === false) {
						$errors["MODIFY_ERROR"] = "Erreur lors de la modification du mot de passe, veuillez réessayer";
					} else {
						$request->modifyPasswordSuccess = true;
					}
				}
			}
			if (count($errors) > 0) {
				$request->errorMessage = $errors;
			} else {
				$request->successMessage = "Votre mot de passe a bien été modifié.";
			}
		}
		$request->user = $modelUser->getById();
		$request->vue = $this->render("compte");
	}
	
	public function calendrier ($request) {
		$request->title = "Compte";
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
		$request->user = $modelUser->getById();
		$request->javascripts = array("res/js/calendar.js");
		$request->vue = $this->render("calendrier");
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
			$request->vue = $this->render("confirmation_inscription_success");
		} else {
			$request->vue = $this->render("confirmation_inscription_fail");
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
			$errors = array();
			$modelUser = new Model_User();
			$modelUser->id = trim($_POST["uid"]);
			$newPassword = trim($_POST["password"]);
			$confirmPassword = trim($_POST["confirm_password"]);
			if ($newPassword != $confirmPassword) {
				$errors["DIFFERENT_PASSWORD"] = "Les champs mot de passe et confirmations sont différents.";
			} else {
				if ($modelUser->modifyPassword($newPassword) === false) {
					$errors["MODIFY_ERROR"] = "Erreur lors de la modification du mot de passe, veuillez réessayer";
				} else {
					$request->modifyPasswordSuccess = true;
				}
			}
			if (count($errors) > 0) {
				$request->errorMessage = $errors;
				$request->vue = $this->render("reset_password");
			} else {
				$request->vue = $this->render("reset_password_succes");
			}
		} else if ($request->request_method == "GET") {
			$request->vue = $this->render("reset_password");
		}
	}
}