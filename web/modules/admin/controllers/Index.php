<?php

include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/CommandeHistory.php";
include_once ROOT_PATH."models/Dispo.php";

class Controller_Index extends Controller_Admin_Template {
	
	public function manage ($request) {
		$this->request = $request;
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
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('resultats/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('resultats/'.$vue.'.php');
	}
	
	public function stats ($request) {
		$request->title = "Administration";
		$modelUser = new Model_User(true, $request->dbConnector);
		$request->livreursDispo = $modelUser->getLivreurAvailableToday();
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$request->resultats = $modelCommande->getTotal();
		$request->livreurs = $modelCommande->getTotalByLivreur();
		$request->restaurants = $modelCommande->getTotalByRestaurant();
		$request->clients = $modelCommande->getTotalByClient();
		$request->villes = $modelCommande->getTotalByVille();
		$request->vue = $this->render("index");
	}
	
	public function stats_history ($request) {
		$request->title = "Administration";
		
		if (isset($_POST['date_debut'])) {
			$request->date_debut = $_POST['date_debut'];
		} else {
			$request->date_debut = '01/'.date('m').'/'.date('Y');
		}
		$dateDebut = datepickerToDatetime($request->date_debut);
		
		if (isset($_POST['date_fin'])) {
			$request->date_fin = $_POST['date_fin'];
		} else {
			$request->date_fin = date('d').'/'.date('m').'/'.date('Y');
		}
		$dateFin = datepickerToDatetime($request->date_fin);
		
		$modelCommande = new Model_Commande_History(true, $request->dbConnector);
		$request->resultats = $modelCommande->getTotal($dateDebut, $dateFin);
		$request->days = $modelCommande->getTotalByDayAndRestaurant($dateDebut, $dateFin);
		$request->months = $modelCommande->getTotalByMonth($dateDebut, $dateFin);
		$request->livreurs = $modelCommande->getTotalByLivreur($dateDebut, $dateFin);
		$request->restaurants = $modelCommande->getTotalByRestaurant($dateDebut, $dateFin);
		$request->clients = $modelCommande->getTotalByClient($dateDebut, $dateFin);
		$request->villes = $modelCommande->getTotalByVille($dateDebut, $dateFin);
		$request->timeRestaurant = $modelCommande->getAvgTimeByRestaurant($dateDebut, $dateFin);
		$request->timeLivreur = $modelCommande->getAvgTimeByLivreur($dateDebut, $dateFin);
		$request->vue = $this->render("history");
	}
	
	public function logout ($request) {
		session_destroy();
		$this->redirect();
	}
}