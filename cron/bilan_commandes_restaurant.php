<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Commande.php";
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/Restaurant.php";
	include_once ROOT_PATH."models/CommandeHistory.php";
	include_once ROOT_PATH."models/Menu.php";
	include_once ROOT_PATH."models/Formule.php";
	include_once ROOT_PATH."models/Contenu.php";
	include_once ROOT_PATH."models/Carte.php";
	include_once ROOT_PATH."models/Format.php";
	include_once ROOT_PATH."models/Categorie.php";
	include_once ROOT_PATH."models/Supplement.php";
	include_once ROOT_PATH."models/CodePromo.php";
	include_once ROOT_PATH."models/Option.php";
	include_once ROOT_PATH."models/OptionValue.php";
	include_once ROOT_PATH."models/PDF.php";
	
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