<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/PDF.php";
	include_once ROOT_PATH."models/Dispo.php";
	
	$modelUser = new Model_User();
	$livreurs = $modelUser->getAllActifLivreur();
	
	foreach ($livreurs as $livreur) {
			
		$today = date('Y-m-d');
		
		$planingDir = ROOT_PATH.'files/planing/'.$today.'/';
		
		if(!is_dir($planingDir)){
		   mkdir($planingDir, 0777, true);
		}
		
		$modelUser->id = $livreur->id;
		$user = $modelUser->getLivreur();
		$pdf = new PDF();
		$pdf->generateHoraireLivreur($user);
		$pdf->render('F', $planingDir.'livreur'.$livreur->id.'.pdf');
		
		$attachments = array(
			$planingDir.'livreur'.$livreur->id.'.pdf'
		);
		
		$messageContent =  file_get_contents (ROOT_PATH.'mails/livreur_planing.html');
		$messageContent = str_replace("[PRENOM]", $user->prenom, $messageContent);
		$messageContent = str_replace("[NOM]", $user->nom, $messageContent);
		send_mail ($user->email, "Planing livreur", $messageContent, MAIL_FROM_DEFAULT, $attachments);
	}