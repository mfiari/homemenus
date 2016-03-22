<?php
	
	$retour = array();
	
	foreach ($result as $key => $value) {
		$retour[] = array();
		$indice = count($retour)-1;
		$retour[$indice]["id"] = $value["id"];
		$retour[$indice]["nom"] = $value["nom"];
		$retour[$indice]["prix"] = $value["prix"];
		$retour[$indice]["preparation"] = $value["temps_preparation"];
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>