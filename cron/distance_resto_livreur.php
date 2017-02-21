<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once MODEL_PATH."Template.php";
	include_once MODEL_PATH."DbConnector.php";
	include_once MODEL_PATH."Restaurant.php";
	include_once MODEL_PATH."Dispo.php";

	register_shutdown_function("fatal_error_handler");
	
	writeLog (CRON_LOG, "début distance resto livreur");
	
	$modelRestaurant = new Model_Restaurant();
	$restaurants = $modelRestaurant->getRestaurantsCalculeDistance();
	
	$modelDispo = new Model_Dispo();
	$dispos = $modelDispo->getAllActifLivreursDispo();
	foreach ($restaurants as $restaurant) {
		foreach ($dispos as $dispo) {
			$adresseResto = $restaurant->latitude.','.$restaurant->longitude;
			$adresseDispo = $dispo->latitude.','.$dispo->longitude;
			$result = getDistance($adresseDispo, $adresseResto);
			if ($result['status'] == "OK") {
				$distanceRestoKm = $result['distance'] / 1000;
				if (!$modelDispo->addDistanceLivreurResto($restaurant->id, $dispo->id, $distanceRestoKm)) {
					writeLog(CRON_LOG, "erreur addDistanceLivreurResto restaurant : ".$restaurant->id.' ; dispo : '.$dispo->id.' ; distance : '.$distanceRestoKm, LOG_LEVEL_ERROR);
				}
			} else {
				writeLog(CRON_LOG, $result, LOG_LEVEL_ERROR);
			}
		}
		$modelDispo->removeUpdateRestaurant($restaurant->id);
	}
	
	writeLog (CRON_LOG, "fin distance resto livreur");