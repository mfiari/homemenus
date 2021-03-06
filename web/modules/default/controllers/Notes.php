<?php

include_once MODEL_PATH."CommandeHistory.php";
include_once MODEL_PATH."Restaurant.php";
include_once MODEL_PATH."Commentaire.php";
include_once MODEL_PATH."Categorie.php";
include_once MODEL_PATH."Contenu.php";
include_once MODEL_PATH."User.php";

class Controller_Notes extends Controller_Default_Template {
	
	public function manage ($request) {
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "noter" :
					$this->noter($request);
					break;
				case "restaurants" :
					$this->restaurants($request);
					break;
				case "noterRestaurant" :
					$this->noterRestaurant($request);
					break;
				case "viewRestaurant" :
					$this->viewRestaurant($request);
					break;
				case "plats" :
					$this->plats($request);
					break;
				case "noterCarte" :
					$this->noterCarte($request);
					break;
				case "viewCarte" :
					$this->viewCarte($request);
					break;
				case "noterMenu" :
					$this->noterMenu($request);
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
			$mobileVue = parent::render('notes/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('notes/'.$vue.'.php');
	}
	
	public function index ($request) {
		$request->title = "Notes";
		$modelCommande = new Model_Commande_History(true, $request->dbConnector);
		$modelCommande->uid = $request->_auth->id;
		$request->commandes = $modelCommande->getByUser();
		$request->javascripts = array("res/js/bootstrap-star-rating.js");
		$request->vue = $this->render("index");
	}
	
	public function noter ($request) {
		if ($request->request_method == "POST") {
			$modelCommande = new Model_Commande_History(true, $request->dbConnector);
			$modelCommande->id = $_POST['id_commande'];
			$modelCommande->note = $_POST['note'];
			$modelCommande->commentaire = $_POST['commentaire'];
			$modelCommande->commentaire_anonyme = $_POST['anonyme'] == 'true' ? true : false;
			$modelCommande->noter();
		}
	}
	
	public function restaurants ($request) {
		$request->title = "Notes";
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->user = $request->_auth;
		$request->restaurants = $modelRestaurant->getCommentaireByUser();
		$request->javascripts = array("res/js/bootstrap-star-rating.js");
		$request->vue = $this->render("restaurants");
	}
	
	public function noterRestaurant ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $_POST['id_restaurant'];
			$modelRestaurant->user = $request->_auth;
			$modelCommantaire= new Model_Commentaire();
			$modelCommantaire->note = $_POST['note'];
			$modelCommantaire->commentaire = $_POST['commentaire'];
			$modelCommantaire->anonyme = $_POST['anonyme'] == 'true' ? true : false;
			$modelRestaurant->commentaire = $modelCommantaire;
			$modelRestaurant->noter();
		}
	}
	
	public function viewRestaurant ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_restaurant'];
		$request->commentaires = $modelRestaurant->getCommentaireRestaurant();
		$request->disableLayout = true;
		$request->vue = $this->render("commentaires");
	}
	
	public function plats ($request) {
		$request->title = "Notes";
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->user = $request->_auth;
		$request->restaurants = $modelRestaurant->getCommentaireCarteByUser();
		$request->javascripts = array("res/js/bootstrap-star-rating.js");
		$request->vue = $this->render("cartes");
	}
	
	public function noterCarte ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $_POST['id_carte'];
			$modelRestaurant->user = $request->_auth;
			$modelCommantaire= new Model_Commentaire();
			$modelCommantaire->note = $_POST['note'];
			$modelCommantaire->commentaire = $_POST['commentaire'];
			$modelCommantaire->anonyme = $_POST['anonyme'] == 'true' ? true : false;
			$modelRestaurant->commentaire = $modelCommantaire;
			$modelRestaurant->noterCarte();
		}
	}
	
	public function viewCarte ($request) {
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_carte'];
		$request->commentaires = $modelRestaurant->getCommentaireCarte();
		$request->disableLayout = true;
		$request->vue = $this->render("commentaires");
	}
	
	public function noterMenu ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $_POST['id_menu'];
			$modelRestaurant->user = $request->_auth;
			$modelCommantaire= new Model_Commentaire();
			$modelCommantaire->note = $_POST['note'];
			$modelCommantaire->commentaire = $_POST['commentaire'];
			$modelCommantaire->anonyme = $_POST['anonyme'] == 'true' ? true : false;
			$modelRestaurant->commentaire = $modelCommantaire;
			$modelRestaurant->noterMenu();
		}
	}
}