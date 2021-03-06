<?php

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
include_once ROOT_PATH."models/Nexmo.php";
include_once ROOT_PATH."models/SMS.php";
include_once ROOT_PATH."models/Notification.php";
include_once ROOT_PATH."models/Paiement.php";


class Controller_Panier extends Controller_Default_Template {
	
	public function manage ($request) {
		$this->request = $request;
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
				case "validePaypal" :
					$this->validePaypal($request);
					break;
				case "multi_paiement" :
					$this->multi_paiement($request);
					break;
				case "valideCarteMultiPaiement" :
					$this->valideCarteMultiPaiement($request);
					break;
				case "annuleCarteMultiPaiement" :
					$this->annuleCarteMultiPaiement($request);
					break;
				case "addCodePromo" :
					$this->addCodePromo($request);
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
			$mobileVue = parent::render('panier/'.$vue.'-mobile.php');
			if (file_exists($mobileVue)){
				return $mobileVue;
			}
		}
		return parent::render('panier/'.$vue.'.php');
	}
	
	public function view ($request) {
		$request->disableLayout = true;
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
		$request->vue = $this->render("panier");
	}
	
	private function initPanier ($request, $id_restaurant) {
		$panier = new Model_Panier(true, $request->dbConnector);
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		$panier->init();
		if ($panier->id_restaurant == -1) {
			$restaurant = new Model_Restaurant(true, $request->dbConnector);
			$restaurant->id = $id_restaurant;
			$fields = array ("latitude", "longitude", "temps_preparation");
			$restaurant->get($fields);
			$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
			
			$user_latitude = $_SESSION['search_latitude'];
			$user_longitude = $_SESSION['search_longitude'];
			$adresseUser = $user_latitude.','.$user_longitude;
			
			$result = getDistance($adresseUser, $adresseResto);
			$distance = 0;
			$duration = 0;
			if ($result['status'] == "OK") {
				$distance = $result['distance'] / 1000;
				$duration = ceil($result['duration'] / 60);
			}
			$panier->id_restaurant = $id_restaurant;
			$panier->rue = isset($_SESSION['search_rue']) ? $_SESSION['search_rue'] : '';
			$panier->ville = isset($_SESSION['search_ville']) ? $_SESSION['search_ville'] : '';
			$panier->code_postal = isset($_SESSION['search_cp']) ? $_SESSION['search_cp'] : '';
			$panier->latitude = $user_latitude;
			$panier->longitude = $user_longitude;
			$panier->distance = $distance;
			$panier->temps_livraison = $duration;
			$panier->preparation_restaurant = $restaurant->temps_preparation;
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
		$modelCarte = new Model_Carte(true, $request->dbConnector);
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
		if (!isset($_POST['id_panier'])) {
			$this->error(400, "Bad request");
		}
		if (!isset($_POST['id_panier_carte'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$panier = new Model_Panier(true, $request->dbConnector);
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
		if (!isset($_POST['id_menu'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$id_menu = $_POST['id_menu'];
		$id_restaurant = $_POST['id_restaurant'];
		$modelMenu = new Model_Menu(true, $request->dbConnector);
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
		if (!isset($_POST['id_panier'])) {
			$this->error(400, "Bad request");
		}
		if (!isset($_POST['id_panier_menu'])) {
			$this->error(400, "Bad request");
		}
		$request->disableLayout = true;
		$request->noRender = true;
		$panier = new Model_Panier(true, $request->dbConnector);
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
		$complement = "";
		$ville = "";
		$code_postal = "";
		if (isset($_POST['rue'])) {
			$rue = $_POST['rue'];
			$_SESSION['search_rue'] = $rue;
		}
		if (isset($_POST['complement'])) {
			$complement = $_POST['complement'];
			$_SESSION['complement'] = $complement;
		}
		if (isset($_POST['ville'])) {
			$ville = $_POST['ville'];
			$_SESSION['search_ville'] = $ville;
		}
		if (isset($_POST['code_postal'])) {
			$code_postal = $_POST['code_postal'];
			$_SESSION['search_cp'] = $code_postal;
		}
		if (isset($_POST['telephone'])) {
			$telephone = $_POST['telephone'];
			$_SESSION['telephone'] = $telephone;
		}
		$heure_commande = -1;
		$minute_commande = 0;
		if ((isset($_POST['type']) && $_POST['type'] == "pre_commande") || (!isset($_POST['type']) && isset($_POST['heure_commande'])) ) {
			$heure_commande = $_POST['heure_commande'];
			$minute_commande = $_POST['minute_commande'];
		}
		
		$panier = new Model_Panier(true, $request->dbConnector);
		if ($request->_auth) {
			$panier->uid = $request->_auth->id;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$restaurant = new Model_Restaurant(true, $request->dbConnector);
		$restaurant->id = $panier->getRestaurant();
		$fields = array ("latitude", "longitude");
		$restaurant->get($fields);
		$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
		
		$adresse = $rue.', '.$code_postal.' '.$ville;
		$geocoder = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCfYBzV2jwX5l1gPJ2W4FeCfzJGIRQ37BQ&address=%s&sensor=false";
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
			$duration = 0;
			if ($result['status'] == "OK") {
				$distance = $result['distance'] / 1000;
				$duration = ceil($result['duration'] / 60);
			}
			
			$panier->validate($rue, $complement, $ville, $code_postal, $telephone, $heure_commande, $minute_commande, $distance, $duration);
			
			$result = array();
			$result['distance'] = $distance;
			
			if ($distance < (MAX_KM +1)) {
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
		$panier = new Model_Panier(true, $request->dbConnector);
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
					$panier = new Model_Panier(true, $request->dbConnector);
					$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
					$panier->get();
					$model = new Model_User(true, $request->dbConnector);
					$model->nom = trim($_POST["nom"]);
					$model->prenom = trim($_POST["prenom"]);
					$model->login = trim($_POST["login"]);
					$model->email = trim($_POST["login"]);
					$model->password = trim($_POST["password"]);
					$model->status = USER_CLIENT;
					$model->rue = utf8_encode($panier->rue);
					$model->ville = utf8_encode($panier->ville);
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
					$user = new Model_User(true, $request->dbConnector);
					if (!$user->login($login, $password)) {
						$errorMessageLogin["LOGIN_NOT_FOUND"] = "Le login ou le mot de passe est incorrecte";
					} else if (!$user->is_enable) {
						$errorMessageLogin["NOT_ENABLE"] = "Votre compte est désactivé";
					} else {
						$_SESSION["uid"] = $user->id;
						$_SESSION["session"] = $user->session;
						
						$panier = new Model_Panier(true, $request->dbConnector);
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
		$request->vue = $this->render("finalisation_inscription");
	}
	
	public function finalisation ($request) {
		if (!$request->_auth) {
			$this->redirect("finalisation_inscription", "panier");
		}
		$panier = new Model_Panier(true, $request->dbConnector);
		$panier->uid = $request->_auth->id;
		$request->panier = $panier->load();
		$request->javascripts = array("https://www.paypalobjects.com/api/checkout.js");
		$request->vue = $this->render("panier_validate");
	}
	
	public function valideCarte ($request) {
		
		$panier = new Model_Panier(true, $request->dbConnector);
		$panier->uid = $request->_auth->id;
		$panier = $panier->load();
		
		$totalPrix = 0;
		
		foreach ($panier->carteList as $carte) {
			$totalPrix += $carte->prix;
			foreach ($carte->supplements as $supplement) {
				$totalPrix += $supplement->prix * $carte->quantite;
			}
		}
		
		foreach ($panier->menuList as $menu) {
			$totalPrix += $menu->prix;
		}
		
		if ($panier->code_promo && $panier->code_promo->surPrixLivraison()) {
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
				
				$paymentToken = $charge->id;
				
				$panier = new Model_Panier(true, $request->dbConnector);
				$panier->uid = $request->_auth->id;
				$panier->init();
				$commande = new Model_Commande(true, $request->dbConnector);
				if ($commande->create($panier)) {
					$commande->setPaiementMethod("STRIPE", $paymentToken);
					$panier->remove();
					$user = new Model_User(true, $request->dbConnector);
					
					$restaurantUsers = $user->getRestaurantUsers($panier->id_restaurant);
					if (count($restaurantUsers) > 0) {
						$gcm = new GCMPushMessage(GOOGLE_API_KEY);
						$telephone = '';
						$oldTelephone = '';
						foreach ($restaurantUsers as $restaurantUser) {
							if ($restaurantUser->gcm_token) {
								$message = "Vous avez reçu une nouvelle commande";
								// listre des utilisateurs à notifier
								$gcm->setDevices(array($restaurantUser->gcm_token));
							 
								// Le titre de la notification
								$data = array(
									"title" => "Nouvelle commande",
									"key" => "restaurant-new-commande",
									"id_commande" => $commande->id
								);
							 
								// On notifie nos utilisateurs
								$result = $gcm->send($message, $data);
								
								$notification = new Model_Notification();
								$notification->id_user = $restaurantUser->id;
								$notification->token = $restaurantUser->gcm_token;
								$notification->message = $message;
								$notification->datas = json_encode($data);
								$notification->is_send = true;
								$notification->save();
							}
							
							$telephone = $restaurantUser->telephone;
							
							if ($telephone != '' && $telephone != $oldTelephone) {
								$oldTelephone = $telephone;
								$sms = new Nexmo();
								$sms->message = "Vous avez recu une nouvelle commande";
								$sms->addNumero($telephone);
								$sms->sendMessage();
							}
						}
					} else {
						writeLog(SERVER_LOG, "Auncun utilisateur restaurant trouvé pour la commande #".$commande->id, LOG_LEVEL_WARNING);
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
					
					$prixLivraison = $commande->prix_livraison;
					
					if ($commande->codePromo && $commande->codePromo->surPrixLivraison()) {
						if ($commande->codePromo->estGratuit()) {
							$prixLivraison = 0;
						} else {
							$prixLivraison -= $commande->codePromo->valeur_prix_livraison;
						}
					}
					
					$prixTotal = $commande->prix;
					
					if ($commande->codePromo && $commande->codePromo->surPrixTotal()) {
						if ($commande->codePromo->estGratuit()) {
							$prixTotal = 0;
							$prixLivraison = 0;
						} else {
							if ($commande->codePromo->valeur_prix_total != -1) {
								$prixTotal -= $commande->codePromo->valeur_prix_total;
							}
							if ($commande->codePromo->pourcentage_prix_total != -1) {
								$prixTotal -= ($prixTotal * $commande->codePromo->pourcentage_prix_total) / 100;
							}
						}
					}
					
					$messageContentAdmin =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_admin.html');
					
					$messageContentAdmin = str_replace("[COMMANDE_ID]", $commande->id, $messageContentAdmin);
					$messageContentAdmin = str_replace("[RESTAURANT]", $commande->restaurant->nom, $messageContentAdmin);
					$messageContentAdmin = str_replace("[CLIENT]", $commande->client->nom.' '.$commande->client->prenom, $messageContentAdmin);
					$messageContentAdmin = str_replace("[TOTAL]", $prixTotal, $messageContentAdmin);
					$messageContentAdmin = str_replace("[PRIX_LIVRAISON]", $prixLivraison, $messageContentAdmin);
					
					send_mail (MAIL_ADMIN, "Nouvelle commande", $messageContentAdmin, MAIL_FROM_DEFAULT, $attachments);
					
					$messageContentClient =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_client.html');
					
					$messageContentClient = str_replace("[COMMANDE_ID]", $commande->id, $messageContentClient);
					$messageContentClient = str_replace("[RESTAURANT]", $commande->restaurant->nom, $messageContentClient);
					$messageContentClient = str_replace("[TOTAL]", $prixTotal, $messageContentClient);
					$messageContentClient = str_replace("[PRIX_LIVRAISON]", $prixLivraison, $messageContentClient);
					
					send_mail ($request->_auth->login, "Nouvelle commande", $messageContentClient, MAIL_FROM_DEFAULT, $attachments);
					
					$restaurantDir = ROOT_PATH.'files/commandes/'.$today.'/restaurant/';
					
					if(!is_dir($restaurantDir)){
					   mkdir($restaurantDir, 0777, true);
					}
					
					$pdf2 = new PDF();
					$pdf2->generateFactureRestaurant($commande);
					$pdf2->render('F', $restaurantDir.'commande'.$commande->id.'.pdf');
					
					carteDeFidelite($request, $commande);
					
				}
				$request->vue = $this->render("paypal_success");
			} catch(\Stripe\Error\Card $e) {
				$stripeCode = $e->stripeCode;
				$panier->setPaymentError("STRIPE", $stripeCode);
				$this->redirect("finalisation", "panier", '', array('payment' => 'refused'));
			}
		}
	}
	
	public function validePaypal ($request) {
		
		$panier = new Model_Panier(true, $request->dbConnector);
		$panier->uid = $request->_auth->id;
		$panier = $panier->load();
		
		$totalPrix = 0;
		
		foreach ($panier->carteList as $carte) {
			$totalPrix += $carte->prix;
			foreach ($carte->supplements as $supplement) {
				$totalPrix += $supplement->prix * $carte->quantite;
			}
		}
		
		foreach ($panier->menuList as $menu) {
			$totalPrix += $menu->prix;
		}
		
		if ($panier->code_promo && $panier->code_promo->surPrixLivraison()) {
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
				
		$panier = new Model_Panier(true, $request->dbConnector);
		$panier->uid = $request->_auth->id;
		$panier->init();
		$commande = new Model_Commande(true, $request->dbConnector);
		if ($commande->create($panier)) {
			$commande->setPaiementMethod("PAYPAL", "");
			$panier->remove();
			$user = new Model_User(true, $request->dbConnector);
			
			$restaurantUsers = $user->getRestaurantUsers($panier->id_restaurant);
			if (count($restaurantUsers) > 0) {
				$gcm = new GCMPushMessage(GOOGLE_API_KEY);
				$telephone = '';
				$oldTelephone = '';
				foreach ($restaurantUsers as $restaurantUser) {
					if ($restaurantUser->gcm_token) {
						$message = "Vous avez reçu une nouvelle commande";
						// listre des utilisateurs à notifier
						$gcm->setDevices(array($restaurantUser->gcm_token));
					 
						// Le titre de la notification
						$data = array(
							"title" => "Nouvelle commande",
							"key" => "restaurant-new-commande",
							"id_commande" => $commande->id
						);
					 
						// On notifie nos utilisateurs
						$result = $gcm->send($message, $data);
						
						$notification = new Model_Notification();
						$notification->id_user = $restaurantUser->id;
						$notification->token = $restaurantUser->gcm_token;
						$notification->message = $message;
						$notification->datas = json_encode($data);
						$notification->is_send = true;
						$notification->save();
					}
					
					$telephone = $restaurantUser->telephone;
					
					if ($telephone != '' && $telephone != $oldTelephone) {
						$oldTelephone = $telephone;
						$sms = new Nexmo();
						$sms->message = "Vous avez recu une nouvelle commande";
						$sms->addNumero($telephone);
						$sms->sendMessage();
					}
				}
			} else {
				writeLog(SERVER_LOG, "Auncun utilisateur restaurant trouvé pour la commande #".$commande->id, LOG_LEVEL_WARNING);
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
			
			$prixLivraison = $commande->prix_livraison;
			
			if ($commande->codePromo && $commande->codePromo->surPrixLivraison()) {
				if ($commande->codePromo->estGratuit()) {
					$prixLivraison = 0;
				} else {
					$prixLivraison -= $commande->codePromo->valeur_prix_livraison;
				}
			}
			
			$prixTotal = $commande->prix;
			
			if ($commande->codePromo && $commande->codePromo->surPrixTotal()) {
				if ($commande->codePromo->estGratuit()) {
					$prixTotal = 0;
					$prixLivraison = 0;
				} else {
					if ($commande->codePromo->valeur_prix_total != -1) {
						$prixTotal -= $commande->codePromo->valeur_prix_total;
					}
					if ($commande->codePromo->pourcentage_prix_total != -1) {
						$prixTotal -= ($prixTotal * $commande->codePromo->pourcentage_prix_total) / 100;
					}
				}
			}
			
			$messageContentAdmin =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_admin.html');
			
			$messageContentAdmin = str_replace("[COMMANDE_ID]", $commande->id, $messageContentAdmin);
			$messageContentAdmin = str_replace("[RESTAURANT]", $commande->restaurant->nom, $messageContentAdmin);
			$messageContentAdmin = str_replace("[CLIENT]", $commande->client->nom.' '.$commande->client->prenom, $messageContentAdmin);
			$messageContentAdmin = str_replace("[TOTAL]", $prixTotal, $messageContentAdmin);
			$messageContentAdmin = str_replace("[PRIX_LIVRAISON]", $prixLivraison, $messageContentAdmin);
			
			send_mail (MAIL_ADMIN, "Nouvelle commande", $messageContentAdmin, MAIL_FROM_DEFAULT, $attachments);
			
			$messageContentClient =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_client.html');
			
			$messageContentClient = str_replace("[COMMANDE_ID]", $commande->id, $messageContentClient);
			$messageContentClient = str_replace("[RESTAURANT]", $commande->restaurant->nom, $messageContentClient);
			$messageContentClient = str_replace("[TOTAL]", $prixTotal, $messageContentClient);
			$messageContentClient = str_replace("[PRIX_LIVRAISON]", $prixLivraison, $messageContentClient);
			
			send_mail ($request->_auth->login, "Nouvelle commande", $messageContentClient, MAIL_FROM_DEFAULT, $attachments);
			
			$restaurantDir = ROOT_PATH.'files/commandes/'.$today.'/restaurant/';
			
			if(!is_dir($restaurantDir)){
			   mkdir($restaurantDir, 0777, true);
			}
			
			$pdf2 = new PDF();
			$pdf2->generateFactureRestaurant($commande);
			$pdf2->render('F', $restaurantDir.'commande'.$commande->id.'.pdf');
			
			carteDeFidelite($request, $commande);
			
		}
		$request->vue = $this->render("paypal_success");
	}
	
	public function multi_paiement ($request) {
		if (!$request->_auth) {
			$this->redirect("finalisation_inscription", "panier");
		}
		$panier = new Model_Panier(true, $request->dbConnector);
		$panier->uid = $request->_auth->id;
		$request->panier = $panier->load();
		$paiement = new Model_Paiement(true, $request->dbConnector);
		$paiement->id_panier = $panier->id;
		$request->panier->paiements = $paiement->getByPanier();
		$request->vue = $this->render("multi_paiement");
	}
	
	public function valideCarteMultiPaiement ($request) {
		
		$panier = new Model_Panier(true, $request->dbConnector);
		$panier->uid = $request->_auth->id;
		$panier = $panier->load();
		
		$paiementModel = new Model_Paiement(true, $request->dbConnector);
		$paiementModel->id_panier = $panier->id;
		$panier->paiements = $paiementModel->getByPanier();
		
		$montant = floatval(str_replace(',', '.', $_POST['montant']));
		if (!is_float($montant) || $montant <= 1) {
			$this->redirect("multi_paiement", "panier", '', array('payment' => 'refused'));
		}
		
		$totalPrix = 0;
		$totalPaiement = 0;
		
		foreach ($panier->carteList as $carte) {
			$totalPrix += $carte->prix;
		}
		
		foreach ($panier->menuList as $menu) {
			$totalPrix += $menu->prix;
		}
		
		if ($panier->code_promo && $panier->code_promo->surPrixLivraison()) {
			if (!$panier->code_promo->estGratuit()) {
				$totalPrix += ($panier->prix_livraison - $panier->code_promo->valeur_prix_livraison);
			}
		} else {
			$prix_livraison = $panier->prix_livraison;
		    if ($request->_auth->is_premium) {
				$prix_livraison -= $panier->reduction_premium;
			}
			$totalPrix += $prix_livraison;
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
		
		foreach ($panier->paiements as $paiement) {
			$totalPaiement += $paiement->montant;
		}
		
		if ($montant + $totalPaiement <= $totalPrix) {
		
			require_once WEBSITE_PATH.'res/lib/stripe/init.php';
			if (isset($_POST['stripeToken'])) {
				\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

				// Get the credit card details submitted by the form
				$token = $_POST['stripeToken'];

				// Create the charge on Stripe's servers - this will charge the user's card
				try {
					$charge = \Stripe\Charge::create(array(
						"amount" => $montant * 100, // amount in cents, again
						"currency" => "eur",
						"source" => $token,
						"description" => "validation commande user ".$request->_auth->id
					));
					
					$paymentToken = $charge->id;
					
					$paiementModel->montant = $montant;
					$paiementModel->method = 'STRIPE';
					$paiementModel->token = $paymentToken;
					$paiementModel->save();
					
					if ($totalPrix - ($montant + $totalPaiement) < 1) {
					
						$commande = new Model_Commande(true, $request->dbConnector);
						if ($commande->create($panier)) {
							$commande->setPaiementMethod("MULTI-PAIEMENT", '');
							foreach ($panier->paiements as $paiement) {
								$paiement->remove();
							}
							$paiementModel->remove();
							$panier->remove();
							$user = new Model_User(true, $request->dbConnector);
						
							$restaurantUsers = $user->getRestaurantUsers($panier->id_restaurant);
							if (count($restaurantUsers) > 0) {
								$gcm = new GCMPushMessage(GOOGLE_API_KEY);
								$telephone = '';
								$oldTelephone = '';
								foreach ($restaurantUsers as $restaurantUser) {
									if ($restaurantUser->gcm_token) {
										$message = "Vous avez reçu une nouvelle commande";
										// listre des utilisateurs à notifier
										$gcm->setDevices(array($restaurantUser->gcm_token));
								 
										// Le titre de la notification
										$data = array(
											"title" => "Nouvelle commande",
											"key" => "restaurant-new-commande",
											"id_commande" => $commande->id
										);
									
										// On notifie nos utilisateurs
										$result = $gcm->send($message, $data);
									
										$notification = new Model_Notification();
										$notification->id_user = $restaurantUser->id;
										$notification->token = $restaurantUser->gcm_token;
										$notification->message = $message;
										$notification->datas = json_encode($data);
										$notification->is_send = true;
										$notification->save();
									}
								
									$telephone = $restaurantUser->telephone;
								
									if ($telephone != '' && $telephone != $oldTelephone) {
										$oldTelephone = $telephone;
										$sms = new Nexmo();
										$sms->message = "Vous avez recu une nouvelle commande";
										$sms->addNumero($telephone);
										$sms->sendMessage();
									}
								
								}
							} else {
								writeLog(SERVER_LOG, "Auncun utilisateur restaurant trouvé pour la commande #".$commande->id, LOG_LEVEL_WARNING);
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
							carteDeFidelite($request, $commande);
						}
						$request->vue = $this->render("paypal_success");
					} else {
						$this->redirect("multi_paiement", "panier");
					}
					
				} catch(\Stripe\Error\Card $e) {
					$stripeCode = $e->stripeCode;
					$paiementModel->montant = $montant;
					$paiementModel->method = 'STRIPE';
					$paiementModel->token = '';
					$paiementModel->error_code = $stripeCode;
					$paiementModel->save();
					$this->redirect("multi_paiement", "panier", '', array('payment' => 'refused'));
				}
			} else {
				$this->redirect("multi_paiement", "panier");
			}
		} else {
			$this->redirect("multi_paiement", "panier", '', array('payment' => 'refused'));
		}
	}
	
	public function annuleCarteMultiPaiement ($request) {
		if (isset($_GET["id_paiement"])) {
			$paiementModel = new Model_Paiement(true, $request->dbConnector);
			$paiementModel->id = $_GET["id_paiement"];
			$paiementModel->load();
			
			if ($paiementModel->method == "STRIPE") {
				require_once WEBSITE_PATH.'res/lib/stripe/init.php';
				\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

				try {
					$re = \Stripe\Refund::create(array(
						"charge" => $paiementModel->token,
						"amount" => $paiementModel->montant * 100
					));
					$paiementModel->remove();
				} catch(Stripe\Error\InvalidRequest $e) {
					var_dump($e); die();
				}
			}
		}
		$this->redirect("multi_paiement", "panier");
	}
	
	public function addCodePromo ($request) {
		$codePromo = $_POST['code_promo'];
		
		$request->disableLayout = true;
		$request->noRender = true;
		
		$modelCodePromo = new Model_CodePromo(true, $request->dbConnector);
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
		
		$panier = new Model_Panier(true, $request->dbConnector);
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