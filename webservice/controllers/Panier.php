<?php

include_once ROOT_PATH."function.php";

include_once MODEL_PATH.'Template.php';
include_once MODEL_PATH.'User.php';
include_once MODEL_PATH.'Panier.php';
include_once MODEL_PATH.'Restaurant.php';
include_once MODEL_PATH.'Horaire.php';
include_once MODEL_PATH."CodePromo.php";
include_once MODEL_PATH.'Carte.php';
include_once MODEL_PATH."Format.php";
include_once MODEL_PATH."Supplement.php";
include_once MODEL_PATH."Option.php";
include_once MODEL_PATH."OptionValue.php";
include_once MODEL_PATH."Accompagnement.php";
include_once MODEL_PATH."Menu.php";
include_once MODEL_PATH."Contenu.php";
include_once MODEL_PATH."Categorie.php";
include_once MODEL_PATH."Commande.php";
include_once MODEL_PATH."GCMPushMessage.php";
include_once MODEL_PATH."PDF.php";
include_once MODEL_PATH."Nexmo.php";
include_once MODEL_PATH."SMS.php";
include_once MODEL_PATH."Notification.php";
include_once MODEL_PATH."Paiement.php";

class Controller_Panier extends Controller_Template {
	
	public function handle() {
		$this->init();
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "all" :
					$this->getAll();
					break;
				case "getByUser" :
					$this->getByUser();
					break;
				case "ajouter" :
					$this->ajouter();
					break;
				case "commande" :
					$this->commande();
					break;
				case "remove" :
					$this->remove();
					break;
				case "view" :
					$this->view();
					break;
				case "addCarte" :
					$this->addCarte();
					break;
				case "validate" :
					$this->validate($request);
					break;
				case "valideCarte" :
					$this->valideCarte($request);
					break;
			}
		} else {
			$this->getAll();
		}
	}
	
	private function getAll () {
		
	}
	
	private function getByUser () {
		if (isset($_GET["id_user"])) {
			$id_user = $_GET["id_user"];
			$ext = $this->getExtension();
			$model = new Model_Panier();
			$result = $model->getByUser($id_user);
			if ($result !== false) {
				require 'vue/panier_get.'.$ext.'.php';
			}
		}
	}
	
	private function ajouter () {
		if (!isset($_POST["id_menu"])) {
			die();
		}
		if (!isset($_POST["id_user"])) {
			die();
		}
		if (!isset($_POST["quantite"])) {
			die();
		}
		$id_menu = $_POST["id_menu"];
		$id_user = $_POST["id_user"];
		$quantite = $_POST["quantite"];
		$modelPanier = new Model_Panier();
		$id_panier = $modelPanier->create($id_user);
		$id_panier_menu = $modelPanier->addMenu($id_panier, $id_menu, $quantite);
		$modelContenu = new Model_Contenu();
		$result = $modelContenu->getCategorieByMenu($id_menu);
		foreach ($result as $key => $value) {
			$id_categorie = $value["id_categorie"];
			$id_contenu = $value["id"];
			$quantite = $value["quantite"];
			if (isset($_POST[$id_categorie."_".$id_contenu])) {
				$nbEnregistrement = 1;
				if ($quantite > 1) {
					$nbEnregistrement = $_POST["select".$id_categorie."_".$id_contenu];
				}
				for ($i = 0 ; $i < $nbEnregistrement ; $i++) {
					$modelPanier->create($id_panier_menu, $id_contenu);
				}
			}
		}
	}
	
	private function commande () {
		if (!isset($_POST["id_user"])) {
			$this->error(400, "Bad request");
		}
		$panier = new Model_Panier();
		$panier->uid = $_POST["id_user"];
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
			
			$messageContent =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_admin.html');
			
			$messageContent = str_replace("[COMMANDE_ID]", $commande->id, $messageContent);
			
			send_mail ("admin@homemenus.fr", "Nouvelle commande", $messageContent);
		}
	}
	
	private function remove () {
		if (!isset($_POST["id_menu"])) {
			die();
		}
		if (!isset($_POST["id_user"])) {
			die();
		}
		$id_menu = $_POST["id_menu"];
		$id_user = $_POST["id_user"];
		$modelPanier = new Model_Panier();
		$modelPanier->remove($id_user, $id_menu);
	}
	
	private function initPanier ($id_user, $id_restaurant) {
		$panier = new Model_Panier();
		if ($id_user !== false) {
			$panier->uid = $id_user;
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		$panier->init();
		if ($panier->id_restaurant == -1) {
			$panier->setRestaurant($id_restaurant);
		} else if ($panier->id_restaurant != $id_restaurant) {
			$this->error(400, "Bad request");
		}
		return $panier;
	}
	
	private function view () {
		$panier = new Model_Panier();
		if (isset($_GET['id_user'])) {
			$panier->uid = $_GET['id_user'];
		} else {
			$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		}
		$panier->loadPanier();
		require 'vue/panier/panier.'.$this->ext.'.php';
	}
	
	public function addCarte () {
		if ($_SERVER['REQUEST_METHOD'] != "POST") {
			$this->error(405, "Method not allowed");
		}
		if (!isset($_POST['id_carte'])) {
			$this->error(409, "Conflict");
		}
		$id_restaurant = $_POST['id_restaurant'];
		if (isset($_POST['id_user'])) {
			$id_user = $_POST['id_user'];
		} else {
			$id_user = false;
		}
		$panier = $this->initPanier ($id_user, $id_restaurant);
		$quantite = $_POST['quantite'];
		$id_carte = $_POST['id_carte'];
		$format = $_POST['format'];
		$modelCarte = new Model_Carte();
		$modelCarte->id = $id_carte;
		$modelCarte->load();
		$id_panier_carte = $panier->addCarte($id_carte, $format, $quantite);
		foreach ($modelCarte->options as $option) {
			foreach ($option->values as $value) {
				if (isset($_POST['check_option_'.$option->id.'_'.$value->id])) {
					$panier->addCarteOption($id_panier_carte, $option->id, $value->id);
				}
			}
		}
		foreach ($modelCarte->accompagnements as $accompagnement) {
			foreach ($accompagnement->cartes as $carte) {
				if (isset($_POST['check_accompagnement_'.$carte->id])) {
					$panier->addCarteAccompagnement($id_panier_carte, $carte->id);
				}
			}
		}
		$carte = $modelCarte->getSupplements();
		foreach ($carte->supplements as $supplement) {
			var_dump($supplement);
			if (isset($_POST['check_supplement_'.$supplement->id])) {
				$panier->addCarteSupplement($id_panier_carte, $supplement->id);
			}
		}
	}
	
	public function validate ($request) {
		if ($_SERVER['REQUEST_METHOD'] != "POST") {
			$this->error(405, "Method not allowed");
		}
		$rue = "";
		$complement = "";
		$ville = "";
		$code_postal = "";
		if (isset($_POST['rue'])) {
			$rue = $_POST['rue'];
		}
		if (isset($_POST['complement'])) {
			$complement = $_POST['complement'];
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
		if (isset($_POST['id_user'])) {
			$panier->uid = $_POST['id_user'];
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
				$this->error(400, "Adresse en dehors du périmètre");
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
	
	public function valideCarte () {
		
		$panier = new Model_Panier();
		$panier->adresse_ip = $_SERVER['REMOTE_ADDR'];
		$panier->init();
		$panier->uid = 3;
		$panier->associateUserToPanier();
		$panier = $panier->load();
		//var_dump($panier); die();
		
		$totalPrix = 0;
		
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
					"description" => "validation commande user 3"
				));
				
				$paymentToken = $charge->id;
				
				$panier = new Model_Panier();
				$panier->uid = 3;
				$panier->init();
				$commande = new Model_Commande();
				if ($commande->create($panier)) {
					$commande->setPaiementMethod("STRIPE", $paymentToken);
					$panier->remove();
					$user = new Model_User();
					
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
					
					send_mail ("fiarimike@yahoo.fr", "Nouvelle commande", $messageContentClient, MAIL_FROM_DEFAULT, $attachments);
					
					$restaurantDir = ROOT_PATH.'files/commandes/'.$today.'/restaurant/';
					
					if(!is_dir($restaurantDir)){
					   mkdir($restaurantDir, 0777, true);
					}
					
					$pdf2 = new PDF();
					$pdf2->generateFactureRestaurant($commande);
					$pdf2->render('F', $restaurantDir.'commande'.$commande->id.'.pdf');
					
					carteDeFidelite($request, $commande);
					
				}
			} catch(\Stripe\Error\Card $e) {
				$stripeCode = $e->stripeCode;
				$panier->setPaymentError("STRIPE", $stripeCode);
				$this->error(400, "Adresse en dehors du périmètre");
			}
		} else {
			var_dump("no token given");
			$this->error(400, "No token");
		}
	}
}
