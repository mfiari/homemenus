<?php

include_once ROOT_PATH."function.php";

include_once MODEL_PATH.'Template.php';
include_once MODEL_PATH.'Restaurant.php';
include_once MODEL_PATH.'Horaire.php';
include_once MODEL_PATH.'Carte.php';
include_once MODEL_PATH.'Categorie.php';

class Controller_Restaurant extends Controller_Template {
	
	public function handle() {
		$this->init();
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "all" :
					$this->getAll();
					break;
				case "one" :
					$this->getById();
					break;
				case "search" :
					$this->recherche();
					break;
				case "categories" :
					$this->categories();
					break;
				case "categorie" :
					$this->categorie();
					break;
				case "carte" :
					$this->carte();
					break;
				case "menu" :
					$this->menu();
					break;
			}
		} else {
			$this->getAll();
		}
	}
	
	private function getAll () {
		$ext = $this->getExtension();
		$model = new Model_Restaurant();
		$result = $model->getAll();
		if ($result !== false) {
			foreach ($result as $restaurant) {
				$this->getLogo($restaurant);
			}
			require 'vue/restaurants_get.'.$ext.'.php';
		}
	}
	
	public function recherche () {
		global $user_latitude, $user_longitude, $distanceKm;
		$ville = "";
		$ext = $this->getExtension();
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$filter = array();
			if (isset($_POST["city"]) && $_POST["city"] != "") {
				$ville = $_POST["city"];
				$filter["ville"] = $ville;
			}
			if (isset($_POST["groupe"]) && $_POST["groupe"] != "") {
				$filter["groupe"] = $_POST["groupe"];
			}
			if (isset($_POST["distance"]) && $_POST["distance"] != "") {
				$distanceKm = $_POST["distance"];
			} else {
				$distanceKm = 5;
			}
			$modelRestaurant = new Model_Restaurant();
			$restaurants = $modelRestaurant->filter($filter);
			if (isset($_POST['adresse']) && trim($_POST['adresse']) != "") {
				$geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
				$localisation = urlencode($_POST['adresse']);
				$query = sprintf($geocoder,$localisation);
				$rd = json_decode(file_get_contents($query));
				$coord = $rd->{'results'}[0]->{'geometry'}->{'location'};
				$user_latitude = $coord->{'lat'};
				$user_longitude = $coord->{'lng'};
				$restaurants = array_filter($restaurants, "filterRestaurant");
			}
			$groupes = array();
			foreach ($restaurants as $restaurant) {
				$this->getLogo($restaurant);
			}
			$result = $restaurants;
			require 'vue/restaurants_get.'.$ext.'.php';
		}
	}
	
	private function getById () {
		if (isset($_GET["id"])) {
			$id = $_GET["id"];
			$ext = $this->getExtension();
			$model = new Model_Restaurant();
			$model->id = $id;
			$result = $model->load();
			if ($result !== false) {
				require 'vue/restaurant_get.'.$ext.'.php';
			}
		}
	}
	
	private function categories () {
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id'];
		$restaurant = $modelRestaurant->loadCategories();
		require 'vue/restaurant/categories.'.$this->ext.'.php';
	}
	
	private function categorie () {
		$modelCategorie = new Model_Categorie();
		$modelCategorie->id = $_GET['id'];
		$categorie = $modelCategorie->loadContenu();
		require 'vue/categorie/contenus.'.$this->ext.'.php';
	}
	
	private function carteContenu () {
		$modelCarte = new Model_Carte();
		$modelCarte->id = $_GET['id'];
		$carte = $modelCarte->load();
		require 'vue/carte/detail.'.$this->ext.'.php';
	}
	
	private function carte () {
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id'];
		$restaurant = $modelRestaurant->loadCarte();
		require 'vue/restaurant/carte.'.$this->ext.'.php';
	}
	
	private function menu () {
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id'];
		$restaurant = $modelRestaurant->loadMenus();
		require 'vue/restaurant/menu.'.$this->ext.'.php';
	}
	
	private function getLogo ($restaurant) {
		$imgPath = "res/img/restaurant/";
		$logoDirectory = WEBSITE_PATH.$imgPath;
		if (file_exists($logoDirectory.$restaurant->id)) {
			if (file_exists($logoDirectory.$restaurant->id.'/logo.png')) {
				$restaurant->logo = $imgPath.$restaurant->id.'/logo.png';
			} else if (file_exists($logoDirectory.$restaurant->id.'/logo.jpg')) {
				$restaurant->logo = $imgPath.$restaurant->id.'/logo.jpg';
			} else if (file_exists($logoDirectory.$restaurant->id.'/logo.gif')) {
				$restaurant->logo = $imgPath.$restaurant->id.'/logo.gif';
			} else {
				$restaurant->logo = $imgPath.'default/logo.jpg';
			}
		} else {
			$restaurant->logo = $imgPath.'default/logo.jpg';
		}
	}
}
