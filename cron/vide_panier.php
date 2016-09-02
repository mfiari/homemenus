<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Panier.php";
	include_once ROOT_PATH."models/User.php";
	include_once ROOT_PATH."models/Restaurant.php";
	
	$modelPanier = new Model_Panier();
	
	$paniers = $modelPanier->getAll();
	
	foreach ($paniers as $panier) {
		if (!$panier->remove()) {
			writeLog (CRON_LOG, "Echec suppression panier ".$panier->id, LOG_LEVEL_ERROR);
		}
	}