<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Panier.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Option.php";
include_once ROOT_PATH."models/OptionValue.php";
include_once ROOT_PATH."models/Accompagnement.php";
include_once ROOT_PATH."models/CodePromo.php";

class Controller_Panier extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "view" :
					$this->view($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Administration - panier";
		$modelPanier = new Model_Panier();
		$request->paniers = $modelPanier->getAll();
		$request->vue = $this->render("panier/index.php");
	}
	
	public function view ($request) {
		$request->title = "Administration - panier";
		$panier = new Model_Panier();
		$panier->id = $_GET['id_panier'];
		$request->panier = $panier->loadById();
		$request->vue = $this->render("panier/view.php");
	}
}