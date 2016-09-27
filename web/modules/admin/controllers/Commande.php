<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
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

class Controller_Commande extends Controller_Admin_Template {
	
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
				case "updateLivreur" :
					$this->updateLivreur($request);
					break;
				case "history" :
					$this->history($request);
					break;
				case "viewHistory" :
					$this->viewHistory($request);
					break;
				case "annule" :
					$this->annule($request);
					break;
				case "validationRestaurant" :
					$this->validationRestaurant($request);
					break;
				case "preparationRestaurant" :
					$this->preparationRestaurant($request);
					break;
				case "recuperationLivreur" :
					$this->recuperationLivreur($request);
					break;
				case "livraisonCommande" :
					$this->livraisonCommande($request);
					break;
				case "renew" :
					$this->renew($request);
					break;
				case "remove" :
					$this->remove($request);
					break;
				case "create" :
					$this->create($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		$modelCommande = new Model_Commande();
		$request->commandes = $modelCommande->getAll();
		$request->title = "Administration - commandes";
		$request->vue = $this->render("commande/index.php");
	}
	
	public function view ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			$request->commande = $commande->load();
			$modelUser = new Model_User();
			$request->livreurs = $modelUser->getAllActifLivreur();
			$request->vue = $this->render("commande/view.php");
		}
	}
	
	public function updateLivreur ($request) {
		if (isset($_POST["id_commande"])) {
			$id_livreur = $_POST["livreur"];
			$commande = new Model_Commande();
			$commande->id = $_POST["id_commande"];
			$livreur = $commande->getLivreur();
			if ($livreur === false ||$livreur->id != $id_livreur) {
				$modelUser = new Model_User();
				$modelUser->id = $id_livreur;
				$modelUser->get();
				$commande->uid = $modelUser->id;
				if ($commande->attributionLivreur()) {
					$gcm = new GCMPushMessage(GOOGLE_API_KEY);
					$message = "Vous avez reçu une nouvelle commande";
					// listre des utilisateurs à notifier
					$gcm->setDevices(array($modelUser->gcm_token));
					// Le titre de la notification
					$data = array(
						"title" => "Nouvelle commande",
						"key" => "livreur-new-commande",
						"id_commande" => $commande->id
					);
					// On notifie nos utilisateurs
					$result = $gcm->send($message, $data);
					
					$message = "Une commande vous a été retiré";
					// listre des utilisateurs à notifier
					$gcm->setDevices(array($livreur->gcm_token));
					// Le titre de la notification
					$data = array(
						"title" => "Changement commande",
						"key" => "livreur-change-commande",
						"id_commande" => $commande->id
					);
					// On notifie nos utilisateurs
					$result = $gcm->send($message, $data);
				}
			}
		}
		$this->redirect('index', 'commande');
	}
	
	public function history ($request) {
		if (isset($_POST['date_debut'])) {
			$request->date_debut = $_POST['date_debut'];
		} else {
			$request->date_debut = '01/'.date('m').'/'.date('Y');
		}
		$dateDebut = datepickerToDatetime($request->date_debut);
		
		if (isset($_POST['date_fin'])) {
			$request->date_fin = $_POST['date_fin'];
		} else {
			$request->date_fin = date('d').'/'.date('m').'/'.date('Y');
		}
		$dateFin = datepickerToDatetime($request->date_fin);
		$modelCommande = new Model_Commande_History();
		$request->commandes = $modelCommande->getAll($dateDebut, $dateFin);
		$request->title = "Administration - commandes";
		$request->vue = $this->render("commande/history.php");
	}
	
	public function viewHistory ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande_History();
			$commande->id = $_GET["id_commande"];
			$request->commande = $commande->load();
			$request->vue = $this->render("commande/viewHistory.php");
		}
	}
	
	public function annule ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			if ($commande->annule()) {
				$livreur = $commande->getLivreur();
				if ($livreur->is_login) {
					if ($livreur->gcm_token) {
						$gcm = new GCMPushMessage(GOOGLE_API_KEY);
						$registatoin_ids = array($livreur->gcm_token);
						$message = "La commande #".$commande->id." a été refusé par le restaurant";
						// listre des utilisateurs à notifier
						$gcm->setDevices($registatoin_ids);
						// Le titre de la notification
						$data = array(
							"title" => "Commande refusé",
							"key" => "livreur-validation-commande",
							"id_commande" => $commande->id
						);
						// On notifie nos utilisateurs
						$result = $gcm->send($message, $data);
					}
				}
				$client = $commande->getClient();
				if ($client->parametre->send_sms_commande /* && $client->telephone commence par 06 ou 07 */) {
					$sms = new Clickatell();
					$sms->message = "Votre commande #".$commande->id." a été refusé par le restaurant";
					$sms->addNumero($client->telephone);
					$sms->sendMessage();
				}
			}
		}
		$this->redirect('index', 'commande');
	}
	
	public function validationRestaurant ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			if ($commande->validation()) {
				$livreur = $commande->getLivreur();
				if ($livreur->is_login) {
					if ($livreur->gcm_token) {
						$gcm = new GCMPushMessage(GOOGLE_API_KEY);
						$registatoin_ids = array($livreur->gcm_token);
						$message = "La commande #".$commande->id." a été validé";
						// listre des utilisateurs à notifier
						$gcm->setDevices($registatoin_ids);
						// Le titre de la notification
						$data = array(
							"title" => "Commande validé",
							"key" => "livreur-validation-commande",
							"id_commande" => $commande->id
						);
						// On notifie nos utilisateurs
						$result = $gcm->send($message, $data);
					}
				}
				$client = $commande->getClient();
				/*var_dump($client);
				var_dump($client->parametre);*/
				if ($client->parametre->send_sms_commande /* && $client->telephone commence par 06 ou 07 */) {
					$sms = new Clickatell();
					$sms->message = "Bonjour, votre commande est en cours de preparation. L'equipe HoMe Menus.";
					$sms->addNumero($client->telephone);
					$sms->sendMessage();
				}
			}
		}
		$this->redirect('index', 'commande');
	}
	
	public function preparationRestaurant ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			if ($commande->finPreparation()) {
				$livreur = $commande->getLivreur();
				if ($livreur->is_login) {
					if ($livreur->gcm_token) {
						$gcm = new GCMPushMessage(GOOGLE_API_KEY);
						$registatoin_ids = array($livreur->gcm_token);
						$message = "La commande #".$commande->id." est prête";
						// listre des utilisateurs à notifier
						$gcm->setDevices($registatoin_ids);
						// Le titre de la notification
						$data = array(
							"title" => "Commande prête",
							"key" => "livreur-preparation-commande",
							"id_commande" => $commande->id
						);
						// On notifie nos utilisateurs
						$result = $gcm->send($message, $data);
					}
				}
			}
		}
		$this->redirect('index', 'commande');
	}
	
	public function recuperationLivreur ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			if ($commande->recuperationCommande()) {
				$client = $commande->getClient();
				if ($client->parametre->send_sms_commande /* && $client->telephone commence par 06 ou 07 */) {
					$sms = new Clickatell();
					$sms->message = "Bonjour, votre commande #".$commande->id." est prête et est en cours de livraison. L'équipe HoMe Menus.";
					$sms->addNumero($client->telephone);
					$sms->sendMessage();
				}
			}
		}
		$this->redirect('index', 'commande');
	}
	
	public function livraisonCommande ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			$commande->livraisonCommande();
		}
		$this->redirect('index', 'commande');
	}
	
	public function renew ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			$commande->renew();
		}
		$this->redirect('index', 'commande');
	}
	
	public function remove ($request) {
		if (isset($_GET["id_commande"])) {
			$commande = new Model_Commande();
			$commande->id = $_GET["id_commande"];
			$commande->remove();
		}
		$this->redirect('index', 'commande');
	}
	
	public function create ($request) {
		
	}
}