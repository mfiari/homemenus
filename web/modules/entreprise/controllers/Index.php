<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Carte.php";

class Controller_Index extends Controller_Entreprise_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "calendrier" :
					$this->calendrier($request);
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
		$request->title = "Administration";
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		if ($request->_auth->status == USER_ADMIN_ENTREPRISE) {
			$request->commandes = $commande->getAllCommandesEntrepriseGroupe();
		} else {
			$request->commandes = $commande->getAllCommandesEntreprise();
		}
		$request->vue = $this->render("index.php");
	}
	
	public function calendrier ($request) {
		$request->title = "Compte";
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
		$request->user = $modelUser->getById();
		$request->javascripts = array("res/js/calendar2.js");
		$request->vue = $this->render("calendrier.php");
	}
	
	public function logout ($request) {
		session_destroy();
		$this->redirect();
	}
}