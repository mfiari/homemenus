<?php

include_once MODEL_PATH."CommandeHistory.php";
include_once MODEL_PATH."Restaurant.php";
include_once MODEL_PATH."Commentaire.php";
include_once MODEL_PATH."Categorie.php";
include_once MODEL_PATH."Contenu.php";

class Controller_Commentaire extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (!$request->_auth) {
			$this->redirect();
		}
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "annule" :
					$this->annule($request);
					break;
				case "enable" :
					$this->enable($request);
					break;
				case "restaurants" :
					$this->restaurants($request);
					break;
				case "annuleRestaurant" :
					$this->annuleRestaurant($request);
					break;
				case "enableRestaurant" :
					$this->enableRestaurant($request);
					break;
				case "plats" :
					$this->plats($request);
					break;
				case "annuleCarte" :
					$this->annuleCarte($request);
					break;
				case "enableCarte" :
					$this->enableCarte($request);
					break;
				case "annuleMenu" :
					$this->annuleMenu($request);
					break;
				case "enableMenu" :
					$this->enableMenu($request);
					break;
				default :
					$this->redirect('404');
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('commentaire/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('commentaire/'.$vue.'.php');
	}
	
	public function index ($request) {
		$request->title = "Notes";
		$modelCommande = new Model_Commande_History(true, $request->dbConnector);
		$request->commandes = $modelCommande->getAllCommentaire();
		$request->vue = $this->render("index");
	}
	
	public function annule ($request) {
		$modelCommande = new Model_Commande_History(true, $request->dbConnector);
		$modelCommande->id = $_GET['id_commande'];
		$modelCommande->disableCommentaire();
		$this->redirect('index', 'commentaire');
	}
	
	public function enable ($request) {
		$modelCommande = new Model_Commande_History(true, $request->dbConnector);
		$modelCommande->id = $_GET['id_commande'];
		$modelCommande->enableCommentaire();
		$this->redirect('index', 'commentaire');
	}
	
	public function restaurants ($request) {
		$request->title = "Notes";
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$request->restaurants = $modelRestaurant->getAllCommentaire();
		$request->vue = $this->render("restaurants");
	}
	
	public function annuleRestaurant ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->disableCommentaire();
		$this->redirect('restaurants', 'commentaire');
	}
	
	public function enableRestaurant ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->enableCommentaire();
		$this->redirect('restaurants', 'commentaire');
	}
	
	public function plats ($request) {
		$request->title = "Commentaire - plats";
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$request->restaurants = $modelRestaurant->getAllCommentairePlats();
		$request->vue = $this->render("cartes");
	}
	
	public function annuleCarte ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->disableCommentaireCarte();
		$this->redirect('plats', 'commentaire');
	}
	
	public function enableCarte ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->enableCommentaireCarte();
		$this->redirect('plats', 'commentaire');
	}
	
	public function annuleMenu ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->disableCommentaireMenu();
		$this->redirect('plats', 'commentaire');
	}
	
	public function enableMenu ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->enableCommentaireMenu();
		$this->redirect('plats', 'commentaire');
	}
}