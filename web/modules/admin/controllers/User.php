<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Perimetre.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/CommandeHistory.php";

class Controller_User extends Controller_Admin_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "livreurs" :
					$this->livreurs($request);
					break;
				case "livreur" :
					$this->livreur($request);
					break;
				case "edit" :
					$this->edit($request);
					break;
				case "clients" :
					$this->clients($request);
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
			}
		}
	}
	
	public function enable ($request) {
		$model = new Model_User();
		$model->id = trim($_GET["id_user"]);
		$model->enable();
		$this->redirect('livreurs', 'user');
	}
	
	public function disable ($request) {
		$model = new Model_User();
		$model->id = trim($_GET["id_user"]);
		$model->disable();
		$this->redirect('livreurs', 'user');
	}
	
	public function livreurs ($request) {
		$modelUser = new Model_User();
		$request->livreurs = $modelUser->getAllLivreurs();
		$request->title = "Administration - livreurs";
		$request->vue = $this->render("user/livreurs.php");
	}
	
	public function livreur ($request) {
		$modelUser = new Model_User();
		$modelUser->id = $_GET['id_user'];
		$request->livreur = $modelUser->getLivreur();
		$modelCommande = new Model_Commande();
		$modelCommande->uid = $_GET['id_user'];
		$request->commandes = $modelCommande->loadCommandeLivreur();
		$modelCommandeHistory = new Model_Commande_History();
		$modelCommandeHistory->uid = $_GET['id_user'];
		$request->commandesHistory = $modelCommandeHistory->loadCommandeLivreur();
		$request->title = "Administration - livreur";
		$request->vue = $this->render("user/livreur.php");
	}
	
	public function edit ($request) {
		if ($request->request_method == "POST") {
			$nom = $_POST['nom'];
			$prenom = $_POST['prenom'];
			$login = $_POST['login'];
			$email = $_POST['email'];
			$telephone = $_POST['telephone'];
			
			$modelUser = new Model_User();
			$modelUser->nom = $nom;
			$modelUser->prenom = $prenom;
			$modelUser->login = $login;
			$modelUser->email = $email;
			$modelUser->password = generatePassword();
			$modelUser->inscription_token = generateToken();
			$modelUser->telephone = $telephone;
			$modelUser->status = "LIVREUR";
			
			for ($i = 1 ; $i <= 7 ; $i++) {
				if (!isset($_POST['ferme_'.$i]) || $_POST['ferme_'.$i] != 'on') {
					$horaire = new Model_Horaire();
					$horaire->id_jour = $i;
					$horaire->heure_debut = $_POST['de_'.$i.'_heure'];
					$horaire->minute_debut = $_POST['de_'.$i.'_minute'];
					$horaire->heure_fin = $_POST['a_'.$i.'_heure'];
					$horaire->minute_fin = $_POST['a_'.$i.'_minute'];
					$modelUser->addHoraire($horaire);
				}
			}
			
			for ($i = 1 ; $i <= 5 ; $i++) {
				if (isset($_POST['per_cp_'.$i]) && $_POST['per_cp_'.$i] != '' && isset($_POST['per_ville_'.$i]) && $_POST['per_ville_'.$i] != '') {
					$perimetre = new Model_Perimetre();
					$perimetre->code_postal = $_POST['per_cp_'.$i];
					$perimetre->ville = $_POST['per_ville_'.$i];
					$modelUser->addPerimetre($perimetre);
				}
			}
			
			$modelUser->save();
			
			$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription_livreur.html');
			
			$messageContent = str_replace("[NOM]", $nom, $messageContent);
			$messageContent = str_replace("[PRENOM]", $prenom, $messageContent);
			$messageContent = str_replace("[LOGIN]", $login, $messageContent);
			$messageContent = str_replace("[PASSWORD]", $modelUser->password, $messageContent);
			$messageContent = str_replace("[UID]", $uid, $messageContent);
			$messageContent = str_replace("[TOKEN]", $token, $messageContent);
			$messageContent = str_replace("[WEBSITE_URL]", WEBSITE_URL, $messageContent);
			
			send_mail ($modelUser->email, "Création de votre compte livreur", $messageContent);
			$this->redirect('livreurs', 'user');
		} else {
			$request->title = "Administration - livreur";
			if (isset($_GET['id_restaurant'])) {
				$modelRestaurant = new Model_Restaurant();
				$modelRestaurant->id = $_GET['id_restaurant'];
				$request->restaurant = $modelRestaurant->getOne();
			}
			$request->vue = $this->render("user/edit.php");
		}
	}
	
	public function clients ($request) {
		$modelUser = new Model_User();
		$request->clients = $modelUser->getAllClients();
		$request->title = "Administration - clients";
		$request->vue = $this->render("user/clients.php");
	}
	
	public function client ($request) {
		$modelUser = new Model_User();
		$modelUser->id = $_GET['id_user'];
		$request->client = $modelUser->getClient();
		$modelCommande = new Model_Commande();
		$modelCommande->uid = $_GET['id_user'];
		$request->commandes = $modelCommande->loadCommandeClient();
		$request->title = "Administration - client";
		$request->vue = $this->render("user/client.php");
	}
}