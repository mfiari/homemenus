<?php

include_once MODEL_PATH."TransfertPaiement.php";
include_once MODEL_PATH."CommandeHistory.php";

class Controller_Index extends Controller_TicketResto_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "compte" :
					$this->compte($request);
					break;
				case "modify_password" :
					$this->modify_password($request);
					break;
				case "logout" :
					$this->logout($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		if (isset($_GET['date_debut'])) {
			$request->date_debut = $_GET['date_debut'];
		} else {
			$request->date_debut = '01/'.date('m').'/'.date('Y');
		}
		$dateDebut = datepickerToDatetime($request->date_debut);
		
		if (isset($_GET['date_fin'])) {
			$request->date_fin = $_GET['date_fin'];
		} else {
			$request->date_fin = date('d').'/'.date('m').'/'.date('Y');
		}
		$dateFin = datepickerToDatetime($request->date_fin);
		
		$modelTransfertPaiement = new Model_TransfertPaiement(true, $request->dbConnector);
		$request->result = $modelTransfertPaiement->getTitreRestaurant($dateDebut, $dateFin);
		
		$modelCommandeHistory = new Model_Commande_History(true, $request->dbConnector);
		$request->restaurants = $modelCommandeHistory->getTitreRestaurant($dateDebut, $dateFin);
		
		$request->title = "Ticket restaurant";
		$request->vue = $this->render("index.php");
	}
	
	public function compte ($request) {
		if ($request->_auth) {
			$request->title = "Compte";
			if ($request->request_method == "POST") {
				$modelUser = new Model_User(true, $request->dbConnector);
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
				if ($modelUser->save()) {
					$request->user = $modelUser;
					$request->successMessage = "Votre compte a bien été modifié.";
				} else {
					$request->errorMessage = array("Une erreur est survenue lors de la modification des données. Veuillez réessayer ultérieurement.");
					$request->user = $oldUser;
				}
			} else {
				$modelUser = new Model_User(true, $request->dbConnector);
				$modelUser->id = $request->_auth->id;
				$request->user = $modelUser->getById();
			}
			$request->vue = $this->render("compte.php");
		} else {
			$this->redirect();
		}
	}
	
	public function modify_password ($request) {
		$modelUser = new Model_User(true, $request->dbConnector);
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
		$request->vue = $this->render("compte.php");
	}
	
	public function logout ($request) {
		$user = new Model_User(true, $request->dbConnector);
		$user->id = $request->_auth->id;
		if ($user->logout()) {
			session_destroy();
		}
		$this->redirect();
	}
}