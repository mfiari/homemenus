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
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>