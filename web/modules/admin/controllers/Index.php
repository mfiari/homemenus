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
		
		if (isset($_POST['date_debut'])) {
			$request->date_debut = $_POST['date_debut'];
		} else {
			$request->date_debut = '01/06/2016';
		}
		$dateDebut = datepickerToDatetime($request->date_debut);
		
		if (isset($_POST['date_fin'])) {
			$request->date_fin = $_POST['date_fin'];
		} else {
			$request->date_fin = '30/06/2016';
		}
		$dateFin = datepickerToDatetime($request->date_fin);
		
		$modelCommande = new Model_Commande_History();
		$request->resultats = $modelCommande->getTotal($dateDebut, $dateFin);
		$request->months = $modelCommande->getTotalByMonth($dateDebut, $dateFin);
		$request->livreurs = $modelCommande->getTotalByLivreur($dateDebut, $dateFin);
		$request->restaurants = $modelCommande->getTotalByRestaurant($dateDebut, $dateFin);
		$request->clients = $modelCommande->getTotalByClient($dateDebut, $dateFin);
		$request->villes = $modelCommande->getTotalByVille($dateDebut, $dateFin);
		$request->vue = $this->render("resultats/history.php");
	}
	
	public function logout ($request) {
		session_destroy();
		$this->redirect();
	}
}