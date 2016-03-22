<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Carte.php";

class Controller_Commande extends Controller_Admin_Restaurant_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
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
		$debut = null;
		$fin = null;
		if (isset($_POST['date_debut']) && $_POST['date_debut'] != '') {
			$debut = trim($_POST['date_debut']);
			$request->date_debut = $debut;
			$debut = datepickerToDatetime($debut);
		}
		if ($debut != null && isset($_POST['date_fin']) && $_POST['date_fin'] != '') {
			$fin = trim($_POST['date_fin']);
			$request->date_fin = $fin;
			$fin = datepickerToDatetime($fin);
		}
		$commande = new Model_Commande();
		$commande->uid = $request->_auth->id;
		$request->commandes = $commande->getCommandeRestaurantByDate($debut, $fin);
		$request->vue = $this->render("commandes.php");
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