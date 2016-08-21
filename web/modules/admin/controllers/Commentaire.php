<?php

include_once MODEL_PATH."Template.php";
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
				case "noterRestaurant" :
					$this->noterRestaurant($request);
					break;
				case "plats" :
					$this->plats($request);
					break;
				case "noterCarte" :
					$this->noterCarte($request);
					break;
				default :
					$this->redirect('404');
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Notes";
		$modelCommande = new Model_Commande_History();
		$request->commandes = $modelCommande->getAllCommentaire();
		$request->vue = $this->render("commentaire/index.php");
	}
	
	public function annule ($request) {
		$modelCommande = new Model_Commande_History();
		$modelCommande->id = $_GET['id_commande'];
		$modelCommande->disableCommentaire();
		$this->redirect('index', 'commentaire');
	}
	
	public function enable ($request) {
		$modelCommande = new Model_Commande_History();
		$modelCommande->id = $_GET['id_commande'];
		$modelCommande->enableCommentaire();
		$this->redirect('index', 'commentaire');
	}
	
	public function restaurants ($request) {
		$request->title = "Notes";
		$modelRestaurant = new Model_Restaurant();
		$request->restaurants = $modelRestaurant->getAllCommentaire();
		$request->vue = $this->render("commentaire/restaurants.php");
	}
	
	public function annuleRestaurant ($request) {
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->disableCommentaire();
		$this->redirect('restaurants', 'commentaire');
	}
	
	public function enableRestaurant ($request) {
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id_commentaire'];
		$modelRestaurant->enableCommentaire();
		$this->redirect('restaurants', 'commentaire');
	}
	
	public function noterRestaurant ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant();
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
	
	public function plats ($request) {
		$request->title = "Notes";
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->user = $request->_auth;
		$request->restaurants = $modelRestaurant->getCommentaireCarteByUser();
		$request->javascripts = array("res/js/bootstrap-star-rating.js");
		$request->vue = $this->render("cartes");
	}
	
	public function noterCarte ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant();
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
	
	public function noterMenu ($request) {
		if ($request->request_method == "POST") {
			$modelRestaurant = new Model_Restaurant();
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