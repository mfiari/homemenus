<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Dispo.php";

class Controller_Compte extends Controller_Livreur_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "modifyPassword" :
					$this->modifyPassword($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Compte";
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
		$request->user = $modelUser->getLivreur();
		$request->vue = $this->render("compte.php");
	}
	
	public function modifyPassword ($request) {
		$oldPassword = $_POST['old_password'];
		$newPassword = $_POST['new_password'];
		$confirmPassword = $_POST['confirm_password'];
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
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
		}
		$request->user = $modelUser->getLivreur();
		$request->vue = $this->render("compte.php");
	}
}