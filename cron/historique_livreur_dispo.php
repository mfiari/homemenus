<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Dispo.php";
	include_once MODEL_PATH."DispoHistory.php";
	include_once MODEL_PATH."User.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début historisation livreur dispo");
	
	$day = date('N') -1;
	if ($day == 0) {
		$day = 7;
	}
	
	$date = strftime("%y-%m-%d", mktime(0, 0, 0, date('m'), date('d')-1, date('y')));
	
	$modelDispo = new Model_Dispo();
	
	$list = $modelDispo->getDispoByDay($day);
	
	foreach ($list as $dispo) {
		
		$dispoHistory = new Model_Dispo_History();
		$dispoHistory->id_livreur = $dispo->livreur->id;
		$dispoHistory->nom = $dispo->livreur->nom;
		$dispoHistory->prenom = $dispo->livreur->prenom;
		$dispoHistory->login = $dispo->livreur->login;
		$dispoHistory->email = $dispo->livreur->email;
		$dispoHistory->rue = $dispo->rue;
		$dispoHistory->ville = $dispo->ville;
		$dispoHistory->code_postal = $dispo->code_postal;
		$dispoHistory->latitude = $dispo->latitude;
		$dispoHistory->longitude = $dispo->longitude;
		$dispoHistory->perimetre = $dispo->perimetre;
		$dispoHistory->vehicule = $dispo->vehicule;
		$dispoHistory->date_dispo = $date;
		$dispoHistory->heure_debut = $dispo->heure_debut;
		$dispoHistory->minute_debut = $dispo->minute_debut;
		$dispoHistory->heure_fin = $dispo->heure_fin;
		$dispoHistory->minute_fin = $dispo->minute_fin;
		
		$dispoHistory->save();
	}
	
	writeLog (CRON_LOG, "fin historisation livreur dispo");