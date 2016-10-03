<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/User.php";
include_once ROOT_PATH."models/Panier.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/GCMPushMessage.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Option.php";
include_once ROOT_PATH."models/OptionValue.php";
include_once ROOT_PATH."models/Accompagnement.php";
include_once ROOT_PATH."models/CodePromo.php";
include_once ROOT_PATH."models/PDF.php";


class Controller_Panier extends Controller_Default_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "view" :
					$this->view($request);
					break;
				case "addCarte" :
					$this->addCarte($request);
					break;
				case "showCarteDetail" :
					$this->showCarteDetail($request);
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
				case "validate" :
					$this->validate($request);
					break;
				case "finalisation_inscription" :
					$this->finalisation_inscription($request);
					break;
				case "finalisation" :
					$this->finalisation($request);
					break;
				case "commande" :
					$this->commande($request);
					break;
				case "valideCarte" :
					$this->valideCarte($request);
					break;
				case "addCodePromo" :
					$this->addCodePromo($request);
					break;
			}
		}
	}
	
	public function view ($request) {
		$request->disableLayout = true;
		$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
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
		$request->vue = $this->render("panier.php");
	}
	
	private function initPanier ($request, $id_restaurant) {
		$panier = new Model_Panier();
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		$panier->init();
		if ($panier->id_restaurant == -1) {
			$restaurant = new Model_Restaurant();
			$restaurant->id = $id_restaurant;
			$fields = array ("latitude", "longitude");
			$restaurant->get($fields);
			$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
			
			$user_latitude = $_SESSION['search_latitude'];
			$user_longitude = $_SESSION['search_longitude'];
			$adresseUser = $user_latitude.','.$user_longitude;
			
			$result = getDistance($adresseUser, $adresseResto);
			$distance = 0;
			if ($result['status'] == "OK") {
				$distance = $result['distance'] / 1000;
			}
			$panier->id_restaurant = $id_restaurant;
			$panier->rue = $_SESSION['search_rue'];
			$panier->ville = $_SESSION['search_ville'];
			$panier->code_postal = $_SESSION['search_cp'];
			$panier->latitude = $user_latitude;
			$panier->longitude = $user_longitude;
			$panier->distance = $distance;
			$panier->update();
		} else if ($panier->id_restaurant != $id_restaurant) {
			$this->error(400, "Bad request");
		}
		return $panier;
	}
	
	public function addCarte ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
			return;
		}
		/*if (!$request->_auth) {
			$this->error(403, "Not authorized");
			return;
		}*/
		if (!isset($_POST['id_carte'])) {
			$this->error(409, "Conflict");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$id_restaurant = $_POST['id_restaurant'];
		$panier = $this->initPanier ($request, $id_restaurant);
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
	
	public function showCarteDetail ($request) {
		
	}
	
	public function removeCarte ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
			return;
		}
		/*if (!$request->_auth) {
			$this->error(403, "Not authorized");
			return;
		}*/
		if (!isset($_POST['id_panier'])) {
			$this->error(400, "Bad request");
		}
		if (!isset($_POST['id_panier_carte'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$panier = new Model_Panier();
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
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
		/*if (!$request->_auth) {
			$this->error(403, "Not authorized");
		}*/
		if (!isset($_POST['id_menu'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$id_menu = $_POST['id_menu'];
		$id_restaurant = $_POST['id_restaurant'];
		$modelMenu = new Model_Menu();
		$modelMenu->id = $id_menu;
		$panier = $this->initPanier ($request, $id_restaurant);
		
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
		/*if (!$request->_auth) {
			$this->error(403, "Not authorized");
			return;
		}*/
		if (!isset($_POST['id_panier'])) {
			$this->error(400, "Bad request");
		}
		if (!isset($_POST['id_panier_menu'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$panier = new Model_Panier();
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
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
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		
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
	
	public function commande ($request) {
		if ($request->request_method != "POST") {
			$this->error(405, "Method not allowed");
			return;
		}
		if (!$request->_auth) {
			$this->error(403, "Not authorized");
			return;
		}
		$rue = "";
		$ville = "";
		$code_postal = "";
		if (isset($_POST['rue'])) {
			
		}
		if (isset($_POST['ville'])) {
			
		}
		if (isset($_POST['code_postal'])) {
			
		}
		if (isset($_POST['telephone'])) {
			
		}
		$heure_commande = -1;
		$minute_commande = 0;
		if ((isset($_POST['type']) && $_POST['type'] == "pre_commande") || (!isset($_POST['type']) && isset($_POST['heure_commande'])) ) {
			$heure_commande = $_POST['heure_commande'];
			$minute_commande = $_POST['minute_commande'];
		}
		$rue = $_POST['rue'];
		$ville = $_POST['ville'];
		$code_postal = $_POST['code_postal'];
		$telephone = $_POST['telephone'];
		$request->disableLayout = true;
		$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
		$panier->validate($rue, $ville, $code_postal, $telephone, $heure_commande, $minute_commande);
	}
	
	public function finalisation_inscription ($request) {
		if ($request->request_method == "POST") {
			if (isset($_POST['subscribeButton'])) {
				$errorMessageSubscribe = array();
				if (!isset($_POST["nom"]) || trim($_POST["nom"]) == "") {
					$errorMessageSubscribe["EMPTY_NOM"] = "Le nom ne peut être vide";
				} else {
					$request->fieldNom = $_POST["nom"];
				}
				if (!isset($_POST["prenom"]) || trim($_POST["prenom"]) == "") {
					$errorMessageSubscribe["EMPTY_PRENOM"] = "Le prénom ne peut être vide";
				} else {
					$request->fieldPrenom = $_POST["prenom"];
				}
				if (!isset($_POST["login"]) || trim($_POST["login"]) == "") {
					$errorMessageSubscribe["EMPTY_LOGIN"] = "Le login ne peut être vide";
				} else {
					$request->fieldEmail = $_POST["login"];
				}
				if (!isset($_POST["password"]) || trim($_POST["password"]) == "") {
					$errorMessageSubscribe["EMPTY_PASSWORD"] = "Le mot de passe ne peut être vide";
				}
				if (count($errorMessageSubscribe) == 0) {
					$panier = new Model_Panier();
					$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
					$panier->get();
					$model = new Model_User();
					$model->nom = trim($_POST["nom"]);
					$model->prenom = trim($_POST["prenom"]);
					$model->login = trim($_POST["login"]);
					$model->email = trim($_POST["login"]);
					$model->password = trim($_POST["password"]);
					$model->status = USER_CLIENT;
					$model->rue = $panier->rue;
					$model->ville = $panier->ville;
					$model->code_postal = $panier->code_postal;
					$model->inscription_token = generateToken();
					$model->telephone = $panier->telephone;
					if ($model->isLoginAvailable()) {
						$model->beginTransaction();
						if ($model->save()) {
							
							$panier->associate($model);
							
							$model->enable();
							$model->login($model->login, $model->password);
							$_SESSION["uid"] = $model->id;
							$_SESSION["session"] = $model->session;
							
							$messageContent =  file_get_contents (ROOT_PATH.'mails/create_compte.html');
							$messageContent = str_replace("[NOM]", $model->nom, $messageContent);
							$messageContent = str_replace("[PRENOM]", $model->prenom, $messageContent);
							$messageContent = str_replace("[LOGIN]", $model->login, $messageContent);
							
							send_mail ($model->email, "Création de votre compte", $messageContent);
							
							
							$messageContent =  file_get_contents (ROOT_PATH.'mails/inscription_admin.html');
							$messageContent = str_replace("[PRENOM]", $model->prenom, $messageContent);
							$messageContent = str_replace("[NOM]", $model->nom, $messageContent);
							if ($model->ville != '') {
								$messageContent = str_replace("[ADRESSE]", $model->ville.' ('.$model->code_postal.')', $messageContent);
							} else {
								$messageContent = str_replace("[ADRESSE]", "(adresse non renseignée)", $messageContent);
							}
							send_mail (MAIL_ADMIN, "création de compte", $messageContent);
							
							$this->redirect("finalisation", "panier");
						} else {
							$request->errorMessageSubscribe = array("CREATE_ERROR" => "Une erreur s'est produite, veuillez réessayé ultérieurement.");
						}
						$model->endTransaction();
					} else {
						$request->errorMessageSubscribe = array("USER_EXISTS" => "l'email ".$model->email." existe déjà");
					}
				} else {
					$request->errorMessageSubscribe = $errorMessageSubscribe;
				}
			} else if (isset($_POST['loginButton'])) {
				$errorMessageLogin = array();
				if (!isset($_POST["login"]) || trim($_POST["login"]) == "") {
					$errorMessageLogin["EMPTY_LOGIN"] = "Le login ne peut être vide";
				} else {
					$request->fieldEmail = $_POST["login"];
				}
				if (!isset($_POST["password"]) || trim($_POST["password"]) == "") {
					$errorMessageLogin["EMPTY_PASSWORD"] = "Le mot de passe ne peut être vide";
				}
				if (count($errorMessageLogin) == 0) {
					$login = $_POST["login"];
					$password = $_POST["password"];
					$user = new Model_User();
					if (!$user->login($login, $password)) {
						$errorMessageLogin["LOGIN_NOT_FOUND"] = "Le login ou le mot de passe est incorrecte";
					} else if (!$user->is_enable) {
						$errorMessageLogin["NOT_ENABLE"] = "Votre compte est désactivé";
					} else {
						$_SESSION["uid"] = $user->id;
						$_SESSION["session"] = $user->session;
						
						$panier = new Model_Panier();
						$panier->uid = $user->id;
						if ($panier->init() !== false) {
							$panier->remove();
						}
						
						$panier = new Model_Panier();
						$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
						$panier->get();
						
						$panier->associate($user);
								
						$this->redirect("finalisation", "panier");
					}
					$request->errorMessageLogin = $errorMessageLogin;
				} else {
					$request->errorMessageLogin = $errorMessageLogin;
				}
			}
		}
		$request->javascripts = array("res/js/jquery.validate.min.js", "res/js/inscription_panier.js");
		$request->title = "Inscription";
		$request->vue = $this->render("finalisation_inscription.php");
	}
	
	public function finalisation ($request) {
		if (!$request->_auth) {
			$this->redirect("finalisation_inscription", "panier");
		}
		$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
		$request->panier = $panier->load();
		$request->vue = $this->render("panier_validate.php");
	}
	
	public function valideCarte ($request) {
		
		$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
		$panier = $panier->load();
		
		$totalPrix = 0;
		
		foreach ($panier->carteList as $carte) {
			$totalPrix += $carte->prix;
		}
		
		foreach ($panier->menuList as $menu) {
			$totalPrix += $menu->prix;
		}
		
		if ($panier->code_promo->surPrixLivraison()) {
			if (!$panier->code_promo->estGratuit()) {
				$totalPrix += ($panier->prix_livraison - $panier->code_promo->valeur_prix_livraison);
			}
		} else {
			$totalPrix += $panier->prix_livraison;
		}
		
		if ($panier->code_promo->surPrixTotal()) {
			if ($panier->code_promo->estGratuit()) {
				$totalPrix = 0;
			} else {
				if ($panier->code_promo->valeur_prix_total != -1) {
					$totalPrix -= $panier->code_promo->valeur_prix_total;
				}
				if ($panier->code_promo->pourcentage_prix_total != -1) {
					$totalPrix -= ($totalPrix * $panier->code_promo->pourcentage_prix_total) / 100;
				}
			}
		}
		
		require_once WEBSITE_PATH.'res/lib/stripe/init.php';
		if (isset($_POST['stripeToken'])) {
			\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

			// Get the credit card details submitted by the form
			$token = $_POST['stripeToken'];

			// Create the charge on Stripe's servers - this will charge the user's card
			try {
			  $charge = \Stripe\Charge::create(array(
				"amount" => $totalPrix * 100, // amount in cents, again
				"currency" => "eur",
				"source" => $token,
				"description" => "validation commande user ".$request->_auth->id
				));
				
				$panier = new Model_Panier();
				$panier->uid = $request->_auth->id;
				$panier->init();
				$commande = new Model_Commande();
				if ($commande->create($panier)) {
					$panier->remove();
					$user = new Model_User();
					
					$restaurantUsers = $user->getRestaurantUsers($panier->id_restaurant);
					if (count($restaurantUsers) > 0) {
						$registatoin_ids = array();
						$gcm = new GCMPushMessage(GOOGLE_API_KEY);
						foreach ($restaurantUsers as $restaurantUser) {
							array_push($registatoin_ids, $restaurantUser->gcm_token);
						}
						$message = "Vous avez reçu une nouvelle commande";
						// listre des utilisateurs à notifier
						$gcm->setDevices($registatoin_ids);
					 
						// Le titre de la notification
						$data = array(
							"title" => "Nouvelle commande",
							"key" => "restaurant-new-commande",
							"id_commande" => $commande->id
						);
					 
						// On notifie nos utilisateurs
						$result = $gcm->send($message, $data);
						//$result = $gcm->send('/topics/restaurant-commande',$message, $data);
					}
					
					$commande->load();
					
					$today = date('Y-m-d');
					
					$clientDir = ROOT_PATH.'files/commandes/'.$today.'/client/';
					
					if(!is_dir($clientDir)){
					   mkdir($clientDir, 0777, true);
					}
					
					$pdf = new PDF();
					$pdf->generateFactureClient($commande);
					$pdf->render('F', $clientDir.'commande'.$commande->id.'.pdf');
					
					$attachments = array(
						$clientDir.'commande'.$commande->id.'.pdf'
					);
					
					$messageContentAdmin =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_admin.html');
					
					$messageContentAdmin = str_replace("[COMMANDE_ID]", $commande->id, $messageContentAdmin);
					$messageContentAdmin = str_replace("[RESTAURANT]", $commande->restaurant->nom, $messageContentAdmin);
					$messageContentAdmin = str_replace("[CLIENT]", $commande->client->nom.' '.$commande->client->prenom, $messageContentAdmin);
					$messageContentAdmin = str_replace("[TOTAL]", $commande->prix, $messageContentAdmin);
					$messageContentAdmin = str_replace("[PRIX_LIVRAISON]", $commande->prix_livraison, $messageContentAdmin);
					
					send_mail (MAIL_ADMIN, "Nouvelle commande", $messageContentAdmin, MAIL_FROM_DEFAULT, $attachments);
					
					$messageContentClient =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_client.html');
					
					$messageContentClient = str_replace("[COMMANDE_ID]", $commande->id, $messageContentClient);
					$messageContentClient = str_replace("[RESTAURANT]", $commande->restaurant->nom, $messageContentClient);
					$messageContentClient = str_replace("[TOTAL]", $commande->prix, $messageContentClient);
					$messageContentClient = str_replace("[PRIX_LIVRAISON]", $commande->prix_livraison, $messageContentClient);
					
					send_mail ($request->_auth->login, "Nouvelle commande", $messageContentClient, MAIL_FROM_DEFAULT, $attachments);
					
					$restaurantDir = ROOT_PATH.'files/commandes/'.$today.'/restaurant/';
					
					if(!is_dir($restaurantDir)){
					   mkdir($restaurantDir, 0777, true);
					}
					
					$pdf2 = new PDF();
					$pdf2->generateFactureRestaurant($commande);
					$pdf2->render('F', $restaurantDir.'commande'.$commande->id.'.pdf');
					
					/*$messageContentRestaurant =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_restaurant.html');
					
					$messageContentRestaurant = str_replace("[COMMANDE_ID]", $commande->id, $messageContentRestaurant);
					$messageContentRestaurant = str_replace("[TOTAL]", $commande->prix, $messageContentRestaurant);
					
					$attachments2 = array(
						$restaurantDir.'commande'.$commande->id.'.pdf'
					);
					foreach ($restaurantUsers as $restaurantUser) {
						send_mail ($restaurantUser->email, "Nouvelle commande", $messageContentRestaurant, MAIL_FROM_DEFAULT, $attachments2);
					}*/
				}
				$request->vue = $this->render("paypal_success.php");
				
			} catch(\Stripe\Error\Card $e) {
				
			}
		}
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
			if (!$modelCodePromo->isBoundToUser($request->_auth->id)) {
				$this->error(401, "Unauthorized");
			}
			if ($modelCodePromo->hasBeenUseByUser($request->_auth->id)) {
				$this->error(410, "Gone");
			}
		}
		
		$panier = new Model_Panier();
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		$panier->init();
		
		if ($modelCodePromo->surRestaurant()) {
			if (!$modelCodePromo->isBoundToRestaurant($panier->id_restaurant)) {
				$this->error(400, "Bad Request");
			}
		}
		
		$panier->setCodePromo ($modelCodePromo->id);
	}
}