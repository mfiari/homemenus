<?php
	
	$retour = array();
	
	foreach ($restaurant->categories as $categorie) {
		$retour[] = array();
		$indice = count($retour)-1;
		$retour[$indice]["id"] = $categorie->id;
		$retour[$indice]["nom"] = utf8_encode($categorie->nom);
		$retour[$indice]["logo"] = $categorie->logo;
	}
	header("Content-type: application/json; charset=utf-8");
	echo json_encode($retour);
	
?>