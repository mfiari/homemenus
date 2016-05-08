<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/PreCommande.php";
	
	$preCommandeModel = new Model_Pre_Commande();
	$commandes = $preCommandeModel->getNotValidateCommande();
	
	$modelUser = new Model_User();
	$today = new DateTime();
	foreach ($commandes as $preCommande) {
		$date_commande = new DateTime($preCommande->date_commande);
		$difference = $today->diff($date_commande);
		if ($difference->invert == 1 && $difference->days > 0) {
			$messageContent =  file_get_contents (ROOT_PATH.'mails/suppression_pre_commande.html');
			$messageContent = str_replace("[COMMANDE_ID]", $preCommande->id, $messageContent);
			$messageContent = str_replace("[DATE]", $date_commande->format('d/m/Y'), $messageContent);
			send_mail ($preCommande->client->email, "Pre commande supprimé", $messageContent);
			$preCommande->remove();
		} else {
			if ($difference->invert == 0 && $difference->days <= 2) {
				$messageContent =  file_get_contents (ROOT_PATH.'mails/validation_pre_commande.html');
				$messageContent = str_replace("[COMMANDE_ID]", $preCommande->id, $messageContent);
				if ($difference->days == 0) {
					$messageContent = str_replace("[DATE]", "aujourd'hui", $messageContent);
				} else if ($difference->days == 1) {
					$messageContent = str_replace("[DATE]", "demain", $messageContent);
				} else {
					$messageContent = str_replace("[DATE]", $date_commande->format('d/m/Y'), $messageContent);
				}
				$messageContent = str_replace("[HEURE]", $preCommande->heure_souhaite.'h'.$preCommande->minute_souhaite, $messageContent);
				send_mail ($preCommande->client->email, "Pre commande non validé", $messageContent);
			}
		}
	}