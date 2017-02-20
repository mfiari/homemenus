<?php

include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Perimetre.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/CommandeHistory.php";
include_once ROOT_PATH."models/Dispo.php";

class Controller_User extends Controller_Admin_Template {
	
	public function manage ($request) {
		$this->request = $request;
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "livreurs" :
					$this->livreurs($request);
					break;
				case "livreur" :
					$this->livreur($request);
					break;
				case "livreurDispo" :
					$this->livreurDispo($request);
					break;
				case "edit" :
					$this->edit($request);
					break;
				case "clients" :
					$this->clients($request);
					break;
				case "addClient" :
					$this->addClient($request);
					break;
				case "client" :
					$this->client($request);
					break;
				case "enable" :
					$this->enable($request);
					break;
				case "disable" :
					$this->disable($request);
					break;
				case "deleted" :
					$this->deleted($request);
					break;
				case "add_dispo" :
					$this->add_dispo($request);
					break;
				case "deleteDispo" :
					$this->deleteDispo($request);
					break;
			}
		}
	}
	
	protected function render ($vue) {
		if ($this->request->mobileDetect && $this->request->mobileDetect->isMobile() && !$this->request->mobileDetect->isTablet()) {
			$mobileVue = parent::render('user/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('user/'.$vue.'.php');
	}
	
	public function enable ($request) {
		$model = new Model_User(true, $request->dbConnector);
		$model->id = trim($_GET["id_user"]);
		$model->enable();
		$type = "";
		if (isset($_GET["type"])) {
			$type = $_GET["type"];
		}
		if ($type == "client") {
			$this->redirect('clients', 'user');
		} else {
			$this->redirect('livreurs', 'user');
		}
	}
	
	public function disable ($request) {
		$model = new Model_User(true, $request->dbConnector);
		$model->id = trim($_GET["id_user"]);
		$model->disable();
		$type = "";
		if (isset($_GET["type"])) {
			$type = $_GET["type"];
		}
		if ($type == "client") {
			$this->redirect('clients', 'user');
		} else {
			$this->redirect('livreurs', 'user');
		}
	}
	
	public function deleted ($request) {
		$model = new Model_User(true, $request->dbConnector);
		$model->id = trim($_GET["id_user"]);
		$model->deleted();
		$type = "";
		if (isset($_GET["type"])) {
			$type = $_GET["type"];
		}
		if ($type == "client") {
			$this->redirect('clients', 'user');
		} else {
			$this->redirect('livreurs', 'user');
		}
	}
	
	public function livreurs ($request) {
		$modelUser = new Model_User(true, $request->dbConnector);
		$request->livreurs = $modelUser->getAllLivreurs();
		$request->title = "Administration - livreurs";
		$request->vue = $this->render("livreurs");
	}
	
	public function livreur ($request) {
		$modelUser = new Model_User(true, $request->dbConnector);
		$modelUser->id = $_GET['id_user'];
		$request->livreur = $modelUser->getLivreur();
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$modelCommande->uid = $_GET['id_user'];
		$request->commandes = $modelCommande->loadCommandeLivreur();
		$modelCommandeHistory = new Model_Commande_History(true, $request->dbConnector);
		$modelCommandeHistory->uid = $_GET['id_user'];
		$request->commandesHistory = $modelCommandeHistory->loadCommandeLivreur('0,20', 'date_commande DESC');
		$request->title = "Administration - livreur";
		$request->javascripts = array("https://maps.googleapis.com/maps/api/js?libraries=places");
		$request->vue = $this->render("livreur");
	}
	
	public function livreurDispo ($request) {
		$modelUser = new Model_User(true, $request->dbConnector);
		$request->livreurs = $modelUser->getAllLivreurs();
		$request->title = "Administration - livreurs";
		$request->vue = $this->render("livreurs");
	}
	
	public function edit ($request) {
		if ($request->request_method == "POST") {
			$nom = $_POST['nom'];
			$prenom = $_POST['prenom'];
			$login = $_POST['login'];
			$email = $_POST['email'];
			$telephone = $_POST['telephone'];
			
			$modelUser = new Model_User(true, $request->dbConnector);
			$modelUser->nom = $nom;
			$modelUser->prenom = $prenom;
			$modelUser->login = $login;
			$modelUser->email = $email;
			$modelUser->password = generatePassword();
			$modelUser->inscription_token = generateToken();
			$modelUser->telephone = $telephone;
			$modelUser->status = "LIVREUR";
			
			$modelUser->save();
			
			$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription_livreur.html');
			
			$messageContent = str_replace("[NOM]", $nom, $messageContent);
			$messageContent = str_replace("[PRENOM]", $prenom, $messageContent);
			$messageContent = str_replace("[LOGIN]", $login, $messageContent);
			$messageContent = str_replace("[PASSWORD]", $modelUser->password, $messageContent);
			$messageContent = str_replace("[UID]", $modelUser->id, $messageContent);
			$messageContent = str_replace("[TOKEN]", $modelUser->inscription_token, $messageContent);
			$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
			
			send_mail ($modelUser->email, "Création de votre compte livreur", $messageContent);
			$this->redirect('livreurs', 'user');
		} else {
			$request->title = "Administration - livreur";
			if (isset($_GET['id_restaurant'])) {
				$modelRestaurant = new Model_Restaurant(true, $request->dbConnector);
				$modelRestaurant->id = $_GET['id_restaurant'];
				$request->restaurant = $modelRestaurant->getOne();
			}
			$request->vue = $this->render("edit");
		}
	}
	
	public function clients ($request) {
		$modelUser = new Model_User(true, $request->dbConnector);
		$request->clients = $modelUser->getAllClients();
		$request->title = "Administration - clients";
		$request->vue = $this->render("clients");
	}
	
	public function addClient ($request) {
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
				$model = new Model_User(true, $request->dbConnector);
				$model->nom = trim($_POST["nom"]);
				$model->prenom = trim($_POST["prenom"]);
				$model->login = trim($_POST["login"]);
				$model->email = trim($_POST["login"]);
				$model->password = generatePassword();
				$model->status = USER_CLIENT;
				$model->rue = $rue;
				$model->ville = $ville;
				$model->code_postal = $code_postal;
				$model->inscription_token = generateToken();
				$model->telephone = $telephone;
				if ($model->isLoginAvailable()) {
					if ($model->save()) {
						$messageContent =  file_get_contents (ROOT_PATH.'mails/creation_compte.html');
						$messageContent = str_replace("[NOM]", $model->nom, $messageContent);
						$messageContent = str_replace("[PRENOM]", $model->prenom, $messageContent);
						$messageContent = str_replace("[LOGIN]", $model->login, $messageContent);
						$messageContent = str_replace("[PASSWORD]", $model->password, $messageContent);
						
						send_mail ($model->email, "Création de votre compte", $messageContent);
						$this->redirect('clients', 'user');
					} else {
						$request->errorMessage = array("CREATE_ERROR" => "Une erreur s'est produite, veuillez réessayé ultérieurement.");
					}
				} else {
					$request->errorMessage = array("USER_EXISTS" => "l'email ".$model->email." existe déjà");
				}
			} else {
				$request->errorMessage = $errorMessage;
			}
		}
		$request->title = "Administration - client";
		$request->vue = $this->render("addClient");
	}
	
	public function client ($request) {
		$modelUser = new Model_User(true, $request->dbConnector);
		$modelUser->id = $_GET['id_user'];
		$request->client = $modelUser->getClient();
		$modelCommande = new Model_Commande(true, $request->dbConnector);
		$modelCommande->uid = $modelUser->id;
		$request->commandes = $modelCommande->loadCommandeClient();
		$modelCommandeHistory = new Model_Commande_History();
		$modelCommandeHistory->uid = $modelUser->id;
		$request->commandesHistory = $modelCommandeHistory->loadCommandeClient();
		$request->title = "Administration - client";
		$request->vue = $this->render("client");
	}
	
	public function add_dispo ($request) {
		if ($request->request_method == "POST") {
			
			$dispo = new Model_Dispo(true, $request->dbConnector);
			$dispo->id_livreur = $_POST['id_livreur'];
			$dispo->rue = $_POST['rue'];
			$dispo->ville = $_POST['ville'];
			$dispo->code_postal = $_POST['code_postal'];
			$dispo->latitude = $_POST['latitude'];
			$dispo->longitude = $_POST['longitude'];
			$dispo->perimetre = $_POST['km'];
			$dispo->vehicule = $_POST['vehicule'];
			$dispo->id_jour = $_POST['day'];
			$dispo->heure_debut = $_POST['heure_debut'];
			$dispo->minute_debut = $_POST['minute_debut'];
			$dispo->heure_fin = $_POST['heure_fin'];
			$dispo->minute_fin = $_POST['minute_fin'];
			
			$dispo->save();
			
			$dispo->addUpdateDispo($dispo->id);
			$this->redirect('livreur', 'user', '', array('id_user' => $_POST['id_livreur']));
		} else {
			$this->redirect('livreurs', 'user');
		}
	}
	
	public function deleteDispo ($request) {
		$dispo = new Model_Dispo(true, $request->dbConnector);
		$dispo->id = $_GET['id_dispo'];
		$dispo->remove();
		$this->redirect('livreur', 'user', '', array('id_user' => $_GET['id_user']));
	}
}