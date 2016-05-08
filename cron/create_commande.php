<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Commande.php";
	include_once ROOT_PATH."models/PreCommande.php";
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/Restaurant.php";
	include_once ROOT_PATH."models/GCMPushMessage.php";
	
	$preCommandeModel = new Model_Pre_Commande();
	$commandes = $preCommandeModel->getTodayCommande();
	foreach ($commandes as $preCommande) {
		$commande = new Model_Commande();
		if ($commande->createFromCommande($preCommande)) {
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
			
			$messageContent =  file_get_contents (ROOT_PATH.'mails/nouvelle_commande_pre_commande.html');
			$messageContent = str_replace("[COMMANDE_ID]", $preCommande->id, $messageContent);
			$messageContent = str_replace("[RESTAURANT_NAME]", $preCommande->restaurant->nom, $messageContent);
			$messageContent = str_replace("[URL]", WEBSITE_URL.'?controler=commande&id='.$commande->id, $messageContent);
			send_mail ($preCommande->client->email, "Nouvelle commande", $messageContent);
			
			
			$preCommande->remove();
		} else {
			writeLog (CRON_LOG, "erreur lors de la création de la pré commande #".$preCommande->id, LOG_LEVEL_ERROR);
		}
	}