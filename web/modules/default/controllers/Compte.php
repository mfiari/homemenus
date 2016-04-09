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

class Controller_Compte extends Controller_Default_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "activation" :
					$this->activation($request);
					break;
				case "forgot_password" :
					$this->forgot_password($request);
					break;
				case "reset_password" :
					$this->reset_password($request);
					break;
				case "solde" :
					$this->solde($request);
					break;
				case "calendrier" :
					$this->calendrier($request);
					break;
				case "commande_search" :
					$this->commande_search($request);
					break;
				case "restaurant" :
					$this->restaurant($request);
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
				default :
					$this->redirect('404');
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		if ($request->_auth) {
			$request->title = "Compte";
			$modelUser = new Model_User();
			$modelUser->id = $request->_auth->id;
			$request->user = $modelUser->getById();
			$request->vue = $this->render("compte.php");
		} else {
			$this->redirect('inscription');
		}
	}
	
	public function calendrier ($request) {
		$request->title = "Compte";
		$modelUser = new Model_User();
		$modelUser->id = $request->_auth->id;
		$request->user = $modelUser->getById();
		$request->javascripts = array("res/js/calendar.js");
		$request->vue = $this->render("compte/calendrier.php");
	}
	
	public function activation ($request) {
		$model = new Model_User();
		$model->id = trim($_GET["uid"]);
		$model->inscription_token = trim($_GET["token"]);
		if ($model->confirm()) {
			$model->getById();
			$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription_admin.html');
					
			$messageContent = str_replace("[PRENOM]", $model->prenom, $messageContent);
			$messageContent = str_replace("[NOM]", $model->nom, $messageContent);
			if ($model->ville != '') {
				$messageContent = str_replace("[ADRESSE]", $model->ville.' ('.$model->code_postal.')', $messageContent);
			} else {
				$messageContent = str_replace("[ADRESSE]", "(adresse non renseignée)", $messageContent);
			}
			send_mail ("admin@homemenus.fr", "création de compte", $messageContent);
			
			$_SESSION["uid"] = $model->id;
			$_SESSION["session"] = $model->session;
			$request->vue = $this->render("confirmation_inscription_success.php");
		} else {
			$request->vue = $this->render("confirmation_inscription_fail.php");
		}
	}
	
	public function forgot_password ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
		}
		if (!isset($_POST['login'])) {
			$this->error(400, "bad request");
		}
		$model = new Model_User();
		$model->login = trim($_POST["login"]);
		$user = $model->getByLogin();
		if ($user) {
			if (!$user->is_enable) {
				$this->error(403, "Not authorized");
			}
			$token = generateToken();
			$messageContent =  file_get_contents (ROOT_PATH.'mails/forgot_password.html');
			$messageContent = str_replace("[UID]", $model->id, $messageContent);
			$messageContent = str_replace("[TOKEN]", $token, $messageContent);
			$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
					
			send_mail ($model->email, "Changement de mot de passe", $messageContent);
			
		} else {
			$this->error(404, "Not found");
		}
	}
	
	public function reset_password ($request) {
		if ($request->request_method == "POST") {
			$model = new Model_User();
			$model->id = trim($_POST["uid"]);
			$model->password = trim($_POST["password"]);
			$model->changePassword();
		} else if ($request->request_method == "GET") {
			$request->vue = $this->render("reset_password.php");
		}
	}
	
	public function commande_search ($request) {
		$city = "";
		if ($request->request_method == "GET") {
			if (!isset($_SESSION['search_serialized'])) {
				$this->redirect();
			}
			$filter = unserialize($_SESSION['search_serialized']);
		} else if ($request->request_method == "POST") {
			$filter = array();
			$filter["search_ardresse"] = $_POST['adresse'];
			if (isset($_POST["city"]) && $_POST["city"] != "") {
				$filter["ville"] = $_POST["city"];
			}
			if (isset($_POST["groupe"]) && $_POST["groupe"] != "") {
				$filter["groupe"] = $_POST["groupe"];
			}
			if (isset($_POST["distance"]) && $_POST["distance"] != "") {
				$filter["distanceKm"] = $_POST["distance"];
			} else {
				$filter["distanceKm"] = 5;
			}
			$modelRestaurant = new Model_Restaurant();
		
			$tags = $modelRestaurant->getTags();
			
			$tagsFilter = array();
			foreach ($tags as $tag) {
				if (isset($_POST["tag_".$tag->id])) {
					$tagsFilter[] = $tag->id;
				}
			}
			$filter["tags"] = $tags;
			$filter["tagsFilter"] = $tagsFilter;
			$_SESSION['search_serialized'] = serialize($filter);
		} else {
			$this->redirect();
		}
		
		if (isset($filter["ville"])) {
			$city = $filter["ville"];
		}
		$distanceKm = $filter["distanceKm"];
		
		$modelRestaurant = new Model_Restaurant();
		
		$request->tags = $filter["tags"];
		$request->tagsFilter = $filter["tagsFilter"];
		
		$restaurants = $modelRestaurant->filter($filter);
		
		$request->search_ardresse = $filter["search_ardresse"];
		$geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
		$localisation = urlencode($filter["search_ardresse"]);
		$query = sprintf($geocoder,$localisation);
		$rd = json_decode(file_get_contents($query));
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
			$_SESSION['search_adresse'] = $request->search_ardresse;
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
			$modelUser = new Model_User();
			foreach ($restaurants as $restaurant) {
				if ($restaurant->latitude != 0 && $restaurant->longitude != 0) {
					$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
					$result = getDistance($adresseUser, $adresseResto);
					if ($result['status'] == "OK") {
						$distanceRestoKm = $result['distance'] / 1000;
						if ($distanceRestoKm < $distanceKm) {
							$restaurant->distance = $distanceRestoKm;
							$availableRestaurant[] = $restaurant;
						}
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
		$request->ouvert = true;
		$request->distance = $distanceKm;
		$request->ville = $city;
		$request->villes = array_unique(array_object_column($restaurants, 'ville'));
		$request->restaurants = $restaurants;
		$request->vue = $this->render("compte/restaurants.php");
	}
	
	public function restaurant ($request) {
		if (isset($_GET['id'])) {
			$request->title = "Restaurant";
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $_GET['id'];
			$request->restaurant = $modelRestaurant->load();
			$modelCategorie = new Model_Categorie();
			$request->restaurant->categories = $modelCategorie->getParentContenu($request->restaurant->id);
			$request->search_adresse = $_SESSION['search_adresse'];
			$request->vue = $this->render("compte/restaurant.php");
		}	
	}
	
	private function categories ($request) {
		$id_categorie = $_GET["id_categorie"];
		$id_restaurant = $_GET["id_restaurant"];
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id_restaurant'];
		$restaurant = $modelRestaurant->loadMinInformation();
		$modelCategorie = new Model_Categorie();
		$modelCategorie->id = $_GET['id_categorie'];
		$childrens = $modelCategorie->getChildren();
		$modelCategorie->loadContenu($id_restaurant);
		$restaurant->addCategorie($modelCategorie);
		foreach ($childrens as $children) {
			$children->loadContenu($id_restaurant);
			$restaurant->addCategorie($children);
		}
		$request->restaurant = $restaurant;
		$request->vue = $this->render("compte/categories.php");
	}
	
	private function carte ($request) {
		if (isset($_GET["id_carte"])) {
			$request->disableLayout = true;
			$modelCarte = new Model_Carte();
			$modelCarte->id = $_GET['id_carte'];
			$request->id_restaurant = $_GET['id'];
			$request->carte = $modelCarte->load();
			$request->carte->getLogo($request->id_restaurant);
			
			$request->vue = $this->render("compte/carteDetail.php");
		}
	}
	
	private function menu ($request) {
		if (isset($_GET["id_menu"])) {
			$request->disableLayout = true;
			$request->id_restaurant = $_GET['id'];
			$modelMenu = new Model_Menu();
			$modelMenu->id = $_GET['id_menu'];
			$request->menu = $modelMenu->load();
			
			$request->vue = $this->render("compte/menu.php");
		} else {
			$modelRestaurant = new Model_Restaurant();
			$modelRestaurant->id = $_GET['id'];
			$restaurant = $modelRestaurant->loadMinInformation();
			$restaurant->loadMenus();
			$request->restaurant = $restaurant;
			$request->javascripts = array("res/js/menu.js");
			$request->vue = $this->render("compte/menus.php");
		}
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