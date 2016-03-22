<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";

class Controller_Index extends Controller_Restaurant_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "enCours" :
					$this->enCours($request);
					break;
				case "nonVue" :
					$this->nonVue($request);
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
		$request->title = "Comande reçu";
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		$request->commandes = $commande->getCommandesRestaurant();
		$request->vue = $this->render("index.php");
	}
	
	public function enCours ($request) {
		$request->disableLayout = true;
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		$request->commandes = $commande->getCommandesRestaurant();
		$request->vue = $this->render("enCours.php");
	}
	
	public function nonVue ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		$count = $commande->getCommandeRestaurantNonVue();
		if ($count !== false) {
			echo $count;
		}
	}
	
	public function logout ($request) {
		session_destroy();
		$this->redirect();
	}
}