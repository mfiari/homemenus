<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";

class Controller_Commande extends Controller_Restaurant_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "validation" :
					$this->validation($request);
					break;
				case "preparation" :
					$this->preparation($request);
					break;
				case "detail" :
					$this->detail($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Index";
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		$request->commandes = $commande->getCommandesRestaurant();
		$request->vue = $this->render("index.php");
	}
	
	public function validation ($request) {
		if (!isset($_GET['id'])) {
			$this->redirect("index", "index");
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->validationRestaurant();
		$this->redirect("index", "index");
	}
	
	public function preparation ($request) {
		if (!isset($_GET['id'])) {
			$this->redirect("index", "index");
		}
		$commande = new Model_Commande();
		$commande->id = $_GET['id'];
		$commande->uid = $request->_auth->id;
		$commande->finPreparationRestaurant();
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