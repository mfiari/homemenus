<?php

include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/CommandeHistory.php";
include_once ROOT_PATH."models/Dispo.php";
include_once ROOT_PATH."models/Restaurant.php";

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
				case "restaurant" :
					$this->restaurant($request);
					break;
				case "livreur" :
					$this->livreur($request);
					break;
				case "client" :
					$this->client($request);
					break;
				case "ville" :
					$this->ville($request);
					break;
				case "stats_history" :
					$this->stats_history($request);
					break;
				case "restaurant_history" :
					$this->restaurant_history($request);
					break;
				case "livreur_history" :
					$this->livreur_history($request);
					break;
				case "client_history" :
					$this->client_history($request);
					break;
				case "ville_history" :
					$this->ville_history($request);
					break;
				case "jour_history" :
					$this->jour_history($request);
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
		$request->nouveauClients = $modelUser->getNouveauClient();
		$request->nbClients = $modelUser->countClients();
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$request->restaurants = $modelRestaurant->getRestaurantSansLivreur();
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$request->resultats = $modelCommande->getTotal();
		$request->vue = $this->render("index");
	}
	
	public function restaurant ($request) {
		$request->title = "Administration";
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$request->restaurants = $modelCommande->getTotalByRestaurant();
		$request->vue = $this->render("restaurant");
	}
	
	public function livreur ($request) {
		$request->title = "Administration";
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$request->livreurs = $modelCommande->getTotalByLivreur();
		$request->vue = $this->render("livreur");
	}
	
	public function client ($request) {
		$request->title = "Administration";
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$request->clients = $modelCommande->getTotalByClient();
		$request->vue = $this->render("client");
	}
	
	public function ville ($request) {
		$request->title = "Administration";
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$request->villes = $modelCommande->getTotalByVille();
		$request->vue = $this->render("ville");
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
		
		$modelUser = new Model_User(true, $request->dbConnector);
		$request->nouveauClients = $modelUser->getNouveauClientByMonth($dateDebut, $dateFin);
		$request->totalClients = $modelUser->getClientBeforeDate($dateDebut);
		$modelCommande = new Model_Commande_History(true, $request->dbConnector);
		$request->resultats = $modelCommande->getTotal($dateDebut, $dateFin);
		$request->months = $modelCommande->getTotalByMonth($dateDebut, $dateFin);
		$request->vue = $this->render("history");
	}
	
	public function restaurant_history ($request) {
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
		$request->days = $modelCommande->getTotalByDayAndRestaurant($dateDebut, $dateFin);
		$request->restaurants = $modelCommande->getTotalByRestaurant($dateDebut, $dateFin);
		$request->timeRestaurant = $modelCommande->getAvgTimeByRestaurant($dateDebut, $dateFin);
		$request->vue = $this->render("restaurant_history");
	}
	
	public function livreur_history ($request) {
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
		$request->livreurs = $modelCommande->getTotalByLivreur($dateDebut, $dateFin);
		$request->timeLivreur = $modelCommande->getAvgTimeByLivreur($dateDebut, $dateFin);
		$request->vue = $this->render("livreur_history");
	}
	
	public function client_history ($request) {
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
		$request->clients = $modelCommande->getTotalByClient($dateDebut, $dateFin);
		$request->vue = $this->render("client_history");
	}
	
	public function ville_history ($request) {
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
		$request->villes = $modelCommande->getTotalByVille($dateDebut, $dateFin);
		$request->vue = $this->render("ville_history");
	}
	
	public function jour_history ($request) {
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
		$request->vue = $this->render("jour_history");
	}
	
	public function logout ($request) {
		session_destroy();
		$this->redirect();
	}
}