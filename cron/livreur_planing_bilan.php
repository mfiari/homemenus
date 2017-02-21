<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."User.php";
	include_once MODEL_PATH."PDF.php";
	include_once MODEL_PATH."Dispo.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début livreur planing bilan");
	
	$modelUser = new Model_User();
	$livreurs = $modelUser->getAllActifLivreur();
	
	$dateDebut = mktime(0, 0, 0, date('m'), date('d')-6, date('Y'));
	$dateFin = time();
	
	foreach ($livreurs as $livreur) {
			
		$today = date('Y-m-d');
		
		$planingDir = ROOT_PATH.'files/planing/'.$today.'/';
		
		if(!is_dir($planingDir)){
		   mkdir($planingDir, 0777, true);
		}
		$modelLivreur = new Model_User();
		$modelLivreur->id = $livreur->id;
		$user = $modelLivreur->getLivreur();
		$modelDispo = new Model_Dispo();
		$modelDispo->id_livreur = $user->id;
		$user->dispos = $modelDispo->getLivreurDispo();
		for ($i = 0 ; $i <= 6 ; $i++) {
			$curentDate = mktime(0, 0, 0, date('m'), date('d')+$i, date('Y'));
			foreach($user->dispos as $dispo) {
				if ($dispo->id_jour == date('N', $curentDate)) {
					$debut = date('Y-m-d', $curentDate).' '.$dispo->heure_debut.':'.$dispo->minute_debut.':00';
					$fin = date('Y-m-d', $curentDate).' '.$dispo->heure_fin.':'.$dispo->minute_fin.':00';
					$dispo->date = date('d/m/Y', $curentDate);
					$dispo->commande = $modelLivreur->getNbCommandeLivreur($debut, $fin);
				}
			}
		}
		$pdf = new PDF();
		$pdf->generateHoraireLivreur($user);
		$pdf->render('F', $planingDir.'livreur'.$livreur->id.'.pdf');
		
		$user->dispos = $modelDispo->getHistoryLivreurDispo('2016-12-05', '2016-12-11');
		foreach($user->dispos as $dispo) {
			$debut = $dispo->date.' '.$dispo->heure_debut.':'.$dispo->minute_debut.':00';
			$fin = $dispo->date.' '.$dispo->heure_fin.':'.$dispo->minute_fin.':00';
			$dispo->commande = $modelLivreur->getNbCommandeLivreur($debut, $fin);
		}
		$pdf = new PDF();
		$pdf->generateBilanHoraireLivreur($user);
		$pdf->render('F', $planingDir.'livreur'.$livreur->id.'_bilan.pdf');
		
		$attachments = array(
			$planingDir.'livreur'.$livreur->id.'.pdf',
			$planingDir.'livreur'.$livreur->id.'_bilan.pdf'
		);
		
		$messageContent =  file_get_contents (ROOT_PATH.'mails/livreur_planing_bilan.html');
		$messageContent = str_replace("[PRENOM]", $user->prenom, $messageContent);
		$messageContent = str_replace("[NOM]", $user->nom, $messageContent);
		send_mail ($user->email, "Planing livreur", $messageContent, MAIL_FROM_DEFAULT, $attachments);
	}
	
	writeLog (CRON_LOG, "fin livreur planing bilan");