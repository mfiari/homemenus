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
	include_once MODEL_PATH."PDF.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début bilan commande restaurant");
	
	$modelRestaurant = new Model_Restaurant();
	$restaurants = $modelRestaurant->getAllRestaurantEnable();
	
	$modelCommande = new Model_Commande_History();
	
	$dateDebut = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-7, date('Y')));
	$dateFin = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
	/*$dateDebut = '2016-06-03';
	$dateFin = '2017-01-11';*/
	$today = date('Y-m-d');
		
	$CommandeDir = ROOT_PATH.'files/commandes/'.$today.'/';
	
	if(!is_dir($CommandeDir)){
	   mkdir($CommandeDir, 0777, true);
	}
	
	foreach ($restaurants as $restaurant) {
		$commandes = $modelCommande->getCommandesByRestaurant($restaurant->id, $dateDebut.' 00:00:00', $dateFin.' 23:59:59');
	
		if (count($commandes) == 0) {
			writeLog (CRON_LOG, "Aucune commande réalisé pour le restaurant $restaurant->id", LOG_LEVEL_WARNING);
			continue;
		}
		
		$pdf = new PDF();
		$pdf->generateTotalFactureRestaurant($commandes, formatTimestampToDate($dateDebut), formatTimestampToDate($dateFin));
		$pdf->render('F', $CommandeDir.'bilan'.$restaurant->id.'.pdf');
	
		$messageContent =  file_get_contents (ROOT_PATH.'mails/bilan_commande_restaurant.html');
		
		$attachments = array(
			$CommandeDir.'bilan'.$restaurant->id.'.pdf'
		);

		$messageContent = str_replace("[DATE_DEBUT]", formatTimestampToDate($dateDebut), $messageContent);
		$messageContent = str_replace("[DATE_FIN]", formatTimestampToDate($dateFin), $messageContent);
		
		send_mail ("admin@homemenus.fr", "Bilan des commandes", $messageContent, MAIL_FROM_DEFAULT, $attachments);
	}
	
	writeLog (CRON_LOG, "fin bilan commande restaurant");