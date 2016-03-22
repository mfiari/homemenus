<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";

class Controller_Commande extends Controller_Livreur_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "validation" :
					$this->validation($request);
					break;
				case "recuperation" :
					$this->recuperation($request);
					break;
				case "livraison" :
					$this->livraison($request);
					break;
				case "detail" :
					$this->detail($request);
					break;
			}
		}
	}
	
	public function validation ($request) {
		if (!isset($_GET['id'])) {
			return;
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->validationLivreur();
	}
	
	public function recuperation ($request) {
		if (!isset($_GET['id'])) {
			return;
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->recuperationLivreur();
		$this->redirect("index", "index");
	}
	
	public function livraison ($request) {
		if (!isset($_GET['id'])) {
			return;
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->livraison();
		$this->redirect("index", "index");
	}
	
	public function detail ($request) {
		if (!isset($_GET['id'])) {
			$this->redirect();
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$request->commande = $commande->getCommandeRestaurant();
		$request->vue = $this->render("commande.php");
	}
}