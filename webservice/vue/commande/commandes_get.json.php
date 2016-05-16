<?php
	
	$retour = array();
	
	foreach ($result as $key => $value) {
		$retour[] = array();
		$indice = count($retour)-1;
		$retour[$indice]["id"] = $value->id;
		$retour[$indice]["date"] = $value->date_commande;
		$retour[$indice]["heure_souhaite"] = $value->heure_souhaite;
		$retour[$indice]["minute_souhaite"] = $value->minute_souhaite;
		$retour[$indice]["etape"] = $value->etape;
		$retour[$indice]["restaurant"] = array();
		$retour[$indice]["restaurant"]['id'] = utf8_encode($value->restaurant->id);
		$retour[$indice]["restaurant"]['nom'] = utf8_encode($value->restaurant->nom);
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>