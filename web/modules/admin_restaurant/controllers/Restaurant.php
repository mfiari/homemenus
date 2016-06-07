<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Tag.php";
include_once ROOT_PATH."models/Option.php";
include_once ROOT_PATH."models/OptionValue.php";
include_once ROOT_PATH."models/Accompagnement.php";

class Controller_Restaurant extends Controller_Admin_Restaurant_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "categories" :
					$this->categories($request);
					break;
				case "carte" :
					$this->carte($request);
					break;
				case "menu" :
					$this->menu($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$request->title = "Restaurant";
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $request->_restaurant->id;
		$request->restaurant = $modelRestaurant->loadMinInformation();
		$modelCategorie = new Model_Categorie();
		$request->restaurant->categories = $modelCategorie->getParentContenu($request->restaurant->id);
		$request->vue = $this->render("restaurant.php");
	}
	
	private function categories ($request) {
		$id_categorie = $_GET["id_categorie"];
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $request->_restaurant->id;
		$restaurant = $modelRestaurant->loadMinInformation();
		$modelCategorie = new Model_Categorie();
		$modelCategorie->id = $_GET['id_categorie'];
		$childrens = $modelCategorie->getChildren();
		$modelCategorie->loadContenu($modelRestaurant->id);
		$restaurant->addCategorie($modelCategorie);
		foreach ($childrens as $children) {
			$children->loadContenu($modelRestaurant->id);
			$restaurant->addCategorie($children);
		}
		$request->categorie = $modelCategorie;
		$request->restaurant = $restaurant;
		$request->vue = $this->render("categories.php");
	}
	
	private function carte ($request) {
		$modelCarte = new Model_Carte();
		$request->id_categorie = $_GET["id_categorie"];
		$modelCarte->id = $_GET['id_carte'];
		$request->carte = $modelCarte->load();
		$request->carte->getLogo($request->_restaurant->id);
		$request->vue = $this->render("carteDetail.php");
	}
	
	private function menu ($request) {
		if (isset($_GET["id_menu"])) {
			$modelMenu = new Model_Menu();
			$modelMenu->id = $_GET['id_menu'];
			$request->menu = $modelMenu->load();
			$request->vue = $this->render("menu.php");
		} else {
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $request->_restaurant->id;
			$restaurant = $modelRestaurant->loadMinInformation();
			$restaurant->loadMenus();
			$request->restaurant = $restaurant;
			$request->vue = $this->render("menus.php");
		}
	}
}