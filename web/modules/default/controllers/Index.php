<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Certificat.php";
include_once ROOT_PATH."models/Commentaire.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/News.php";

class Controller_Index extends Controller_Default_Template {
	
	public function manage ($request) {
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->home($request);
					break;
				case "qui_sommes_nous" :
					$this->qui_sommes_nous($request);
					break;
				case "premium" :
					$this->premium($request);
					break;
				case "faq" :
					$this->faq($request);
					break;
				case "particuliers" :
					$this->particuliers($request);
					break;
				case "entreprises" :
					$this->entreprises($request);
					break;
				case "evenement" :
					$this->evenement($request);
					break;
				case "restaurants_partenaire" :
					$this->restaurants_partenaire($request);
					break;
				case "restaurant_partenaire" :
					$this->restaurant_partenaire($request);
					break;
				case "plats_favoris" :
					$this->plats_favoris($request);
					break;
				case "inscription" :
					$this->signin($request);
					break;
				case "inscription_sucess" :
					$this->signin_success($request);
					break;
				case "login" :
					$this->login($request);
					break;
				case "logout" :
					$this->logout($request);
					break;
				case "mention_legal" :
					$this->mention_legal($request);
					break;
				case "cgu" :
					$this->cgu($request);
					break;
				case "cgv" :
					$this->cgv($request);
					break;
				case "404" :
					$this->error_404($request);
					break;
				default :
					$this->error_404($request);
					break;
			}
		} else {
			$this->home($request);
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('index/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('index/'.$vue.'.php');
	}
	
	public function home ($request) {
		$request->home = true;
		$request->title = "HoMe Menus - vos envies sont servies";
		$modelNews = new Model_News();
		$request->news = $modelNews->getAllActiveNews();
		$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places", "res/js/home.js");
		$request->vue = $this->render("home");
	}
	
	public function qui_sommes_nous ($request) {
		$request->title = "HoMe Menus - Qui sommes-nous";
		$request->vue = $this->render("qui_somme_nous");
	}
	
	public function premium ($request) {
		$request->title = "premium";
		$request->vue = $this->render("premium");
	}
	
	public function faq ($request) {
		$request->title = "HoMe Menus - FAQ";
		$request->vue = $this->render("faq");
	}
	
	public function particuliers ($request) {
		$request->title = "HoMe Menus - Particuliers";
		$request->vue = $this->render("particuliers");
	}
	
	public function entreprises ($request) {
		$request->title = "HoMe Menus - Entreprises";
		$request->vue = $this->render("entreprises");
	}
	
	public function evenement ($request) {
		$request->title = "HoMe Menus - Evénement";
		$request->vue = $this->render("evenement");
	}
	
	public function restaurants_partenaire ($request) {
		$request->title = "HoMe Menus - Restaurants partenaire";
		$modelRestaurant = new Model_Restaurant();
		$request->restaurants = $modelRestaurant->getAllRestaurantEnable();
		$request->vue = $this->render("restaurants_partenaire");
	}
	
	public function restaurant_partenaire ($request) {
		$modelRestaurant = new Model_Restaurant();
		$modelRestaurant->id = $_GET['id'];
		$request->restaurant = $modelRestaurant->loadAll();
		$request->title = "HoMe Menus - Restaurant ".utf8_encode($request->restaurant->nom);
		$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyA2OFGXi3t5i1_mVyyHBw8OBp20ZsY9Lh0");
		$request->vue = $this->render("restaurant_partenaire");
	}
	
	public function plats_favoris ($request) {
		$request->title = "HoMe Menus - Plats favoris";
		$modelCarte = new Model_Carte();
		$request->cartes = $modelCarte->getBestProducts();
		$request->vue = $this->render("plats_favoris");
	}
	
	public function signin ($request) {
		if ($request->request_method == "POST") {
			$errorMessage = array();
			if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
				$errorMessage["EMPTY_NOM"] = "Le nom ne peut être vide";
			} else {
				$request->fieldNom = $_POST["nom"];
			}
			if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
				$errorMessage["EMPTY_PRENOM"] = "Le prénom ne peut être vide";
			} else {
				$request->fieldPrenom = $_POST["prenom"];
			}
			if (!isset($_POST["login"]) || trim($_POST["login"]) == "") {
				$errorMessage["EMPTY_LOGIN"] = "Le login ne peut être vide";
			} else {
				$request->fieldEmail = $_POST["login"];
			}
			if (!isset($_POST["password"]) || trim($_POST["password"]) == "") {
				$errorMessage["EMPTY_PASSWORD"] = "Le mot de passe ne peut être vide";
			}
			$rue = null;
			$ville = null;
			$code_postal = null;
			$telephone = null;
			if (isset($_POST["adresse"]) && trim($_POST["adresse"]) != '') {
				$rue = trim($_POST["rue"]);
				$ville = trim($_POST["ville"]);
				$code_postal = trim($_POST["code_postal"]);
				$request->fieldAdresse = $_POST["adresse"];
				$request->fieldRue = $rue;
				$request->fieldVille = $ville;
				$request->fieldCP = $code_postal;
			}
			if (isset($_POST["telephone"]) && trim($_POST["telephone"]) != '') {
				$telephone = trim($_POST["telephone"]);
				$request->fieldTel = $_POST["telephone"];
			}
			if (count($errorMessage) == 0) {
				$model = new Model_User();
				$model->nom = trim($_POST["nom"]);
				$model->prenom = trim($_POST["prenom"]);
				$model->login = trim($_POST["login"]);
				$model->email = trim($_POST["login"]);
				$model->password = trim($_POST["password"]);
				$model->status = USER_CLIENT;
				$model->rue = $rue;
				$model->ville = $ville;
				$model->code_postal = $code_postal;
				$model->inscription_token = generateToken();
				$model->telephone = $telephone;
				if ($model->isLoginAvailable()) {
					$model->beginTransaction();
					if ($model->save()) {
						$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription.html');
						$messageContent = str_replace("[NOM]", $model->nom, $messageContent);
						$messageContent = str_replace("[PRENOM]", $model->prenom, $messageContent);
						$messageContent = str_replace("[LOGIN]", $model->login, $messageContent);
						$messageContent = str_replace("[UID]", $model->id, $messageContent);
						$messageContent = str_replace("[TOKEN]", $model->inscription_token, $messageContent);
						$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
						
						send_mail ($model->email, "Création de votre compte", $messageContent);
						$this->redirect("inscription_sucess");
					} else {
						$request->errorMessage = array("CREATE_ERROR" => "Une erreur s'est produite, veuillez réessayé ultérieurement.");
					}
					$model->endTransaction();
				} else {
					$request->errorMessage = array("USER_EXISTS" => "l'email ".$model->email." existe déjà");
				}
			} else {
				$request->errorMessage = $errorMessage;
			}
		}
		$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places", "res/js/jquery.validate.min.js", "res/js/compte.js");
		$request->title = "HoMe Menus - Inscription";
		$request->vue = $this->render("signin");
	}
	
	public function signin_success ($request) {
		$request->_noindex = true;
		$request->title = "Inscription réussie";
		$request->vue = $this->render("signin_success");
	}
	
	public function login ($request) {
		if ($request->request_method == "POST") {
			$request->disableLayout = true;
			$request->noRender = true;
			if (!isset($_POST['login']) || !isset($_POST['password'])) {
				$this->error(400, "bad request");
			}
			$login = trim($_POST['login']);
			$password = trim($_POST['password']);
			if ($login == '') {
				$this->error(400, "bad request");
			}
			if ($password == '') {
				$this->error(400, "bad request");
			}
			$user = new Model_User();
			if (!$user->login($login, $password)) {
				$this->error(404, "Not found");
			}
			if (!$user->is_enable) {
				$this->error(403, "Not authorized");
			}
			$_SESSION["uid"] = $user->id;
			$_SESSION["session"] = $user->session;
		} else {
			$request->title = "Connexion";
			$request->vue = $this->render("connexion");
		}
	}
	
	public function mention_legal ($request) {
		$request->title = "Mentions légales";
		$request->vue = $this->render("mention_legal");
	}
	
	public function cgu ($request) {
		$request->title = "Conditions générales d'utilisation";
		$request->vue = $this->render("cgu");
	}
	
	public function cgv ($request) {
		$request->title = "Conditions générales de vente";
		$request->vue = $this->render("cgv");
	}
	
	public function recaptcha ($request) {
		$request->disableLayout = true;
		$request->noRender = true;
		$request->vue = $this->render("recaptcha.php");
	}
	
	public function error_404 ($request) {
		$this->request = $request;
		$request->title = "Page non trouvée";
		$request->vue = $this->render("404");
	}
	
	public function logout ($request) {
		$user = new Model_User();
		$user->id = $_SESSION["uid"];
		if ($user->logout()) {
			session_destroy();
		}
		$this->redirect();
	}
	
	
}