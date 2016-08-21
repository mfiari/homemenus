<?php

include_once MODEL_PATH."Template.php";
include_once MODEL_PATH."CommandeHistory.php";
include_once MODEL_PATH."Restaurant.php";
include_once MODEL_PATH."Commentaire.php";

class Controller_Notes extends Controller_Default_Template {
	
	public function manage ($request) {
		if (!$request->_auth) {
			$this->redirect();
		}
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "noter" :
					$this->noter($request);
					break;
				case "restaurants" :
					$this->restaurants($request);
					break;
				case "noterRestaurant" :
					$this->noterRestaurant($request);
					break;
				case "reset_password" :
					$this->reset_password($request);
					break;
				case "solde" :
					$this->solde($request);
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
			$mobileVue = parent::render('notes/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('notes/'.$vue.'.php');
	}
	
	public function index ($request) {
		$request->title = "Notes";
		$modelCommande = new Model_Commande_History();
		$modelCommande->uid = $request->_auth->id;
		$request->commandes = $modelCommande->getByUser();
		$request->javascripts = array("res/js/bootstrap-star-rating.js");
		$request->vue = $this->render("index");
	}
	
	public function noter ($request) {
		if ($request->request_method == "POST") {
			$modelCommande = new Model_Commande_History();
			$modelCommande->id = $_POST['id_commande'];
			$modelCommande->note = $_POST['note'];
			$modelCommande->commentaire = $_POST['commentaire'];
			$modelCommande->commentaire_anonyme = $_POST['anonyme'] == 'true' ? true : false;
			$modelCommande->noter();
		}
	}
	
	public function restaurants ($request) {
		$request->title = "Notes";
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->user = $request->_auth;
		$request->restaurants = $modelRestaurant->getCommentaireByUser();
		$request->javascripts = array("res/js/bootstrap-star-rating.js");
		$request->vue = $this->render("restaurants");
	}
	
	public function noterRestaurant ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $_POST['id_restaurant'];
			$modelRestaurant->user = $request->_auth;
			$modelCommantaire= new Model_Commentaire();
			$modelCommantaire->note = $_POST['note'];
			$modelCommantaire->commentaire = $_POST['commentaire'];
			$modelCommantaire->anonyme = $_POST['anonyme'] == 'true' ? true : false;
			$modelRestaurant->commentaire = $modelCommantaire;
			$modelRestaurant->noter();
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