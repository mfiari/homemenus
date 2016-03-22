<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/CommandeHistory.php";

class Controller_Index extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->stats($request);
					break;
				case "compte" :
					$this->compte($request);
					break;
				case "stats" :
					$this->stats($request);
					break;
				case "stats_history" :
					$this->stats_history($request);
					break;
				case "logout" :
					$this->logout($request);
					break;
				default :
					$this->redirect('404', '', 'default');
					break;
			}
		} else {
			$this->stats($request);
		}
	}
	
	public function compte ($request) {
		$request->title = "Administration";
		$request->vue = $this->render("index.php");
	}
	
	public function stats ($request) {
		$request->title = "Administration";
		$modelCommande = new Model_Commande();
		$request->resultats = $modelCommande->getTotal();
		$request->livreurs = $modelCommande->getTotalByLivreur();
		$request->restaurants = $modelCommande->getTotalByRestaurant();
		$request->clients = $modelCommande->getTotalByClient();
		$request->villes = $modelCommande->getTotalByVille();
		$request->vue = $this->render("resultats/index.php");
	}
	
	public function stats_history ($request) {
		$request->title = "Administration";
		$modelCommande = new Model_Commande_History();
		$request->resultats = $modelCommande->getTotal();
		$request->livreurs = $modelCommande->getTotalByLivreur();
		$request->restaurants = $modelCommande->getTotalByRestaurant();
		$request->clients = $modelCommande->getTotalByClient();
		$request->villes = $modelCommande->getTotalByVille();
		$request->vue = $this->render("resultats/history.php");
	}
	
	public function logout ($request) {
		session_destroy();
		$this->redirect();
	}
}