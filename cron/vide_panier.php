<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Panier.php";
	include_once MODEL_PATH."User.php";
	include_once MODEL_PATH."Restaurant.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début vide panier");
	
	$modelPanier = new Model_Panier();
	
	$paniers = $modelPanier->getAll();
	
	foreach ($paniers as $panier) {
		if (!$panier->remove()) {
			writeLog (CRON_LOG, "Echec suppression panier ".$panier->id, LOG_LEVEL_ERROR);
		}
	}
	
	writeLog (CRON_LOG, "fin vide panier");