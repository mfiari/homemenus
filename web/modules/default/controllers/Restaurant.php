<?php

include_once ROOT_PATH."models/Panier.php";
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
include_once ROOT_PATH."models/Recherche.php";
include_once ROOT_PATH."models/Certificat.php";
include_once ROOT_PATH."models/CodePromo.php";
include_once ROOT_PATH."models/Commentaire.php";
include_once ROOT_PATH."models/Dispo.php";

class Controller_Restaurant extends Controller_Default_Template {
	
	public function manage ($request) {
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "recherche" :
					$this->recherche($request);
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
				case "autocompleteTag" :
					$this->autocompleteTag($request);
					break;
				case "autocompleteRestaurant" :
					$this->autocompleteRestaurant($request);
					break;
				case "panier" :
					$this->panier($request);
					break;
				default :
					$this->redirect('404');
					break;
			}
		} else {
			$this->redirect('404');
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('restaurant/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('restaurant/'.$vue.'.php');
	}
	
	public function index ($request) {
		if (isset($_GET['id'])) {
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $_GET['id'];
			$restaurant = $modelRestaurant->loadAll();
			
			$modelUser = new Model_User(true, $request->dbConnector);
			$livreurs = $modelUser->getLivreurAvailableForRestaurant($restaurant);
			$restaurant->has_livreur_dispo = count($livreurs) > 0;
			foreach ($livreurs as $livreur) {
				$livreur->getLivreurDispo();
			}
			$request->livreurs = $livreurs;
			
			if (!isset($_SESSION['search_adresse'])) {
				$_SESSION['search_latitude'] = 48.989323;
				$_SESSION['search_longitude'] = 1.714958;
				$_SESSION['search_adresse'] = "Mantes la jolie";
				$_SESSION['search_ville'] = "Mantes la jolie";
				$_SESSION['search_cp'] = "78200";
			}
			
			$adresseUser = $_SESSION['search_latitude'].','.$_SESSION['search_longitude'];
			$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
			$result = getDistance($adresseUser, $adresseResto);
			if ($result['status'] == "OK") {
				$distanceRestoKm = $result['distance'] / 1000;
				$restaurant->distance = $distanceRestoKm;
				$request->prix_livraison = $restaurant->getPrixLivraison();
			}
			
			$panier = new Model_Panier(true, $request->dbConnector);
			if ($request->_auth) {
				$panier->uid = $request->_auth->id;
			} else {
				$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
			}
			$request->panier = $panier->loadPanier();
			
			if (isset($_SESSION['search_adresse'])) {
				$request->adresse = $_SESSION['search_adresse'];
			}
			if (isset($_SESSION['search_ville'])) {
				$request->ville = $_SESSION['search_ville'];
			}
			if (isset($_SESSION['search_cp'])) {
				$request->codePostal = $_SESSION['search_cp'];
			}
			if (isset($_SESSION['search_rue'])) {
				$request->rue = $_SESSION['search_rue'];
			}
			$request->title = 'Restaurant '.utf8_encode($restaurant->nom);
			$request->restaurant = $restaurant;
			$request->search_adresse = $_SESSION['search_adresse'];
			$request->javascripts = array("res/js/menu.js", "https://maps.googleapis.com/maps/api/js?libraries=places");
			$request->vue = $this->render("restaurant");
		} else {
			$this->redirect();
		}		
	}
	
	public function recherche ($request) {
		$city = "";
		if ($request->request_method == "GET") {
			if (!isset($_SESSION['search_serialized'])) {
				if (isset($_GET['ville'])) {
					if (isVilleAvailable($_GET['ville'])) {
						$filter = array(
							"search_adresse" => str_replace('-', ' ', $_GET['ville']),
							"distanceKm" => MAX_KM
						);
					} else {
						$this->redirect('404');
					}
				} else {
					$filter = array(
						"search_adresse" => "Mantes la jolie",
						"distanceKm" => MAX_KM
					);
				}
			} else {
				$filter = unserialize($_SESSION['search_serialized']);
				if (isset($_GET['ville']) && isVilleAvailable($_GET['ville'])) {
					$filter['search_adresse'] = str_replace('-', ' ', $_GET['ville']);
				}
			}
		} else if ($request->request_method == "POST") {
			if (!isset($_POST['adresse']) || trim($_POST['adresse']) == "") {
				if (!isset($_SESSION['search_serialized'])) {
					$filter = array(
						"search_adresse" => "Mantes la jolie",
						"distanceKm" => MAX_KM
					);
				} else {
					$filter = unserialize($_SESSION['search_serialized']);
				}
			} else {
				$filter = array();
				$filter["search_adresse"] = $_POST['adresse'];
				if (isset($_POST["city"]) && $_POST["city"] != "") {
					$filter["ville"] = $_POST["city"];
				}
				if (isset($_POST["groupe"]) && $_POST["groupe"] != "") {
					$filter["groupe"] = $_POST["groupe"];
				}
				if (isset($_POST["distance"]) && $_POST["distance"] != "") {
					$filter["distanceKm"] = $_POST["distance"];
				} else {
					$filter["distanceKm"] = MAX_KM;
				}
			}
		} else {
			$this->redirect();
		}
		$_SESSION['search_serialized'] = serialize($filter);
		if (isset($filter["ville"])) {
			$city = $filter["ville"];
		}
		$distanceKm = $filter["distanceKm"];
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		
		$restaurants = $modelRestaurant->filter($filter);
		
		$request->search_adresse = $filter["search_adresse"];
		$geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
		$localisation = urlencode($filter["search_adresse"]);
		$query = sprintf($geocoder,$localisation);
		$rd = json_decode(file_get_contents($query));
		
		$recherche = new Model_Recherche(true, $request->dbConnector);
		$recherche->recherche = $filter["search_adresse"];
		$recherche->distance = $filter["distanceKm"];
		if (isset($filter["ville"])) {
			$recherche->ville = $filter["ville"];
		}
		if ($request->_auth) {
			$recherche->user = $request->_auth;
		}
		
		if ($rd->{'status'} == "OK") {
			$addressComponents = $rd->{'results'}[0]->{'address_components'};
			$codePostal = "";
			$ville = "";
			$street_number = "";
			$route = "";
			for ($i = 0 ; $i < count($addressComponents) ; $i++) {
				if ($addressComponents[$i]->{'types'}[0] == 'postal_code') {
					$codePostal = $addressComponents[$i]->{'short_name'};
				} else if ($addressComponents[$i]->{'types'}[0] == 'locality') {
					$ville = $addressComponents[$i]->{'long_name'};
				} else if ($addressComponents[$i]->{'types'}[0] == 'street_number') {
					$street_number = $addressComponents[$i]->{'long_name'};
				} else if ($addressComponents[$i]->{'types'}[0] == 'route') {
					$route = $addressComponents[$i]->{'long_name'};
				}
			}
			$_SESSION['search_adresse'] = $request->search_adresse;
			$_SESSION['search_ville'] = $ville;
			$_SESSION['search_cp'] = $codePostal;
			$_SESSION['search_rue'] = $street_number.' '.$route;
			$coord = $rd->{'results'}[0]->{'geometry'}->{'location'};
			$user_latitude = $coord->{'lat'};
			$user_longitude = $coord->{'lng'};
			$_SESSION['search_latitude'] = $user_latitude;
			$_SESSION['search_longitude'] = $user_longitude;
			
			$availableRestaurant = array();
			$adresseUser = $user_latitude.','.$user_longitude;
			foreach ($restaurants as $restaurant) {
				if ($restaurant->latitude != 0 && $restaurant->longitude != 0) {
					$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
					$result = getDistance($adresseUser, $adresseResto);
					if ($result['status'] == "OK") {
						$distanceRestoKm = $result['distance'] / 1000;
						$restaurant->distance = $distanceRestoKm == 0 ? -1 : $distanceRestoKm;
						$availableRestaurant[] = $restaurant;
						$recherche->addRestaurant($restaurant);
					}
				}
			}
			$restaurants = $availableRestaurant;
				
			foreach ($restaurants as $restaurant) {
				$this->getLogo($restaurant);
			}
		} else {
			$request->adressError = true;
		}
		$recherche->save();
		
		$request->ouvert = true;
		$request->distance = $distanceKm;
		$request->ville = $city;
		$request->villes = array_unique(array_object_column($restaurants, 'ville'));
		$request->restaurants = $restaurants;
		$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places");
		$request->vue = $this->render("restaurants");
	}
	
	private function categories ($request) {
		$id_categorie = $_GET["id_categorie"];
		$id_restaurant = $_GET["id_restaurant"];
		$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
		$modelRestaurant->id = $_GET['id_restaurant'];
		$restaurant = $modelRestaurant->loadMinInformation();
		$modelCategorie = new Model_Categorie(true, $request->dbConnector);
		$modelCategorie->id = $_GET['id_categorie'];
		$childrens = $modelCategorie->getChildren();
		$modelCategorie->loadContenu($id_restaurant);
		$restaurant->addCategorie($modelCategorie);
		foreach ($childrens as $children) {
			$children->loadContenu($id_restaurant);
			$restaurant->addCategorie($children);
		}
		$request->restaurant = $restaurant;
		$request->vue = $this->render("categories");
	}
	
	private function carte ($request) {
		if (isset($_GET["id_carte"])) {
			$request->disableLayout = true;
			$modelCarte = new Model_Carte(true, $request->dbConnector);
			$modelCarte->id = $_GET['id_carte'];
			$request->id_restaurant = $_GET['id'];
			$request->carte = $modelCarte->load();
			$request->carte->getLogo($request->id_restaurant);
			
			$modelUser = new Model_User(true, $request->dbConnector);
			$ville = isset($_SESSION['search_ville']) ? $_SESSION['search_ville'] : '';
			$codePostal = isset($_SESSION['search_cp']) ? $_SESSION['search_cp'] : '';
			$restaurant = new Model_Restaurant(true, $request->dbConnector);
			$restaurant->id = $request->id_restaurant;
			$fields = array ("code_postal", "ville");
			$restaurant->get($fields);
			$livreurs = $modelUser->getLivreurAvailableForRestaurant($restaurant);
			$request->has_livreur_dispo = count($livreurs) > 0;
			
			$request->vue = $this->render("carteDetail");
		} else {
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $_GET['id'];
			$request->restaurant = $modelRestaurant->loadCarte();
			$request->vue = $this->render("carte.php");
		}
	}
	
	private function menu ($request) {
		if (isset($_GET["id_menu"])) {
			$request->disableLayout = true;
			$request->id_restaurant = $_GET['id'];
			$modelMenu = new Model_Menu(true, $request->dbConnector);
			$modelMenu->id = $_GET['id_menu'];
			$request->menu = $modelMenu->load();
			
			$modelUser = new Model_User(true, $request->dbConnector);
			$ville = $_SESSION['search_ville'];
			$codePostal = $_SESSION['search_cp'];
			$restaurant = new Model_Restaurant(true, $request->dbConnector);
			$restaurant->id = $request->id_restaurant;
			$fields = array ("code_postal", "ville");
			$restaurant->get($fields);
			$livreurs = $modelUser->getLivreurAvailableForRestaurant($restaurant);
			$request->has_livreur_dispo = count($livreurs) > 0;
			
			$request->vue = $this->render("menu.php");
		} else {
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $_GET['id'];
			$restaurant = $modelRestaurant->loadMinInformation();
			$restaurant->loadMenus();
			$request->restaurant = $restaurant;
			$request->javascripts = array("res/js/menu.js");
			$request->vue = $this->render("menus.php");
		}
	}
	
	private function autocompleteTag ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		if(isset($_GET['term'])) {
			$restaurants = $_GET['restaurant'];
			// Mot tapé par l'utilisateur
			$q = htmlentities($_GET['term']);
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$suggestions = array();
			$list = $modelRestaurant->filterTags ($q, $restaurants);
			foreach ($list as $tag) {
				$suggestions[] = array();
				$indice = count($suggestions) - 1;
				$suggestions[$indice]['id'] = $tag->id;
				$suggestions[$indice]['value'] = utf8_encode($tag->nom);
			}
			echo json_encode($suggestions);
		}
	}
	
	private function autocompleteRestaurant ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		if(isset($_GET['term'])) {
			$restaurants = $_GET['restaurant'];
			// Mot tapé par l'utilisateur
			$q = htmlentities($_GET['term']);
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$suggestions = array();
			$list = $modelRestaurant->filterRestaurant ($q, $restaurants);
			foreach ($list as $restaurant) {
				$suggestions[] = array();
				$indice = count($suggestions) - 1;
				$suggestions[$indice]['id'] = $restaurant->id;
				$suggestions[$indice]['value'] = utf8_encode($restaurant->nom);
			}
			echo json_encode($suggestions);
		}
	}
	
	private function panier ($request) {
		$panier = new Model_Panier(true, $request->dbConnector);
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		$request->panier = $panier->loadPanier();
		if (isset($_GET["type"]) && $_GET["type"] == "ajax") {
			$request->disableLayout = true;
		} else if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$request->disableLayout = false;
			$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
			$modelRestaurant->id = $request->panier->restaurant->id;
			$request->restaurant = $modelRestaurant->loadAll();
			
			$modelUser = new Model_User(true, $request->dbConnector);
			$livreurs = $modelUser->getLivreurAvailableForRestaurant($request->restaurant);
			$request->restaurant->has_livreur_dispo = count($livreurs) > 0;
			foreach ($livreurs as $livreur) {
				$livreur->getLivreurDispo();
			}
			$request->livreurs = $livreurs;
		} else {
			$request->disableLayout = true;
		}
		$request->vue = $this->render("panier");
	}
	
	private function getLogo ($restaurant) {
		$logoDirectory = WEBSITE_PATH."res/img/restaurant/";
		if (file_exists($logoDirectory.$restaurant->id)) {
			if (file_exists($logoDirectory.$restaurant->id.'/logo.png')) {
				$restaurant->logo = $restaurant->id.'/logo.png';
			} else if (file_exists($logoDirectory.$restaurant->id.'/logo.jpg')) {
				$restaurant->logo = $restaurant->id.'/logo.jpg';
			} else if (file_exists($logoDirectory.$restaurant->id.'/logo.gif')) {
				$restaurant->logo = $restaurant->id.'/logo.gif';
			} else {
				$restaurant->logo = 'default/logo.jpg';
			}
		} else {
			$restaurant->logo = 'default/logo.jpg';
		}
	}
}