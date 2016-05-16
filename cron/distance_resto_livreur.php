<?php
 
    include_once '../config.php';
	
	include_once ROOT_PATH."function.php";
	include_once ROOT_PATH."models/Template.php";
	include_once ROOT_PATH."models/Restaurant.php";
	include_once ROOT_PATH."models/Dispo.php";
	
	$modelRestaurant = new Model_Restaurant();
	$restaurants = $modelRestaurant->getRestaurantsCalculeDistance();
	//var_dump($commandes);
	
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
			}
		}
	}