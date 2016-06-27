<?php

include_once ROOT_PATH."models/Template.php";
include_once ROOT_PATH."models/Panier.php";
include_once ROOT_PATH."models/Restaurant.php";
include_once ROOT_PATH."models/Horaire.php";
include_once ROOT_PATH."models/Carte.php";
include_once ROOT_PATH."models/Format.php";
include_once ROOT_PATH."models/Formule.php";
include_once ROOT_PATH."models/Supplement.php";
include_once ROOT_PATH."models/Menu.php";
include_once ROOT_PATH."models/Categorie.php";
include_once ROOT_PATH."models/Contenu.php";
include_once ROOT_PATH."models/Commande.php";
include_once ROOT_PATH."models/GCMPushMessage.php";
include_once ROOT_PATH."models/PDF.php";
include_once ROOT_PATH."models/Paypal.php";
include_once ROOT_PATH."models/PaypalItem.php";
include_once ROOT_PATH."models/CodePromo.php";


class Controller_Paypal extends Controller_Default_Template {
	
	public function manage ($request) {
		if (isset($_GET["action"])) {
			$action = $_GET["action"];
			switch ($action) {
				case "index" :
					$this->index($request);
					break;
				case "success" :
					$this->success($request);
					break;
				case "cancel" :
					$this->cancel($request);
					break;
				case "return" :
					$this->return_action($request);
					break;
				case "premium_subscribe" :
					$this->premium_subscribe($request);
					break;
				case "premium_cancel" :
					$this->premium_cancel($request);
					break;
				case "premium_return" :
					$this->premium_return($request);
					break;
			}
		} else {
			$this->index($request);
		}
	}
	
	public function index ($request) {
		
		$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
		$panier = $panier->load();
		
		$paypal = new Paypal();
		
		$totalPrix = 0;
		
		$indice = 1;
		
		foreach ($panier->carteList as $carte) {
			$item = new PaypalItem();
			$item->name = $carte->nom;
			$item->number = $indice;
			$item->price = $carte->prix;
			$item->quantity = $carte->quantity;
			
			$totalPrix += $carte->prix;
			
			$indice++;
			
			$paypal->addItem($item);
		}
		
		foreach ($panier->menuList as $menu) {
			$item = new PaypalItem();
			$item->name = $menu->nom;
			$item->number = $indice;
			$item->price = $menu->prix;
			$item->quantity = $menu->quantity;
			
			$totalPrix += $menu->prix;
			
			$indice++;
			
			$paypal->addItem($item);
		}
		
		$item = new PaypalItem();
		$item->name = "prix de livraison";
		$item->number = $indice;
		$item->price = $panier->prix_livraison;
		$item->quantity = 1;
		
		$totalPrix += $panier->prix_livraison;
		
		$paypal->addItem($item);
		
		$shippingDiscount = 0;
		
		if ($panier->code_promo->surPrixLivraison()) {
			if ($request->panier->code_promo->estGratuit()) {
				$shippingDiscount -= $panier->prix_livraison;
			} else {
				$shippingDiscount -= $panier->code_promo->valeur_prix_livraison;
			}
		}
		
		if ($panier->code_promo->surPrixTotal()) {
			if ($panier->code_promo->estGratuit()) {
				$shippingDiscount -= $totalPrix;
			} else {
				if ($panier->code_promo->valeur_prix_total != -1) {
					$shippingDiscount -= $panier->code_promo->valeur_prix_total;
				}
				if ($panier->code_promo->pourcentage_prix_total != -1) {
					$shippingDiscount -= ($totalPrix * $panier->code_promo->pourcentage_prix_total) / 100;
				}
			}
		}
		
		$paypal->cancelUrl = WEBSITE_URL."index.php?controler=paypal&action=cancel";
		$paypal->returnUrl = WEBSITE_URL."index.php?controler=paypal&action=return";
		
		$paypal->amount = $totalPrix;
		$paypal->shippingDiscount = $shippingDiscount;
		
		$paypal->adresse_name = $request->_auth->nom.' '.$request->_auth->prenom;
		$paypal->adresse_rue = $panier->rue;
		$paypal->adresse_ville = $panier->ville;
		$paypal->adresse_pays = "FR";
		$paypal->adresse_cp = $panier->code_postal;
		$paypal->adresse_telephone = $panier->telephone;
		
		$paypal->setExpressCheckout();
		/*$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
		$panier = $panier->load();
		
		$totalQte = 0;
		$totalPrix = 0;
		
		foreach ($panier->carteList as $carte) {
			$totalQte += 1;
			$totalPrix += $carte->prix;
		}
		foreach ($panier->menuList as $menu) {
			$totalQte += 1;
			$totalPrix += $menu->prix;
		}
		$totalPrix = 1;
		
		$requete = construit_url_paypal(); // Construit les options de base
		// La fonction urlencode permet d'encoder au format URL les espaces, slash, deux points, etc.)
		$requete = $requete."&METHOD=SetExpressCheckout".
			"&CANCELURL=".urlencode(WEBSITE_URL."index.php?controler=paypal&action=cancel").
			"&RETURNURL=".urlencode(WEBSITE_URL."index.php?controler=paypal&action=return").
			"&AMT=".$totalPrix.
			"&CURRENCYCODE=EUR".
			"&DESC=".urlencode("Magnifique Oeuvre d'art (que mon fils de 3 ans a peint.)").
			"&LOCALECODE=FR".
			"&HDRIMG=".urlencode(WEBSITE_URL."res/img/logo.png");
					
		// Affiche la chaîne pour vérifier que la chaîne est bien formatée :
		//echo $requete;

		// Initialise notre session cURL. On lui donne la requête à exécuter
		$ch = curl_init($requete);
		// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné. 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement. 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// On lance l'exécution de la requête URL et on récupère le résultat dans une variable
		$resultat_paypal = curl_exec($ch);

		// S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		if (!$resultat_paypal)
			{echo "<p>Erreur</p><p>".curl_error($ch)."</p>";}

		else // S'il s'est exécuté correctement, on effectue les traitements...
		{
			$liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
			
			// On affiche le tout pour voir que tout est OK.
			//echo "<pre>";
			//print_r($liste_param_paypal);
			//echo "</pre>";

			// Si la requête a été traitée avec succès
			if ($liste_param_paypal['ACK'] == 'Success') {
				// Redirige le visiteur sur le site de PayPal
				//header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);
				header("Location: https://www.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);
				exit();
			} else // En cas d'échec, affiche la première erreur trouvée.
			{
				echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
			}		
			
		}

		// On ferme notre session cURL.
		curl_close($ch);*/
	}
	
	public function cancel ($request) {
		if (ENVIRONNEMENT == "DEV" || ENVIRONNEMENT == "TEST" || ENVIRONNEMENT == "DEMO") {
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
				
				$messageContent =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_admin.html');
				
				$messageContent = str_replace("[COMMANDE_ID]", $commande->id, $messageContent);
				
				send_mail (MAIL_ADMIN, "Nouvelle commande", $messageContent);	
			}
			$request->vue = $this->render("paypal_success.php");
		} else {
			$request->vue = $this->render("paypal_cancel.php");
		}
	}
	
	public function return_action ($request) {
		
		$panier = new Model_Panier();
		$panier->uid = $request->_auth->id;
		$panier = $panier->load();
		
		$paypal = new Paypal();
		
		$totalQte = 0;
		$totalPrix = 0;
		
		$indice = 1;
		
		foreach ($panier->carteList as $carte) {
			$item = new PaypalItem();
			$item->name = $carte->nom;
			$item->number = $indice;
			$item->price = $carte->prix;
			$item->quantity = $carte->quantity;
			
			$totalPrix += $carte->prix;
			
			$indice++;
			
			$paypal->addItem($item);
		}
		
		foreach ($panier->menuList as $menu) {
			$item = new PaypalItem();
			$item->name = $menu->nom;
			$item->number = $indice;
			$item->price = $menu->prix;
			$item->quantity = $menu->quantity;
			
			$totalQte += $menu->quantity;
			$totalPrix += $menu->prix;
			
			$indice++;
			
			$paypal->addItem($item);
		}
		
		$item = new PaypalItem();
		$item->name = "prix de livraison";
		$item->number = $indice;
		$item->price = $panier->prix_livraison;
		$item->quantity = 1;
		
		$totalPrix += $panier->prix_livraison;
		
		$paypal->addItem($item);
		
		$paypal->amount = $totalPrix;
		
		$paypal->token = $_GET["token"];
		$paypal->payer = $_GET["PayerID"];
		
		if ($paypal->doExpressCheckout()) {
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
				
				$messageContent =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_admin.html');
				
				$messageContent = str_replace("[COMMANDE_ID]", $commande->id, $messageContent);
				
				send_mail (MAIL_ADMIN, "Nouvelle commande", $messageContent);
				
			}
			$request->vue = $this->render("paypal_success.php");
		} else {
			$request->vue = $this->render("paypal_failed.php");
		}
	}
	
	public function premium_subscribe ($request) {
		
		$requete = construit_url_paypal(); // Construit les options de base
		// La fonction urlencode permet d'encoder au format URL les espaces, slash, deux points, etc.)
		$requete = $requete."&METHOD=SetExpressCheckout".
			"&CANCELURL=".urlencode(WEBSITE_URL."index.php?controler=paypal&action=premium_cancel").
			"&RETURNURL=".urlencode(WEBSITE_URL."index.php?controler=paypal&action=premium_return").
			"&AMT=15".
			"&CURRENCYCODE=EUR".
			"&DESC=".urlencode("Souscription au compte premium").
			"&LOCALECODE=FR".
			"&HDRIMG=".urlencode(WEBSITE_URL."res/img/logo.png");
					
		// Affiche la chaîne pour vérifier que la chaîne est bien formatée :
		//echo $requete;

		// Initialise notre session cURL. On lui donne la requête à exécuter
		$ch = curl_init($requete);
		// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné. 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement. 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// On lance l'exécution de la requête URL et on récupère le résultat dans une variable
		$resultat_paypal = curl_exec($ch);

		// S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
		if (!$resultat_paypal)
			{echo "<p>Erreur</p><p>".curl_error($ch)."</p>";}

		else // S'il s'est exécuté correctement, on effectue les traitements...
		{
			$liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
			
			// On affiche le tout pour voir que tout est OK.
			/*echo "<pre>";
			print_r($liste_param_paypal);
			echo "</pre>";*/

			// Si la requête a été traitée avec succès
			if ($liste_param_paypal['ACK'] == 'Success') {
				// Redirige le visiteur sur le site de PayPal
				header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);
				exit();
			} else // En cas d'échec, affiche la première erreur trouvée.
			{
				echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
			}		
			
		}

		// On ferme notre session cURL.
		curl_close($ch);
	}
	
	public function premium_cancel ($request) {
		$user = new Model_User();
		$user->id = $request->_auth->id;
		$user->subscribePremium();
		
		$date_debut = date("d/m/Y");
		$nextMonth = date("d/m/Y", mktime(0, 0, 0, date("m")+1, date("d"),   date("Y")));
		$prix = 15;
		
		$filename = ROOT_PATH."files/pdf/premium_".$user->id.'_'.date('Ymd').'.pdf';
		
		$pdf = new PDF();
		$pdf->generateFacturePremium($date_debut, $nextMonth, $prix);
		$pdf->render("F", $filename);
		
		$user->getById();
		
		$messageContent =  file_get_contents (ROOT_PATH.'mails/subscribe_premium.html');
		send_mail ($user->email, "Souscription au compte premium", $messageContent, MAIL_FROM_DEFAULT, array($filename));
		
		$request->vue = $this->render("paypal/premium_success.php");
	}
	
	public function premium_return ($request) {
		
	}
}