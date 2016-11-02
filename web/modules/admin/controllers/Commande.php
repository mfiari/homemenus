<?php

include_once ROOT_PATH."function.php";

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/CommandeHistory.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Option.php";
include_once ROOT_PATH."models/OptionValue.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/GCMPushMessage.php";
include_once ROOT_PATH."models/Panier.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Certificat.php";
include_once ROOT_PATH."models/Commentaire.php";
include_once ROOT_PATH."models/CodePromo.php";

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
				case "carte" :
					$this->carte($request);
					break;
				case "addCarte" :
					$this->addCarte($request);
					break;
				case "removeCarte" :
					$this->removeCarte($request);
					break;
				case "addMenu" :
					$this->addMenu($request);
					break;
				case "removeMenu" :
					$this->removeMenu($request);
					break;
				case "panier" :
					$this->panier($request);
					break;
				case "addCodePromo" :
					$this->addCodePromo($request);
					break;
				case "validate" :
					$this->validate($request);
					break;
				case "finalisation" :
					$this->finalisation($request);
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
		if (isset($_GET['date_debut'])) {
			$request->date_debut = $_GET['date_debut'];
		} else {
			$request->date_debut = '01/'.date('m').'/'.date('Y');
		}
		$dateDebut = datepickerToDatetime($request->date_debut);
		
		if (isset($_GET['date_fin'])) {
			$request->date_fin = $_GET['date_fin'];
		} else {
			$request->date_fin = date('d').'/'.date('m').'/'.date('Y');
		}
		$dateFin = datepickerToDatetime($request->date_fin);
		
		if (isset($_GET['page'])) {
			$request->page = $_GET['page'];
		} else {
			$request->page = 1;
		}
		$page = $request->page;
		
		if (isset($_GET['nbItem'])) {
			$request->nbItem = $_GET['nbItem'];
		} else {
			$request->nbItem = 20;
		}
		$nbItem = $request->nbItem;
		
		$modelCommande = new Model_Commande_History();
		$result = $modelCommande->getAll($dateDebut, $dateFin, $page, $nbItem);
		$request->totalRows = $result["total_rows"];
		$request->commandes = $result["list_commandes"];
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
		if ($request->request_method == "POST") {
			$errorMessage = array();
			if (!isset($_POST["client"]) || trim($_POST["client"]) == "") {
				$errorMessage["EMPTY_CLIENT"] = "Le client ne peut être vide";
			} else {
				$request->selectClient = $_POST["client"];
			}
			if (!isset($_POST["restaurant"]) || trim($_POST["restaurant"]) == "") {
				$errorMessage["EMPTY_RESTAURANT"] = "Le restaurant ne peut être vide";
			} else {
				$request->selectRestaurant = $_POST["restaurant"];
			}
			if (count($errorMessage) == 0) {
				$id_user = $_POST["client"];
				$id_restaurant = $_POST["restaurant"];
				$panier = new Model_Panier();
				$panier->uid = $id_user;
				$panier->init();
				if ($panier->id_restaurant == -1 || $panier->id_restaurant == $id_restaurant) {
					$distance = 0;
					$panier->id_restaurant = $id_restaurant;
					$panier->rue = '';
					$panier->ville = '';
					$panier->code_postal = '';
					$panier->latitude = 0;
					$panier->longitude = 0;
					$panier->distance = $distance;
					$panier->update();
				} else if ($panier->id_restaurant != $id_restaurant) {
					$errorMessage["NOT_EMPTY_PANIER"] = "Le un panier existe déjà pour cette utilisateur";
				}
				if (count($errorMessage) == 0) {
					
					$modelRestaurant = new Model_Restaurant();
					$modelRestaurant->id = $id_restaurant;
					$request->restaurant = $modelRestaurant->loadAll();
					
					$request->restaurant->distance = $distance;
					$request->prix_livraison = $request->restaurant->getPrixLivraison();
					
					$request->panier = $panier->loadPanier();
					
					$request->id_user = $id_user;
			
					$request->title = "Administration - commande";
					$request->javascripts = array("res/js/menu.js");
					$request->vue = $this->render("commande/addPanier.php");
					return;
				} else {
					$request->errorMessage = $errorMessage;
				}
			} else {
				$request->errorMessage = $errorMessage;
			}
		}
		$modelUser = new Model_User();
		$request->clients = $modelUser->getAllClients();
		
		$modelRestaurant = new Model_Restaurant();
		$request->restaurants = $modelRestaurant->getAll();
		
		$request->title = "Administration - commande";
		$request->vue = $this->render("commande/createCommande.php");
	}
	
	private function carte ($request) {
		if (isset($_GET["id_carte"])) {
			$request->disableLayout = true;
			$modelCarte = new Model_Carte();
			$modelCarte->id = $_GET['id_carte'];
			$request->id_restaurant = $_GET['id'];
			$request->id_user = $_GET['id_user'];
			$request->carte = $modelCarte->load();
			$request->carte->getLogo($request->id_restaurant);
			
			$request->vue = $this->render("commande/carteDetail.php");
		} 
	}
	
	public function addCarte ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
			return;
		}
		if (!isset($_POST['id_carte'])) {
			$this->error(409, "Conflict");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$id_restaurant = $_POST['id_restaurant'];
		
		$panier = new Model_Panier();
		$panier->uid = $_POST['id_user'];
		$panier->init();
		
		$quantite = $_POST['quantite'];
		$id_carte = $_POST['id_carte'];
		$format = $_POST['format'];
		$modelCarte = new Model_Carte();
		$modelCarte->id = $id_carte;
		$modelCarte->load();
		$id_panier_carte = $panier->addCarte($id_carte, $format, $quantite);
		foreach ($modelCarte->options as $option) {
			if (isset($_POST['check_option_'.$option->id])) {
				$panier->addCarteOption($id_panier_carte, $option->id, $_POST['check_option_'.$option->id]);
			}
		}
		foreach ($modelCarte->accompagnements as $accompagnement) {
			foreach ($accompagnement->cartes as $carte) {
				if (isset($_POST['check_accompagnement_'.$carte->id])) {
					$panier->addCarteAccompagnement($id_panier_carte, $carte->id);
				}
			}
		}
		foreach ($modelCarte->supplements as $supplement) {
			if (isset($_POST['check_supplement_'.$supplement->id])) {
				$panier->addCarteSupplement($id_panier_carte, $supplement->id);
			}
		}
	}
	
	public function removeCarte ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
			return;
		}
		if (!isset($_POST['id_panier'])) {
			$this->error(400, "Bad request");
		}
		if (!isset($_POST['id_panier_carte'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$panier = new Model_Panier();
		$panier->uid = $_POST['id_user'];
		$panier->id = $_POST['id_panier'];
		$id_panier_carte = $_POST['id_panier_carte'];
		if (!$panier->removePanierCarte($id_panier_carte)) {
			$this->error(500, "Bad request");
		}
		$totalArticle = $panier->getNbArticle();
		if ($totalArticle == 0) {
			$panier->remove();
		}
	}
	
	public function addMenu ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
		}
		if (!isset($_POST['id_menu'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$id_menu = $_POST['id_menu'];
		$id_restaurant = $_POST['id_restaurant'];
		$modelMenu = new Model_Menu();
		$modelMenu->id = $id_menu;
		
		$panier = new Model_Panier();
		$panier->uid = $_POST['id_user'];
		$panier->init();
		
		$quantite = $_POST['quantite'];
		$id_format = $_POST['id_format'];
		$id_formule = $_POST['id_formule'];
		$id_panier_menu = $panier->addMenu($id_menu, $id_format, $id_formule, $quantite);
		
		$categories = $modelMenu->getCategories($id_formule);
		foreach ($categories as $categorie) {
			if ($categorie['quantite'] == 1) {
				if (isset($_POST['contenu_'.$categorie['id']])) {
					$id_contenu = $_POST['contenu_'.$categorie['id']];
					$panier->addContenu($id_panier_menu, $id_contenu);
				}
			}
		}
	}
	
	public function removeMenu ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
			return;
		}
		if (!isset($_POST['id_panier'])) {
			$this->error(400, "Bad request");
		}
		if (!isset($_POST['id_panier_menu'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$panier = new Model_Panier();
		$panier->uid = $_POST['id_user'];
		$panier->id = $_POST['id_panier'];
		$id_panier_menu = $_POST['id_panier_menu'];
		if (!$panier->removePanierMenu($id_panier_menu)) {
			$this->error(500, "Bad request");
		}
		$totalArticle = $panier->getNbArticle();
		if ($totalArticle == 0) {
			$panier->remove();
		}
	}
	
	private function panier ($request) {
		if (isset($_GET["type"]) && $_GET["type"] == "ajax") {
			$request->disableLayout = true;
		} else if ($request->mobileDetect && $request->mobileDetect->isMobile() && !$request->mobileDetect->isTablet()) {
			$request->disableLayout = false;
		} else {
			$request->disableLayout = true;
		}
		$panier = new Model_Panier();
		$panier->uid = $_GET['id_user'];
		$request->panier = $panier->loadPanier();
		$request->vue = $this->render("commande/panier.php");
	}
	
	public function addCodePromo ($request) {
		$codePromo = $_POST['code_promo'];
		
		$request->disableLayout = true;
		$request->noRender = true;
		
		$modelCodePromo = new Model_CodePromo();
		$modelCodePromo->code = $codePromo;
		if ($modelCodePromo->getByCode() === false) {
			$this->error(404, "Not found");
		}
		
		if ($modelCodePromo->isPrivate()) {
			if ($request->_auth === false) {
				$this->error(403, "Forbidden");
			}
			if (!$modelCodePromo->isBoundToUser(3)) {
				$this->error(401, "Unauthorized");
			}
			if ($modelCodePromo->hasBeenUseByUser(3)) {
				$this->error(410, "Gone");
			}
		}
		
		$panier = new Model_Panier();
		$panier->uid = $_POST['id_user'];
		$panier->init();
		
		if ($modelCodePromo->surRestaurant()) {
			if (!$modelCodePromo->isBoundToRestaurant($panier->id_restaurant)) {
				$this->error(400, "Bad Request");
			}
		}
		$panier->setCodePromo ($modelCodePromo->id);
	}
	
	public function validate ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
			return;
		}
		$rue = "";
		$ville = "";
		$code_postal = "";
		if (isset($_POST['rue'])) {
			$rue = $_POST['rue'];
		}
		if (isset($_POST['ville'])) {
			$ville = $_POST['ville'];
		}
		if (isset($_POST['code_postal'])) {
			$code_postal = $_POST['code_postal'];
		}
		if (isset($_POST['telephone'])) {
			$telephone = $_POST['telephone'];
		}
		$heure_commande = -1;
		$minute_commande = 0;
		if ((isset($_POST['type']) && $_POST['type'] == "pre_commande") || (!isset($_POST['type']) && isset($_POST['heure_commande'])) ) {
			$heure_commande = $_POST['heure_commande'];
			$minute_commande = $_POST['minute_commande'];
		}
		
		$panier = new Model_Panier();
		$panier->uid = $_POST['id_user'];
		
		$restaurant = new Model_Restaurant();
		$restaurant->id = $panier->getRestaurant();
		$fields = array ("latitude", "longitude");
		$restaurant->get($fields);
		$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
		
		$adresse = $rue.', '.$code_postal.' '.$ville;
		$geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
		$localisation = urlencode($adresse);
		$query = sprintf($geocoder,$localisation);
		$rd = json_decode(file_get_contents($query));
		
		if ($rd->{'status'} == "OK") {
			$coord = $rd->{'results'}[0]->{'geometry'}->{'location'};
			$user_latitude = $coord->{'lat'};
			$user_longitude = $coord->{'lng'};
			
			$adresseUser = $user_latitude.','.$user_longitude;
			
			$result = getDistance($adresseUser, $adresseResto);
			$distance = 0;
			if ($result['status'] == "OK") {
				$distance = $result['distance'] / 1000;
			}
			
			$panier->validate($rue, $ville, $code_postal, $telephone, $heure_commande, $minute_commande, $distance);
			
			$result = array();
			$result['distance'] = $distance;
			
			if ($distance < 16) {
				writeLog(SERVER_LOG, "Adresse correcte", LOG_LEVEL_INFO, "$rue, $code_postal $ville");
			} else {
				writeLog(SERVER_LOG, "Adresse en dehors du périmètre", LOG_LEVEL_ERROR, "$rue, $code_postal $ville");
			}
			
			$request->disableLayout = true;
			$request->noRender = true;
			echo json_encode($result);
		} else {
			$this->error(404, "Not found");
			writeLog(SERVER_LOG, "Adresse panier invalide", LOG_LEVEL_ERROR, "$rue, $code_postal $ville");
		}
	}
	
	public function finalisation ($request) {
		$panier = new Model_Panier();
		$panier->uid = $_GET['id_user'];
		$request->panier = $panier->load();
		$request->vue = $this->render("commande/panier_validate.php");
	}
	
}