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
	
	$totalCommandeError = 0;
	$montantTotalError = 0;
	
	$modelCommandeHistory = new Model_Commande_History();
	
	foreach ($commandes as $commande) {
		if ($commande->etape != 4) {
			$totalCommandeError++;
			$montantTotalError += $commande->prix + $commande->prix_livraison;
			writeLog (CRON_LOG, "Echec historisation commande #".$commande->id." : commande ".$commande->getStatus (), LOG_LEVEL_ERROR);
		} else if ($modelCommandeHistory->saveCommande($commande)) {
			$messageContent =  file_get_contents (ROOT_PATH.'mails/fin_commande.html');
			send_mail ($commande->client->email, "Merci de votre commande", $messageContent);
			
			$commande->remove();
			$totalCommande++;
			$montantTotal += $commande->prix + $commande->prix_livraison;
		} else {
			$totalCommandeError++;
			$montantTotalError += $commande->prix + $commande->prix_livraison;
			writeLog (CRON_LOG, "Echec historisation commande #".$commande->id." : erreur lors de la sauvegarde", LOG_LEVEL_ERROR);
		}
	}
	
	$messageContent =  file_get_contents (ROOT_PATH.'mails/bilan_commande.html');

	$messageContent = str_replace("[NB_TOTAL_COMMANDE]", $totalCommande, $messageContent);
	$messageContent = str_replace("[MONTANT_TOTAL]", $montantTotal, $messageContent);
	$messageContent = str_replace("[NB_TOTAL_COMMANDE_ERROR]", $totalCommandeError, $messageContent);
	$messageContent = str_replace("[MONTANT_TOTAL_ERROR]", $montantTotalError, $messageContent);
	
	send_mail ("admin@homemenus.fr", "Bilan des commandes", $messageContent);
	
	writeLog (CRON_LOG, "fin historisation commande");