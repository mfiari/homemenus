<?php
	
	$retour = array();
	
	foreach ($result as $key => $value) {
		$retour[] = array();
		$indice = count($retour)-1;
		$retour[$indice]["id"] = $value->id;
		$retour[$indice]["rue"] = utf8_encode($value->rue);
		$retour[$indice]["ville"] = utf8_encode($value->ville);
		$retour[$indice]["code_postal"] = $value->code_postal;
		$retour[$indice]["date"] = $value->date_commande;
		$retour[$indice]["heure_souhaite"] = $value->heure_souhaite;
		$retour[$indice]["minute_souhaite"] = $value->minute_souhaite;
		$retour[$indice]["restaurant"] = array();
		$retour[$indice]["restaurant"]['id'] = utf8_encode($value->restaurant->id);
		$retour[$indice]["restaurant"]['nom'] = utf8_encode($value->restaurant->nom);
		$retour[$indice]["restaurant"]['rue'] = utf8_encode($value->restaurant->rue);
		$retour[$indice]["restaurant"]['ville'] = utf8_encode($value->restaurant->ville);
		$retour[$indice]["restaurant"]['code_postal'] = $value->restaurant->code_postal;
		$retour[$indice]["restaurant"]['latitude'] = $value->restaurant->latitude;
		$retour[$indice]["restaurant"]['longitude'] = $value->restaurant->longitude;
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>