<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Commande.php";
	include_once MODEL_PATH."User.php";
	include_once MODEL_PATH."Restaurant.php";
	include_once MODEL_PATH."CommandeHistory.php";
	include_once MODEL_PATH."Menu.php";
	include_once MODEL_PATH."Formule.php";
	include_once MODEL_PATH."Contenu.php";
	include_once MODEL_PATH."Carte.php";
	include_once MODEL_PATH."Format.php";
	include_once MODEL_PATH."Categorie.php";
	include_once MODEL_PATH."Supplement.php";
	include_once MODEL_PATH."CodePromo.php";
	include_once MODEL_PATH."Option.php";
	include_once MODEL_PATH."OptionValue.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début historisation commande");
	
	$modelCommande = new Model_Commande();
	$commandes = $modelCommande->getCommandeTerminer();
	
	if (count($commandes) == 0) {
		writeLog (CRON_LOG, "Aucune commande réalisé hier", LOG_LEVEL_WARNING);
	}
	
	$totalCommande = 0;
	$montantTotal = 0;
	
	$totalCommandeAnnule = 0;
	$montantTotalAnnule = 0;
	
	$totalCommandeError = 0;
	$montantTotalError = 0;
	
	$totalAnomalie = 0;
	$montantTotalAnomalie = 0;
	
	$totalNouveauClient = 0;
	
	$commandeNonValideResto = array();
	$commandeNonValideLivreur = array();
	
	$modelCommandeHistory = new Model_Commande_History();
	
	$modelUser = new Model_User();
	$dateDebut = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
	$dateFin = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
	$clients = $modelUser->getNouveauClientByMonth($dateDebut, $dateFin);
	foreach ($clients as $client) {
		$totalNouveauClient += $client["total"];
	}
	
	foreach ($commandes as $commande) {
		if ($commande->etape == -1) {
			$totalCommandeAnnule++;
			$montantTotalAnnule += $commande->prix + $commande->prix_livraison;
			writeLog (CRON_LOG, "Echec historisation commande #".$commande->id." : commande annulée", LOG_LEVEL_WARNING);
			$commande->remove();
		} else if ($commande->etape != 4) {
			$totalCommandeError++;
			$montantTotalError += $commande->prix + $commande->prix_livraison;
			writeLog (CRON_LOG, "Echec historisation commande #".$commande->id." : commande ".$commande->getStatus (), LOG_LEVEL_ERROR);
		} else if ($modelCommandeHistory->saveCommande($commande)) {
			$messageContent =  file_get_contents (ROOT_PATH.'mails/fin_commande.html');
			send_mail ($commande->client->email, "Merci de votre commande", $messageContent);
			if (!$commande->client->send_questionnaire) {
				$messageContent =  file_get_contents (ROOT_PATH.'mails/questionnaire_fin_commande.html');
				send_mail ($commande->client->email, "Questionnaire de satisfaction HoMe Menus", $messageContent);
			}
			
			$totalCommande++;
			$montantTotal += $commande->prix + $commande->prix_livraison;
			
			if ($commande->annomalie_montant) {
				$totalAnomalie++;
				$montantTotalAnomalie += $commande->annomalie_montant;
			}
			if ($commande->date_validation_restaurant == '0000-00-00 00:00:00' || $commande->date_fin_preparation_restaurant == '0000-00-00 00:00:00') {
				$commandeNonValideResto[] = $commande;
			}
			if ($commande->date_recuperation_livreur == '0000-00-00 00:00:00' || $commande->date_livraison == '0000-00-00 00:00:00') {
				$commandeNonValideLivreur[] = $commande;
			}
			$commande->remove();
		} else {
			$totalCommandeError++;
			$montantTotalError += $commande->prix + $commande->prix_livraison;
			writeLog (CRON_LOG, "Echec historisation commande #".$commande->id." : erreur lors de la sauvegarde", LOG_LEVEL_ERROR);
		}
	}
	
	$messageContent =  file_get_contents (ROOT_PATH.'mails/bilan_commande.html');

	$messageContent = str_replace("[NB_NOUVEAU_CLIENT]", $totalNouveauClient, $messageContent);
	$messageContent = str_replace("[NB_TOTAL_COMMANDE]", $totalCommande, $messageContent);
	$messageContent = str_replace("[MONTANT_TOTAL]", $montantTotal, $messageContent);
	$messageContent = str_replace("[NB_TOTAL_COMMANDE_ANNULE]", $totalCommandeAnnule, $messageContent);
	$messageContent = str_replace("[MONTANT_TOTAL_ANNULE]", $montantTotalAnnule, $messageContent);
	$messageContent = str_replace("[NB_TOTAL_COMMANDE_ERROR]", $totalCommandeError, $messageContent);
	$messageContent = str_replace("[MONTANT_TOTAL_ERROR]", $montantTotalError, $messageContent);
	$messageContent = str_replace("[NB_TOTAL_ANOMALIE]", $totalAnomalie, $messageContent);
	$messageContent = str_replace("[MONTANT_TOTAL_ANOMALIE]", $montantTotalAnomalie, $messageContent);
	
	if (count($commandeNonValideResto) == 0) {
		$commandeNonValideRestoTexte = '<span>Aucune</span><br />';
	} else {
		$commandeNonValideRestoTexte = '<ul>';
		foreach ($commandeNonValideResto as $commande) {
			$commandeNonValideRestoTexte .= '<li> commande #'.$commande->id.' ('.utf8_encode($commande->restaurant->nom).')</li>';
		}
		$commandeNonValideRestoTexte .= '</ul>';
	}
	$messageContent = str_replace("[COMMANDE_NON_VALIDE_RESTAURANT]", $commandeNonValideRestoTexte, $messageContent);
	
	if (count($commandeNonValideLivreur) == 0) {
		$commandeNonValideLivreurTexte = '<span>Aucune</span><br />';
	} else {
		$commandeNonValideLivreurTexte = '<ul>';
		foreach ($commandeNonValideLivreur as $commande) {
			$commandeNonValideLivreurTexte .= '<li> commande #'.$commande->id.' ('.utf8_encode($commande->livreur->nom.' '.$commande->livreur->prenom).')</li>';
		}
		$commandeNonValideLivreurTexte .= '</ul>';
	}
	$messageContent = str_replace("[COMMANDE_NON_VALIDE_LIVREUR]", $commandeNonValideLivreurTexte, $messageContent);
	
	send_mail ("admin@homemenus.fr", "Bilan des commandes", $messageContent);
	
	writeLog (CRON_LOG, "fin historisation commande");