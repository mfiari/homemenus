<?php

include_once ROOT_PATH."models/CodePromo.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/CommandeHistory.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/GCMPushMessage.php";

class Controller_CodePromo extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "edit" :
					$this->edit($request);
					break;
				case "view" :
					$this->view($request);
					break;
				case "addUser" :
					$this->addUser($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('code_promo/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('code_promo/'.$vue.'.php');
	}
	
	public function index ($request) {
		$modelCodePromo = new Model_CodePromo(true, $request->dbConnector);
		$request->promos = $modelCodePromo->getAll();
		$request->title = "Administration - code promo";
		$request->vue = $this->render("index");
	}
	
	public function edit ($request) {
		if ($request->request_method == "POST") {
			$id_code_promo = $_POST['id_code_promo'];
			$code = $_POST['code'];
			$description = $_POST['description'];
			$date_debut = datepickerToDatetime($_POST['date_debut']);
			$date_fin = datepickerToDatetime($_POST['date_fin']).' 23:59:59';
			$publique = (isset($_POST['publique']) && $_POST['publique'] == 'on');
			$sur_restaurant = (isset($_POST['sur_restaurant']) && $_POST['sur_restaurant'] == 'on');
			$type_reduction = $_POST['type_reduction'];
			$sur_prix_livraison = (isset($_POST['sur_prix_livraison']) && $_POST['sur_prix_livraison'] == 'on');
			$valeur_prix_livraison = $_POST['valeur_prix_livraison'];
			$sur_prix_total = (isset($_POST['sur_prix_total']) && $_POST['sur_prix_total'] == 'on');
			$valeur_prix_total = $_POST['valeur_prix_total'];
			$pourcentage_prix_total = $_POST['pourcentage_prix_total'];
			
			$modelCodePromo = new Model_CodePromo(true, $request->dbConnector);
			$modelCodePromo->id = $id_code_promo;
			$modelCodePromo->code = $code;
			$modelCodePromo->description = $description;
			$modelCodePromo->date_debut = $date_debut;
			$modelCodePromo->date_fin = $date_fin;
			$modelCodePromo->publique = $publique;
			$modelCodePromo->sur_restaurant = $sur_restaurant;
			$modelCodePromo->type_reduc = $type_reduction;
			$modelCodePromo->sur_prix_livraison = $sur_prix_livraison;
			$modelCodePromo->valeur_prix_livraison = $valeur_prix_livraison;
			$modelCodePromo->sur_prix_total = $sur_prix_total;
			$modelCodePromo->valeur_prix_total = $valeur_prix_total;
			$modelCodePromo->pourcentage_prix_total = $pourcentage_prix_total;
			if (!$modelCodePromo->save()) {
				
			}
			$this->redirect('', 'codePromo');
		} else {
			$request->title = "Administration - code promo";
			if (isset($_GET['id_code_promo'])) {
				$modelCodePromo = new Model_CodePromo(true, $request->dbConnector);
				$modelCodePromo->id = $_GET['id_code_promo'];
				$request->codePromo = $modelCodePromo->load();
			}
			$request->vue = $this->render("edit");
		}
	}
	
	public function view ($request) {
		if (isset($_GET["id"])) {
			$modelCodePromo = new Model_CodePromo(true, $request->dbConnector);
			$modelCodePromo->id = $_GET['id'];
			$request->codePromo = $modelCodePromo->load();
			$modelUser = new Model_User(true, $request->dbConnector);
			$request->clients = $modelUser->getAllClients();
			$request->vue = $this->render("view");
		}
	}
	
	public function addUser ($request) {
		if ($request->request_method == "POST") {
			$id_code_promo = $_POST['id_code_promo'];
			$id_user = $_POST['id_user'];
			
			$modelCodePromo = new Model_CodePromo(true, $request->dbConnector);
			$modelCodePromo->id = $id_code_promo;
			if ($modelCodePromo->addClient($id_user)) {
				$modelCodePromo->getById();
				$modelUser = new Model_User(true, $request->dbConnector);
				$modelUser->id = $id_user;
				$modelUser->getById();
				
				$messageContent =  file_get_contents (ROOT_PATH.'mails/code_promo.html');
			
				$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
				$messageContent = str_replace("[DATE_DEBUT]", formatTimestampToDateHeure($modelCodePromo->date_debut), $messageContent);
				$messageContent = str_replace("[DATE_FIN]", formatTimestampToDateHeure($modelCodePromo->date_fin), $messageContent);
				$messageContent = str_replace("[DESCRIPTION]", $modelCodePromo->description, $messageContent);
				
				$restaurants = "";
				if ($modelCodePromo->surRestaurant()) {
					
				} else {
					$restaurants = "Tous les restaurants";
				}
				$messageContent = str_replace("[RESTAURANTS]", $restaurants, $messageContent);
				$messageContent = str_replace("[CODE_PROMO]", $modelCodePromo->code, $messageContent);
				
				if (send_mail ($modelUser->email, "Code promotionnel", $messageContent)) {
					$request->mailSuccess = true;
				} else {
					$request->mailSuccess = false;
				}
			}
			$this->redirect('view', 'codePromo', '', array("id" => $id_code_promo));
		}
		$this->redirect('', 'codePromo');
	}
}