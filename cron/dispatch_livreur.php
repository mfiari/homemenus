<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Commande.php";
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/Restaurant.php";
	include_once ROOT_PATH."models/GCMPushMessage.php";
	
	$modelCommande = new Model_Commande();
	$commandes = $modelCommande->getCommandeNonAttribue();
	//var_dump($commandes);
	
	$modelUser = new Model_User();
	foreach ($commandes as $commande) {
		$livreurs = $modelUser->getLivreurAvailableForCommande($commande);
		
		$livreur = getBestLivreur($livreurs, $commande);
		
		//var_dump($livreur);
		
		if ($livreur === false) {
			writeLog(CRON_LOG, "Pas de livreur pour la commande ".$commande->id, LOG_LEVEL_ERROR);
			continue;
		}
		
		if ($livreur->is_login) {
			sendNotificationMessage ($livreur);
		}
		$commande->uid = $livreur->id;
		$commande->attributionLivreur();
	}
	
	
	function getBestLivreur ($livreurs, $commande) {
		if (count($livreurs) == 0) {
			return false;
		}
		if (count($livreurs) == 1) {
			return $livreurs[0];
		}
		$bestLivreur = null;
		$hasCmdInRestaurant = false;
		$nbCommandeLivreur = -1;
		foreach ($livreurs as $livreur) {
			$commande->livreur = $livreur;
			$livreurCommandes = $commande->getCommandeEnCours();
			if ($bestLivreur === null) {
				$bestLivreur = $livreur;
				foreach ($livreurCommandes as $cmd) {
					if ($cmd->restaurant->id == $commande->restaurant->id) {
						$hasCmdInRestaurant = true;
						break;
					}
				}
				$nbCommandeLivreur = count($livreurCommandes);
			} else {
				
				/* 1) si le livreur à une commande à récupérer dans ce restaurant */
				$tmpCommandeInRestaurant = false;
				foreach ($livreurCommandes as $cmd) {
					if ($cmd->restaurant->id == $commande->restaurant->id) {
						$tmpCommandeInRestaurant = true;
						break;
					}
				}
				if (!$tmpCommandeInRestaurant && $hasCmdInRestaurant) {
					continue;
				}
				if ($tmpCommandeInRestaurant && !$hasCmdInRestaurant) {
					$bestLivreur = $livreur;
					$nbCommandeLivreur = count($livreurCommandes);
					continue;
				}
				/* 2) si le livreur à moins de commande */
				if (count($livreurCommandes) >  $nbCommandeLivreur) {
					continue;
				}
				if (count($livreurCommandes) <  $nbCommandeLivreur) {
					$bestLivreur = $livreur;
					$nbCommandeLivreur = count($livreurCommandes);
					continue;
				}
				/* 3) si le livreur se trouve plus près */
				$d1 = getDistanceFromLatLonInKm($bestLivreur->latitude, $bestLivreur->longitude, $commande->restaurant->latitude, $commande->restaurant->longitude);
				$d2 = getDistanceFromLatLonInKm($livreur->latitude, $livreur->longitude, $commande->restaurant->latitude, $commande->restaurant->longitude);
				if ($d2 < $d1) {
					$bestLivreur = $livreur;
				}
			}
		}
		return $bestLivreur;
	}
	
	function sendNotificationMessage ($livreur) {
		if ($livreur->gcm_token) {
			$gcm = new GCMPushMessage(GOOGLE_API_KEY);
			$registatoin_ids = array($livreur->gcm_token);
			$message = "Vous avez reçu une nouvelle commande";
			// listre des utilisateurs à notifier
			$gcm->setDevices($registatoin_ids);
			// Le titre de la notification
			$data = array(
				"title" => "Nouvelle commande",
				"key" => "livreur-new-commande",
				"id_commande" => $commande->id
			);
			// On notifie nos utilisateurs
			$result = $gcm->send($message, $data);
		} else {
			$message = 'Le livreur '.$livreur->id.' ne possède pas de gcm_token';
			writeLog(CRON_LOG, $message, LOG_LEVEL_WARNING);
		}
	}